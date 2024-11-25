PROJECT_NAME=symfony-ddd-cqrs
APP_CONTAINER=symfony-ddd-cqrs-backend-1

run:
	@docker-compose -f docker-compose.yml build --no-cache
	@docker-compose -f docker-compose.yml -p $(PROJECT_NAME) up -d

app-container:
	@docker exec -it $(APP_CONTAINER) bash

run-composer:
	@docker exec -it $(APP_CONTAINER) composer install

check-supervisor:
	@docker exec -it $(APP_CONTAINER) supervisorctl status

