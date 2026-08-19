APP_CONTAINER ?= tasks_app

DOCKER_COMPOSE ?= docker compose --env-file .env

# Docker targets
up:
	$(DOCKER_COMPOSE) up -d

down:
	$(DOCKER_COMPOSE) down

restart:
	$(DOCKER_COMPOSE) down && $(DOCKER_COMPOSE) up -d

logs:
	$(DOCKER_COMPOSE) logs -f

ps:
	$(DOCKER_COMPOSE) ps

fresh:
	$(DOCKER_COMPOSE) down -v
	$(DOCKER_COMPOSE) up -d --build

# App Container Targets
bash:
	docker exec -it $(APP_CONTAINER) bash

zsh:
	docker exec -it $(APP_CONTAINER) zsh

tinker:
	docker exec -it $(APP_CONTAINER) php artisan tinker

# Dev Targets
ide-helper:
	docker exec -it $(APP_CONTAINER) php artisan ide-helper:models -n -M
