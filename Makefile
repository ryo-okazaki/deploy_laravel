install:
	cp laravel/.env.example laravel/.env
	cp laravel/.env.example laravel/.env.prd
	docker-compose up -d --build
	docker-compose run --rm composer install
	docker-compose exec php-fpm php artisan key:generate
	docker-compose exec php-fpm php artisan migrate

up:
	docker-compose up -d

up-build:
	docker-compose up -d --build

down:
	docker-compose down

migrate-fresh:
	docker-compose run --rm php-fpm php artisan migrate:fresh --seed

cache-clear:
	docker-compose run --rm php-fpm php artisan cache:clear
	docker-compose run --rm php-fpm php artisan config:clear
	docker-compose run --rm php-fpm php artisan route:clear
	docker-compose run --rm php-fpm php artisan view:clear

dump-autoload:
	docker-compose run --rm php-fpm composer dump-autoload
	docker-compose run --rm php-fpm php artisan clear-compiled
	docker-compose run --rm php-fpm php artisan optimize
	docker-compose run --rm php-fpm php artisan config:cache