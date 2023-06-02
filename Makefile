#!/usr/bin/make
include .env

SHELL = /bin/sh

APP_PUID := $(shell id -u)
APP_PGID := $(shell id -g)

environment := $(APP_ENV)
file := docker-compose.$(environment).yml

build := (APP_PUID=$(APP_PUID) APP_PGID=$(APP_PGID) docker compose -f $(file) build)
run := (APP_PUID=$(APP_PUID) APP_PGID=$(APP_PGID) docker compose -f $(file) up -d)
restart := (APP_PUID=$(APP_PUID) APP_PGID=$(APP_PGID) docker compose -f $(file) restart)

migrate := (docker compose -f $(file) exec app php artisan migrate --force)
composer-install := (docker compose -f $(file) exec app composer install --no-dev --no-interaction -o)
optimize-clear := (docker compose -f $(file) exec app php artisan optimize:clear)

deploy :
	$(build)
	$(run)
	$(composer-install)
	$(migrate)
	$(optimize-clear)

deploy-without-build:
	$(run)
	$(composer-install)
	$(migrate)
	$(optimize-clear)

run:
	$(run)

# only work inside container
dev:
	/bin/bash ./bin/start-development-server.sh

build:
	$(build)

migrate:
	$(migrate)

composer-install:
	$(composer-install)

optimize-clear:
	$(optimize-clear)

stop:
	docker compose down

restart:
	$(restart)
