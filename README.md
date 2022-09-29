# News Parsing Service

This is a micro service for parsing news from news platforms and storing the details on a database.

## Requirements
* `docker-compose`: [Installation guide](https://www.digitalocean.com/community/tutorials/how-to-install-docker-compose-on-ubuntu-18-04).
* `symfony-cli`: [Installation guide](https://symfony.com/download).
* `amqp`: [Installation guide](https://stackoverflow.com/a/64448402).

## Installation
Once you have the requirements settled, clone this repository.
```bash
git clone repository_url
```

Launch the container images, Requirements
```bash
docker-compose up -d
```

Run the migrations
```bash
symfony console doctrine:migrations:migrate
```

Set the messenger to consume request:
```bash
symfony console messenger:consume async
```

## Usage
To initiate news parsing, call the `news:start-parsing` command:
```bash
symfony console news:start-parsing
```
This command can be added as cron job.


To view processed news articles, start symfony server with:
```bash
symfony serve -d
```

Then visit the webpage at `http://localhost:8000/`


**NB:** If you receive database error, open adminer to be sure the database was created.
```bash
https://localhost:8080
```
If it was not created, create the database with this command:
```bash
symfony console doctrine:database:create
```

**NBB:** If you installed `docker-compose` and/or `symfony` with `sudo`, you might need it for every command above (e.g. `sudo symfony serve -d`).


Check `./docker-compose.yml` for database root user login details and other details.
