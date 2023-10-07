#!/usr/bin/make
build :=  docker compose build
run :=  docker compose up -d

migrate := docker compose exec app php artisan migrate --force
seed := docker compose exec app php artisan db:seed --force
composer-install := docker compose exec app composer install --no-dev --no-interaction -o
composer-install-dev := docker compose exec app composer install --no-interaction -o
npm-install := npm install
optimize-clear := docker compose exec app php artisan optimize:clear

deploy :
	$(build)
	$(run)
	$(composer-install)
	$(migrate)
	$(seed)
	$(optimize-clear)

deploy-without-build:
	$(run)
	$(composer-install)
	$(migrate)
	$(seed)
	$(optimize-clear)

devcontainer:
	$(build)
	$(run)
	$(composer-install-dev)
	$(npm-install)

# only work inside container
start-dev:
	php -d variables_order=EGPCS artisan octane:start --server=swoole --host=0.0.0.0 --rpc-port=6001 --port=9000 --watch

compose-build:
	$(build)

compose-up:
	$(run)

compose-stop:
	docker compose stop

compose-down:
	docker compose down

compose-restart:
	docker compose restart

artisan-migrate:
	$(migrate)

composer-install:
	$(composer-install)

artisan-optimize:
	$(optimize-clear)
