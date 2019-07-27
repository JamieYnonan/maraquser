[![Build Status](https://travis-ci.org/JamieYnonan/maraquser.svg?branch=master)](https://travis-ci.org/JamieYnonan/base-value-object)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/JamieYnonan/maraquser/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/JamieYnonan/maraquser/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/JamieYnonan/maraquser/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/JamieYnonan/maraquser/?branch=master)

# Maraquser
Project (in progress) to manage users roles and permissions.

---

## This project is based on:
- Hexagonal Architecture / DDD
- Command Bus (Tactician)
- Asynchrony (RabbitMQ)
- Docker
- Makefile
- Swagger (Api documentation)
- Unit Testing (PHPUnit)
- Mutation Testing (Infection)
- Symfony Components
- PHP ^7.1 (+ Xdebug)
- Python 3

## Up Project (local)

create a db **maraquser** or change name in app/.env

**workers config:**  
rename file workers/config.json.example to workers/config.json  
changes workers/config.json with real configs

**api config:**  
application app/config  
infrastructure rename app/.env.example to app/.env

    make init_dev

## Show routes:
    make routes

## Down Project:
    make down

## Run Unit Tests:
    make test
    
## Run Mutation Testing (Infection):
if the app/build dir doesn't exist, internally exec _make test_
 
    make infection
    
## Run Doctrine tool:
https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/tools.html

    make doctrine COMMAND="command"
    
## Api documentation with Swagger (in progress):
to view the documentation enter to http://localhost:8080

    make swagger_api
    
    #if you want to use another port (http://localhost:8888)
    make swagger_api SWAGGER_PORT=8888
    
## Makefile help:
    make help
