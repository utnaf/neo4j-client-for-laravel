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

## Saved queries

```
MATCH (b:Beer)
WHERE SIZE((b)<-[:ABOUT]-(:Review)) > 5
MATCH (s:Style)<-[:STYLE]-(b)<-[:ABOUT]-(r:Review)
WITH b AS beer, s AS style, AVG(r.rating) AS average, STDEV(r.rating) AS stddev
WHERE stddev < 0.6 AND average > 3
WITH beer, style.name AS style, stddev, average,
SIZE((beer)<-[:ABOUT]-(:Review)) AS number
ORDER BY average DESC, stddev DESC, number DESC
WITH style, COLLECT(beer) AS beers
RETURN style, beers[..3]
```
