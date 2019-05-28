.DEFAULT_GOAL	:= help

USERNAME_LOCAL	= "$(shell whoami)"
UID_LOCAL		= "$(shell id -u)"
GID_LOCAL		= "$(shell id -g)"

LOCAL_IP		= "$$(ip route get 8.8.4.4 | head -1 | awk '{print $$7}')"
XDEBUG_PORT		= 9999
XDEBUG_IDEKEY	= PHPSTORM

API_IMAGE		= mu_api
CLI_IMAGE		= mu_cli
WORKER_IMAGE	= mu_worker
DOCKER_STACK	= maraquser

build_api_image_base: ## Build base api image: make build_api_image_base
	docker build --force-rm \
		--build-arg USERNAME_LOCAL=${USERNAME_LOCAL} \
    	--build-arg UID_LOCAL=${UID_LOCAL} \
    	--build-arg GID_LOCAL=${GID_LOCAL} \
		-t ${API_IMAGE}:base docker/application

build_api_image_dev: ## Build dev api image: make build_api_image_dev
	@make build_api_image_base
	docker build --force-rm \
		--build-arg IMAGE=${API_IMAGE}:base \
		--build-arg XDEBUG_HOST=${LOCAL_IP} \
		--build-arg XDEBUG_PORT=${XDEBUG_PORT} \
		--build-arg XDEBUG_IDEKEY=${XDEBUG_IDEKEY} \
		-t ${API_IMAGE}:dev docker/application/dev

build_cli_image: ## Build cli image: make build_cli_image
	docker build --force-rm \
		--build-arg USERNAME_LOCAL=${USERNAME_LOCAL} \
		--build-arg UID_LOCAL=${UID_LOCAL} \
		--build-arg GID_LOCAL=${GID_LOCAL} \
		-t ${CLI_IMAGE} docker/cli

build_wk_image: ## Build worker image: make build_wk_image
	docker build --force-rm \
		--build-arg USERNAME_LOCAL=${USERNAME_LOCAL} \
		--build-arg UID_LOCAL=${UID_LOCAL} \
		--build-arg GID_LOCAL=${GID_LOCAL} \
		-t ${WORKER_IMAGE} docker/worker

up_dev: ## Up application dev: make up_dev
	API_IMAGE=${API_IMAGE}:dev \
	APP_DEBUG=1 \
	docker stack deploy -c docker/docker-compose.yml ${DOCKER_STACK}

down: ## Down application: make down
	docker stack rm ${DOCKER_STACK}

service: ## List docker services: make service
	docker service ls

composer: ## Execute composer with cli image: make composer COMMAND="command"
	docker run --rm -it -u ${UID_LOCAL}:${GID_LOCAL} \
		-v $$PWD/app:/app ${CLI_IMAGE} \
		bash -c "composer ${COMMAND}"

console: ## Execute php console (cli image): make console COMMAND="command"
	docker run --rm -it -u ${UID_LOCAL}:${GID_LOCAL} \
		-v $$PWD/app:/app ${CLI_IMAGE} \
		bash -c "php bin/console ${COMMAND}"

routes: ## Show list routes: make routes
	@make console COMMAND="debug:router"

test: ## Execute tests (composer test): make test
	rm -Rf $$PWD/app/build; \
	@make composer COMMAND=test

infection: ## Execute infection (composer infection). First execute "make test": make infection
	@make composer COMMAND=infection"; \
	rm -Rf $$PWD/app/var

doctrine: ## Execute docker (composer doctrine): make doctrine COMMAND="command"
	docker run --rm -it -u ${UID_LOCAL}:${GID_LOCAL} \
		--network container:$$(docker ps | grep ${DOCKER_STACK}_mysql | awk '{print $$1}') \
		-v $$PWD/app:/app \
		${CLI_IMAGE} bash -c "composer doctrine ${COMMAND}"

## Help ##
help:
	@printf "\033[31m%-16s %-70s %s\033[0m\n" "Target" "Help" "Usage"; \
	printf "\033[31m%-16s %-70s %s\033[0m\n" "------" "----" "-----"; \
	grep -hE '^\S+:.*## .*$$' $(MAKEFILE_LIST) | sed -e 's/:.*##\s*/:/' | sort | awk 'BEGIN {FS = ":"}; {printf "\033[32m%-16s\033[0m %-69s \033[34m%s\033[0m\n", $$1, $$2, $$3}'
