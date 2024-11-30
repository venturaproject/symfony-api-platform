ifneq (,$(wildcard .env))
    include .env
    export
endif

# Executables (local)
DOCKER_COMP = docker compose

# Executables
PHP      = $(PHP_CONT) php
COMPOSER = $(PHP_CONT) composer
SYMFONY  = $(PHP) bin/console

# Variables
PROJECT_NAME ?= api-platform
PROJECT_REFERENCE ?= symfony-api-platform
APP_CONTAINER = symfony-${PROJECT_NAME}
FRONTEND_CONTAINER = react-${PROJECT_NAME}
DB_CONTAINER = mysql-${PROJECT_NAME}
SUPERVISOR_CONTAINER = supervisor-${PROJECT_NAME}
RABBITMQ_CONTAINER = rabbitmq-${PROJECT_NAME}

JWT_PATH=api/config/jwt
JWT_PRIVATE_KEY=$(JWT_PATH)/private.pem
JWT_PUBLIC_KEY=$(JWT_PATH)/public.pem
JWT_PASSPHRASE=bf48ad0c8c3d089174b66723913c2e108848bab364e3da39ef3d5b61059ac371

run:
	@docker-compose -f docker-compose.yml build --no-cache
	@docker-compose -f docker-compose.yml -p $(PROJECT_REFERENCE) up -d


down:
	@docker-compose down --remove-orphans

logs: 
	@$(DOCKER_COMP) logs --tail=0 --follow

sta:
	@docker exec -it $(APP_CONTAINER) php vendor/bin/phpstan analyse src --level=5

migrations:
	@docker exec -it $(APP_CONTAINER) php bin/console doctrine:migrations:migrate --no-interaction
	
migrate-test-db:
	@docker exec -it $(APP_CONTAINER) php bin/console doctrine:migrations:migrate --env=test --no-interaction
	@echo "Migrations executed for test database."

fixtures:
	@docker exec -it $(APP_CONTAINER) php bin/console doctrine:fixtures:load --append

fixtures-test:
	@docker exec -it $(APP_CONTAINER) php bin/console doctrine:fixtures:load --env=test --no-interaction --purge-with-truncate

create-user-test:
	@docker exec -it $(APP_CONTAINER) php bin/console app:create-user-cli --env=test testuser testuser@example.com testpassword

messenger-consume:
	@docker exec -it $(APP_CONTAINER) php bin/console doctrine:fixtures:load --append
	 
app-container:
	@docker exec -it $(APP_CONTAINER) bash

frontend-container:
	@docker exec -it $(FRONTEND_CONTAINER) bash

db-container:
	@docker exec -it $(DB_CONTAINER) bash

create-db:
	@docker exec -it $(DB_CONTAINER) mysql -u root -p$(DB_ROOT_PASSWORD) -e "CREATE DATABASE IF NOT EXISTS symfony_api_test;"
	@echo "Database 'symfony_api_test' created (if it didn't exist)."

grant-permissions:
	@docker exec -i mysql-${PROJECT_NAME} mysql -u root -proot -e "GRANT ALL PRIVILEGES ON symfony_api_test.* TO 'admin'@'%'; FLUSH PRIVILEGES;"
	
supervisor-container:
	@docker exec -it $(SUPERVISOR_CONTAINER) bash

rabbitmq-container:
	@docker exec -it $(RABBITMQ_CONTAINER) bash

jwt-keys:
	@mkdir -p $(JWT_PATH)
	@echo "Generating private key..."
	@openssl genpkey -algorithm RSA -out $(JWT_PRIVATE_KEY) -aes-256-cbc -pass pass:$(JWT_PASSPHRASE) -pkeyopt rsa_keygen_bits:2048
	@echo "Generating public key...."
	@openssl rsa -pubout -in $(JWT_PRIVATE_KEY) -out $(JWT_PUBLIC_KEY) -passin pass:$(JWT_PASSPHRASE)
	@echo "JWT keys generated in $(JWT_PATH):"
	@ls -l $(JWT_PATH)

	




