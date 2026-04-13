.PHONY: help up down docker-env
.SILENT: help up down docker-env
.DEFAULT_GOAL: help

## Variables
CONFIG_ARGS=-f ./docker-environment/docker-compose.yml --env-file ./docker-environment/.env

## Commands
help: ## Show help
	@printf "\033[33m%s:\033[0m\n" 'Available commands'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  \033[32m%-18s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

up: ## Start containers
	docker compose -f ./docker-environment/docker-compose.yml --env-file ./docker-environment/.env up -d --build mysql workspace php-fpm nginx zookeeper kafka

down: ## Down containers
	docker stop $$(docker ps --filter "name=cookbook_*" -q)

bash: ## Login into application container
	docker compose -f ./docker-environment/docker-compose.yml --env-file ./docker-environment/.env exec --user=luke workspace bash | tee /dev/null

docker-env: ## Copy .env and prepare for build
	cp ./docker-environment/.env.example ./docker-environment/.env
	cp ./docker-environment/nginx/sites/local-cookbook.conf.example ./docker-environment/nginx/sites/local-cookbook.conf
	cp ./app/.env.example ./app/.env
