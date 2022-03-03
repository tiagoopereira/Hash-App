up:
	docker-compose up -d
down:
	docker-compose down
bash:
	docker exec -it hash_app /bin/bash
composer:
	composer install
migration:
	docker exec -it hash_app php bin/console doctrine:migrations:migrate --no-interaction
run: up composer migration