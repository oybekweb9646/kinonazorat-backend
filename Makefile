up:
	docker compose up -d

down:
	docker compose down

build:
	docker compose up --build -d

php:
	docker compose run php-cli bash

migrate:
	docker compose run php-cli php artisan migrate

seeder:
	docker compose run php-cli php artisan db:seed
