# Quotes API

This is a REST API that returns quotes of a famous person

## Installation

To run this project locally you should run this command in project root directory

`$ docker-compose up --build -d`

Then install all dependencies in the docker container

`$ docker-compose exec php composer install`

After composer has installed all dependencies, APIs will be available by `http://localhost:8080`

## Testing

Run `$ docker-compose exec php bin/phpunit` to be sure everything is working correctly

