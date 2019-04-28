.DEFAULT_GOAL	:= help

USERNAME_LOCAL	?= "$(shell whoami)"
UID_LOCAL  		?= "$(shell id -u)"
GID_LOCAL  		?= "$(shell id -g)"

IMAGE_APP		= mu_api
IMAGE_CLI 		= mu_cli
IMAGE_WORKER 	= mu_worker
DOCKER_STACK	= maraquser

build_image_api: ## Build api image: make build_image_api
	docker build --force-rm \
		--build-arg USERNAME_LOCAL=${USERNAME_LOCAL} \
    	--build-arg UID_LOCAL=${UID_LOCAL} \
    	--build-arg GID_LOCAL=${GID_LOCAL} \
		-t ${IMAGE_APP} docker/application

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

up: ## Up application: make up
	docker stack deploy -c docker/docker-compose.yml ${DOCKER_STACK}

down: ## Down application: make down
	docker stack rm ${DOCKER_STACK} && docker rm $$(docker ps -a -q) -f

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