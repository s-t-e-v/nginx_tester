include srcs/.env  # Load variables from .env

COMPOSE_FILE := srcs/docker-compose.yml

all: up

up: prepare
	docker compose -f $(COMPOSE_FILE) up ${OPTS} --build $(ARGS)

prepare:
	@mkdir -p $(DATA_DIR)/website

stop:
	docker compose -f $(COMPOSE_FILE) stop

down:
	docker compose -f $(COMPOSE_FILE) down

re: down all

del:
	docker stop $$(docker ps -aq) 2>/dev/null || true
	docker rm $$(docker ps -aq) 2>/dev/null || true
	docker rmi $$(docker images -aq) 2>/dev/null || true
	docker volume prune -f
	docker container prune -f
	docker system prune --all --force --volumes
	docker volume rm -f $$(docker volume ls | grep -v DRIVER | tr -s " " | cut -d " " -f 2 | tr "\n" " ") 2>/dev/null || true

.PHONY: up prepare stop down del
