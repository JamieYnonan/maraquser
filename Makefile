.DEFAULT_GOAL	:= help

USERNAME_LOCAL	?= "$(shell whoami)"
UID_LOCAL  		?= "$(shell id -u)"
GID_LOCAL  		?= "$(shell id -g)"

IMAGE_APP		= mu_app
IMAGE_CLI 		= mu_cli
IMAGE_WORKER 	= mu_worker
SERVICE_NAME	= maraquser
CONATAINER_NAME	= maraquser

DOCKER_NETWORK	= maraquser

build_image_app: ## Build app image: make build_image_app
	docker build --force-rm \
		--build-arg USERNAME_LOCAL=${USERNAME_LOCAL} \
    	--build-arg UID_LOCAL=${UID_LOCAL} \
    	--build-arg GID_LOCAL=${GID_LOCAL} \
		-t ${IMAGE_APP} docker/application

build_image_cli: ## Build cli image: make build_image_app
	docker build --force-rm \
		--build-arg USERNAME_LOCAL=${USERNAME_LOCAL} \
		--build-arg UID_LOCAL=${UID_LOCAL} \
		--build-arg GID_LOCAL=${GID_LOCAL} \
		-t ${IMAGE_CLI} docker/cli

up: ## Up application: make up
	@make create-network
	DOCKER_NETWORK=$(DOCKER_NETWORK) \
	docker-compose -f docker/docker-compose.yml up -d --remove-orphans

create-network: ## Create network in docker: make verify_network
	@if [ $$(docker network ls | grep maraquser | wc -l) -eq 0  ]; then\
		docker network create $(DOCKER_NETWORK);\
	fi

down: ## Down application: make down
	DOCKER_NETWORK=$(DOCKER_NETWORK) \
	docker-compose -f docker/docker-compose.yml down

composer: ## Execute composer with cli image: make composer COMMAND="command"
	docker run --rm -it -u ${UID_LOCAL}:${GID_LOCAL} \
		-v $$PWD/app:/app \
		-v $$HOME/.ssh:/home/${USERNAME_LOCAL}/.ssh ${IMAGE_CLI} \
		bash -c "composer ${COMMAND}"

test: ## Execute tests (composer test): make test
	rm -Rf $$PWD/app/build; \
	docker run --rm -it -u ${UID_LOCAL}:${GID_LOCAL} \
			-v $$PWD/app:/app \
			-v $$HOME/.ssh:/home/${USERNAME_LOCAL}/.ssh ${IMAGE_CLI} \
			bash -c "composer test"; \

infection: ## Execute infection (composer infection). First execute "make test": make infection
	docker run --rm -it -u ${UID_LOCAL}:${GID_LOCAL} \
			-v $$PWD/app:/app \
			-v $$HOME/.ssh:/home/${USERNAME_LOCAL}/.ssh ${IMAGE_CLI} \
			bash -c "composer infection"; \
	rm -Rf $$PWD/app/var

doctrine: ## Execute docker (composer doctrine): make doctrine COMMAND="command"
	docker run --rm -it -u ${UID_LOCAL}:${GID_LOCAL} \
		--network container:${CONATAINER_NAME} \
		-v $$PWD/app:/app \
		-v $$HOME/.ssh:/home/${USERNAME_LOCAL}/.ssh ${IMAGE_CLI} \
		bash -c "composer doctrine ${COMMAND}"

## Help ##
help:
	@printf "\033[31m%-16s %-70s %s\033[0m\n" "Target" "Help" "Usage"; \
	printf "\033[31m%-16s %-70s %s\033[0m\n" "------" "----" "-----"; \
	grep -hE '^\S+:.*## .*$$' $(MAKEFILE_LIST) | sed -e 's/:.*##\s*/:/' | sort | awk 'BEGIN {FS = ":"}; {printf "\033[32m%-16s\033[0m %-69s \033[34m%s\033[0m\n", $$1, $$2, $$3}'