# Hops

### Requirements

* PHP 8+
* composer
* Docker
* NodeJS

### How to use

Before running the app for the first time you need to run a couple of commands:

```
composer install
npm install
```

Start Neo4j
```
docker-compose up
```

Start the app

```
php artisan serve
```

Seed the Database

```
make neo4j-seed
```
