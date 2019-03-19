.DEFAULT_GOAL	:= help

USERNAME_LOCAL  ?= "$(shell whoami)"
UID_LOCAL  		?= "$(shell id -u)"
GID_LOCAL  		?= "$(shell id -g)"

IMAGE_APP		= mu_app
IMAGE_CLI 		= mu_cli
SERVICE_NAME	= maraquser
CONATAINER_NAME	= maraquser

build_image_app:
	docker build --force-rm \
		--build-arg USERNAME_LOCAL=${USERNAME_LOCAL} \
    	--build-arg UID_LOCAL=${UID_LOCAL} \
    	--build-arg GID_LOCAL=${GID_LOCAL} \
		-it ${IMAGE_APP} docker/application

build_image_cli:
	docker build --force-rm \
		--build-arg USERNAME_LOCAL=${USERNAME_LOCAL} \
		--build-arg UID_LOCAL=${UID_LOCAL} \
		--build-arg GID_LOCAL=${GID_LOCAL} \
		-it ${IMAGE_CLI} docker/cli

up:
	UID=${UID_LOCAL} GID=${GID_LOCAL} docker-compose -f docker/docker-compose.yml up -d --remove-orphans

down:
	UID=${UID_LOCAL} GID=${GID_LOCAL} docker-compose -f docker/docker-compose.yml down

composer:
	docker run --rm -it -u ${UID_LOCAL}:${GID_LOCAL} \
		-v $$PWD/app:/app \
		-v $$HOME/.ssh:/home/${USERNAME_LOCAL}/.ssh ${IMAGE_CLI} \
		bash -c "composer ${COMMAND}"

test:
	rm -Rf $$PWD/app/build; \
	docker run --rm -it -u ${UID_LOCAL}:${GID_LOCAL} \
    		-v $$PWD/app:/app \
    		-v $$HOME/.ssh:/home/${USERNAME_LOCAL}/.ssh ${IMAGE_CLI} \
    		bash -c "composer test && composer infection"; \
    rm -Rf $$PWD/app/var

doctrine:
	docker run --rm -it -u ${UID_LOCAL}:${GID_LOCAL} \
		--network container:${CONATAINER_NAME} \
		-v $$PWD/app:/app \
		-v $$HOME/.ssh:/home/${USERNAME_LOCAL}/.ssh ${IMAGE_CLI} \
		bash -c "composer doctrine ${COMMAND}"

## Help ##
help:
	@printf "\033[31m%-16s %-59s %s\033[0m\n" "Target" "Help" "Usage"; \
	printf "\033[31m%-16s %-59s %s\033[0m\n" "------" "----" "-----"; \
	grep -hE '^\S+:.*## .*$$' $(MAKEFILE_LIST) | sed -e 's/:.*##\s*/:/' | sort | awk 'BEGIN {FS = ":"}; {printf "\033[32m%-16s\033[0m %-58s \033[34m%s\033[0m\n", $$1, $$2, $$3}'