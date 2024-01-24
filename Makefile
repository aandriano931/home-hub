# Makefile for Laravel Project

# Docker Compose commands
DOCKER_COMPOSE = docker-compose
DOCKER_EXEC = $(DOCKER_COMPOSE) exec
APP_CONTAINER = app
MYSQL_CONTAINER = mysql
PHPMYADMIN_CONTAINER = phpmyadmin
NGINX_CONTAINER = nginx

# Laravel Artisan commands
ARTISAN = $(DOCKER_EXEC) $(APP_CONTAINER) php artisan

# Makefile Targets
install:
	$(DOCKER_COMPOSE) up -d --build
	$(DOCKER_EXEC) $(APP_CONTAINER) composer install
	$(DOCKER_EXEC) $(APP_CONTAINER) php artisan key:generate
	$(DOCKER_EXEC) $(APP_CONTAINER) php artisan migrate

start:
	$(DOCKER_COMPOSE) up -d

stop:
	$(DOCKER_COMPOSE) down

restart:
	$(DOCKER_COMPOSE) restart

logs:
	$(DOCKER_COMPOSE) logs -f

# Composer Commands
composer-update:
	$(DOCKER_EXEC) $(APP_CONTAINER) composer update

composer-install:
	$(DOCKER_EXEC) $(APP_CONTAINER) composer install

# Laravel Artisan Commands
migrate:
	$(ARTISAN) migrate

test:
	$(ARTISAN) test

# Access Container Shells
sh-mysql:
	$(DOCKER_EXEC) $(MYSQL_CONTAINER) /bin/bash

sh-phpmyadmin:
	$(DOCKER_EXEC) $(PHPMYADMIN_CONTAINER) /bin/bash

sh-nginx:
	$(DOCKER_EXEC) $(NGINX_CONTAINER) /bin/sh

sh-app:
	$(DOCKER_EXEC) $(APP_CONTAINER) /bin/bash

# Help command
help:
	@echo "Available targets:"
	@echo "  - install:       Build and set up the project (composer install, key generation, migration with seeding)"
	@echo "  - start:         Start the Docker containers"
	@echo "  - stop:          Stop and remove the Docker containers"
	@echo "  - restart:       Restart the Docker containers"
	@echo "  - logs:          View Docker container logs"
	@echo "  - migrate:       Run Laravel database migrations"
	@echo "  - seed:          Run Laravel database seeding"
	@echo "  - test:          Run Laravel tests"
	@echo "  - sh-mysql:      Access MySQL container shell"
	@echo "  - sh-phpmyadmin: Access phpMyAdmin container shell"
	@echo "  - sh-nginx:      Access Nginx container shell"
	@echo "  - sh-app:        Access Laravel app container shell"
