# Setup Configuration
- Project with Laravel 9, Docker and Mysql 5.7.

## 1 - Clonar Repositório

```
git clone https://github.com/especializati/setup-docker-laravel.git
```

## 2 - Criar o Arquivo .env e adicionar da senha da base de dados
```sh
cp .env.example .env
```


## 3 - Suba os containers do projeto
```sh
docker-compose up -d
```


## 4 - Acessar o container
```sh
docker-compose exec app bash
```


## 5 - Instalar as dependências do projeto
```sh
composer install
```

## 6 - Acessar o projeto, criar a APP_KEY e atualizar o browser 
[http://localhost:8989](http://localhost:8989)



#  To make a Form Request
php artisan make:request NameRequest


# To make a test
php artisan make:test BasicTest


# To make factory
php artisan make:factory PostFactory


# To auto load dependencies
composer dump-autoload

# To make seed
php artisan make:seed PostSeeder

# To run seeder
php artisan db:seed PostSeeder

# Code Coverage
vendor/bin/phpunit --coverage-text
vendor/bin/phpunit --coverage-html tests/coverage

php artisan test --coverage


# Create Factory
php artisan make:factory PostFactory --model=Post# church-music
