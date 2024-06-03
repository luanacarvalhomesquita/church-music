t=

make down:
    docker-compose stop
php:
    docker-compose exec app sh
up:
    docker-compose up --detach
destroy:
    docker-compose down --volumes
build:
    docker-compose up --detach --build
seed:
    docker-compose exec app php artisan db:seed
migrate:
    docker-compose exec app php artisan migrate:fresh
docker clean : 
	docker-compose stop
	docker container prune -f
	docker volume prune -f
	docker network prune -f
test all:
	docker-compose exec app php vendor/bin/phpunit