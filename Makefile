.DEFAULT_GOAL	:= help

USERNAME_LOCAL	?= "$(shell whoami)"
UID_LOCAL  		?= "$(shell id -u)"
GID_LOCAL  		?= "$(shell id -g)"

LOCAL_IP		= "$$(ip route get 8.8.4.4 | head -1 | awk '{print $$7}')"
XDEBUG_PORT		= 9999
XDEBUG_IDEKEY	= PHPSTORM


IMAGE_API		= mu_api
IMAGE_CLI 		= mu_cli
IMAGE_WORKER 	= mu_worker
DOCKER_STACK	= maraquser

build_image_api_base: ## Build base api image: make build_image_api_base
	docker build --force-rm \
		--build-arg USERNAME_LOCAL=${USERNAME_LOCAL} \
    	--build-arg UID_LOCAL=${UID_LOCAL} \
    	--build-arg GID_LOCAL=${GID_LOCAL} \
		-t ${IMAGE_API}:base docker/application

build_image_api_dev: ## Build dev api image: make build_image_api_dev
	@make build_image_api_base
	docker build --force-rm \
		--build-arg IMAGE=${IMAGE_API}:base \
		--build-arg XDEBUG_HOST=${LOCAL_IP} \
		--build-arg XDEBUG_PORT=${XDEBUG_PORT} \
		--build-arg XDEBUG_IDEKEY=${XDEBUG_IDEKEY} \
		-t ${IMAGE_API}:dev docker/application/dev

build_image_api_prod: ## Build prod api image: make build_image_api_prod
	@make build_image_api_base
	docker build --force-rm \
		--build-arg IMAGE=${IMAGE_API}:base \
		-t ${IMAGE_API}:prod docker/application/prod

build_image_cli: ## Build cli image: make build_image_cli
	docker build --force-rm \
		--build-arg USERNAME_LOCAL=${USERNAME_LOCAL} \
		--build-arg UID_LOCAL=${UID_LOCAL} \
		--build-arg GID_LOCAL=${GID_LOCAL} \
		-t ${IMAGE_CLI} docker/cli

build_image_wk: ## Build worker image: make build_image_wk
	docker build --force-rm \
		--build-arg USERNAME_LOCAL=${USERNAME_LOCAL} \
		--build-arg UID_LOCAL=${UID_LOCAL} \
		--build-arg GID_LOCAL=${GID_LOCAL} \
		-t ${IMAGE_WORKER} docker/worker

up_dev: ## Up application dev: make up_dev
	IMAGE_API=${IMAGE_API}:dev \
	docker stack deploy -c docker/docker-compose.yml ${DOCKER_STACK}

up_prod: ## Up application prod: make up_prod
	IMAGE_API=${IMAGE_API}:prod \
	docker stack deploy -c docker/docker-compose.yml ${DOCKER_STACK}

down: ## Down application: make down
	docker stack rm ${DOCKER_STACK}

service: ## List docker services: make service
	docker service ls

composer: ## Execute composer with cli image: make composer COMMAND="command"
	docker run --rm -it -u ${UID_LOCAL}:${GID_LOCAL} \
		-v $$PWD/app:/app ${IMAGE_CLI} \
		bash -c "composer ${COMMAND}"

console: ## Execute php console (cli image): make console COMMAND="command"
	docker run --rm -it -u ${UID_LOCAL}:${GID_LOCAL} \
		-v $$PWD/app:/app ${IMAGE_CLI} \
		bash -c "php bin/console ${COMMAND}"

routes: ## Show list routes: make routes
	@make console COMMAND="debug:router"

test: ## Execute tests (composer test): make test
	rm -Rf $$PWD/app/build; \
	docker run --rm -it -u ${UID_LOCAL}:${GID_LOCAL} \
		-v $$PWD/app:/app ${IMAGE_CLI} \
		bash -c "composer test"

infection: ## Execute infection (composer infection). First execute "make test": make infection
	docker run --rm -it -u ${UID_LOCAL}:${GID_LOCAL} \
		-v $$PWD/app:/app ${IMAGE_CLI} \
		bash -c "composer infection"; \
	rm -Rf $$PWD/app/var

doctrine: ## Execute docker (composer doctrine): make doctrine COMMAND="command"
	docker run --rm -it -u ${UID_LOCAL}:${GID_LOCAL} \
		--network container:$$(docker ps | grep ${DOCKER_STACK}_mysql | awk '{print $$1}') \
		-v $$PWD/app:/app \
		-v $$HOME/.ssh:/home/${USERNAME_LOCAL}/.ssh ${IMAGE_CLI} \
		bash -c "composer doctrine ${COMMAND}"

## Help ##
help:
	@printf "\033[31m%-16s %-70s %s\033[0m\n" "Target" "Help" "Usage"; \
	printf "\033[31m%-16s %-70s %s\033[0m\n" "------" "----" "-----"; \
	grep -hE '^\S+:.*## .*$$' $(MAKEFILE_LIST) | sed -e 's/:.*##\s*/:/' | sort | awk 'BEGIN {FS = ":"}; {printf "\033[32m%-16s\033[0m %-69s \033[34m%s\033[0m\n", $$1, $$2, $$3}'