# Maraquser
Project (in progress) to manage users roles and permissions.

---

## This project is based on:
- Hexagonal Architecture / DDD
- Command Bus (Tactician)
- Asynchrony (RabbitMQ)
- Docker
- Makefile
- Unit Testing (PHPUnit)
- Mutation Testing (Infection)
- Symfony Components
- PHP ^7.1
- Python 3

## Up Project

create a db **maraquser** or change name in app/.env

**workers config:**  
rename file workers/config.json.example to workers/config.json  
changes workers/config.json with real configs

**api config:**  
application app/config  
infrastructure app/.env

    make build_image_cli
    make build_image_api
    make build_image_wk
    
    make composer COMMAND="install"
    
    make up
    
    make doctrine COMMAND="orm:schema-tool:create"

## Down Project:
    make down

## Run Unit Tests:
    make test
    
## Run Mutation Testing (Infection):
first run "make test"
    
    make infection
    
## Run Doctrine tool:
https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/tools.html

    make doctrine COMMAND="command"
    
## Makefile help:
    make help
