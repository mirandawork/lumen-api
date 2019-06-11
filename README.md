
# Lumen API

This is a implementation of API using Lumen, Doctrine, Migrations, Swagger, and PHPUnit.

## Installation
This project use docker-compose.
 
```bash
$ git clone https://github.com/mirandawork/lumen-api.git
$ cd lumen-api
$ docker-compose up --build
```

Run the commands after all the containers are running.

```bash
$ docker-compose exec backend bash
$ cp .env.local .env
$ composer.phar install 
$ php artisan doctrine:migrations:migrate
```

## Usage

Run phpunit inside the backend(php-fpm) container.

```bash
$ ./vendor/bin/phpunit
```

The [swagger ui](http://localhost:3000/api/documentation) is located in [http://localhost:3000/api/documentation](http://localhost:3000/api/documentation).

The [swagger.json]((http://localhost:3000/docs)) can be view in [http://localhost:3000/docs](http://localhost:3000/docs). 

The swagger.json is generated by the libraries darkaonline/swagger-lume and zircote/swagger-php. It is located in storage/api-docs/api-docs.json

## Improvements

* Add the migrations to an entry point in Dockerfile.
* Add more tests.


# License
[MIT](https://choosealicense.com/licenses/mit/)
