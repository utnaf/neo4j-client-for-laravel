CREATE CONSTRAINT user_id_unique IF NOT exists ON (u:User) ASSERT u.id IS UNIQUE;
CREATE CONSTRAINT user_email_unique IF NOT exists ON (u:User) ASSERT u.email IS UNIQUE;
CREATE CONSTRAINT brewery_id_unique IF NOT exists ON (b:Brewery) ASSERT b.id IS UNIQUE;
CREATE CONSTRAINT beer_id_unique IF NOT exists ON (b:Beer) ASSERT b.id IS UNIQUE;

CALL apoc.load.csv("file:///breweries.csv") YIELD map
MERGE (br:Brewery {id:map.id}) SET br.name = map.name
MERGE (c:City {name:map.city})
MERGE (br)-[:FROM]->(c)
MERGE (s:State {name: TRIM(map.state)})
MERGE (c)-[:IN]->(s);

CALL apoc.load.csv("file:///beers.csv") YIELD map
MERGE (be:Beer {id:toInteger(map.id)}) SET
be.ibu = ROUND(toFloat(map.ibu), 2),
be.abv = ROUND(toFloat(map.abv), 3),
be.name = map.name,
be.style = map.style
WITH be, map
MATCH (br:Brewery {id: map.brewery_id})
MERGE (be)-[:BREWED_BY]->(br);

MATCH (be:Beer)
WHERE be.ibu IS NULL
DETACH DELETE be;

UNWIND ["Lois", "Peter", "Meg", "Chris", "Stewie", "Brian"] as name
CREATE (u:User {
    id: apoc.create.uuid(),
    email: toLower(name) + "@gmail.com",
    name: name,
    password:"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi"
});

UNWIND ["Lois", "Peter", "Meg", "Chris", "Stewie", "Brian"] as name
MATCH (u:User {name: name})
WITH u, [1, 2, 2, 2, 3, 3, 3, 3, 3, 3, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 5, 5, 5, 5] AS votes
UNWIND RANGE(0, 20) AS _useless
MATCH (b:Beer {id: toInteger(rand() * 100 + 1)})
CREATE (r:Review {rating: apoc.coll.randomItem(votes)})
CREATE (u)-[:WROTE]->(r)-[:ABOUT]->(b);

MATCH (u:User)
WITH u, [1, 2, 2, 2, 3, 3, 3, 3, 3, 3, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 5, 5, 5, 5] AS votes, [
    "Festie", "Humbucker Helles", "Grisette", "Abita Amber",
    "Larry Imperial IPA", "Maggie's Leap", "Horny Monk",
    "Rodeo Rye Pale Ale", "Monk's Blood", "Aviator Raspberry Blonde",
    "Point Special", "Noche Dulce", "Vanilla Porter", "Night Cat"
] AS common_beers
UNWIND common_beers AS beer_name
MATCH (b:Beer {name:  beer_name})
CREATE (r:Review {rating: apoc.coll.randomItem(votes)})
CREATE (u)-[:WROTE]->(r)-[:ABOUT]->(b);

MATCH (b:Beer) WITH b ORDER BY id(b)
MATCH (u:User) WHERE size((u)-[:WROTE]->(:Review)) > 2
WITH b, u OPTIONAL MATCH (u)-[:WROTE]->(review:Review)-[:ABOUT]->(b)
WITH b, u, CASE review IS NULL WHEN true THEN 0 ELSE review.rating END AS rating
ORDER BY ID(u), ID(b)
WITH {item: ID(u), weights: COLLECT(rating)} AS userData
WITH COLLECT(userData) AS data
CALL gds.alpha.similarity.pearson.stream({data: data, topK: 3, similarityCutoff: 0.3})
YIELD item1, item2, count1, count2, similarity
WITH gds.util.asNode(item1) AS user1, gds.util.asNode(item2) AS user2, similarity
MERGE (user1)-[r:SIMILAR_USER]->(user2) SET r.userSimilarity = similarity;


