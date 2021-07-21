CREATE CONSTRAINT user_id_unique IF NOT exists ON (u:User) ASSERT u.id IS UNIQUE;
CREATE CONSTRAINT user_email_unique IF NOT exists ON (u:User) ASSERT u.email IS UNIQUE;
CREATE CONSTRAINT brewery_id_unique IF NOT exists ON (b:Brewery) ASSERT b.id IS UNIQUE;
CREATE CONSTRAINT beer_id_unique IF NOT exists ON (b:Beer) ASSERT b.id IS UNIQUE;
CREATE CONSTRAINT style_unique IF NOT exists ON (s:Style) ASSERT s.name IS UNIQUE;

CALL apoc.load.csv("file:///breweries.csv") YIELD map
MERGE (br:Brewery {id:map.id}) SET br.name = map.name
MERGE (c:City {name:map.city})
MERGE (br)-[:FROM]->(c)
MERGE (s:State {name:map.state})
MERGE (c)-[:IN]->(s);

CALL apoc.load.csv("file:///beers.csv") YIELD map
MERGE (be:Beer {id:toInteger(map.id)}) SET
be.ibu = ROUND(toFloat(map.ibu), 2),
be.abv = ROUND(toFloat(map.abv), 3),
be.name = map.name
MERGE (s:Style {name:map.style})
MERGE (be)-[:STYLE]->(s)
WITH be, map
MATCH (br:Brewery {id: map.brewery_id})
MERGE (be)-[:BREWED_BY]->(br);

UNWIND RANGE(1, 50) AS _id
MERGE (u:User {
    id: apoc.create.uuid(),
    email:"user_" + _id + "@gmail.com",
    name:"User " + _id,
    password:"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi"
});

UNWIND RANGE(1, 500000) AS _id
MATCH (b:Beer {id: toInteger(ROUND(RAND()* 2410 + 1))})
WITH b
CREATE (r:Review {rating:ROUND(RAND()* 5 + 1, 1)})
CREATE (r)-[:ABOUT]->(b)
WITH r
MATCH (u:User {name: "User " +  toInteger(ROUND(RAND()* 50 + 1))})
WITH u, r
CREATE (u)-[:WROTE]->(r);
