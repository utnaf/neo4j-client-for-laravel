CREATE CONSTRAINT user_id_unique IF NOT EXISTS ON (u:User) ASSERT u.id IS UNIQUE;
CREATE CONSTRAINT user_email_unique IF NOT EXISTS ON (u:User) ASSERT u.email IS UNIQUE;
CREATE CONSTRAINT brewery_id_unique IF NOT EXISTS ON (b:Brewery) ASSERT b.id IS UNIQUE;
CREATE CONSTRAINT beer_id_unique IF NOT EXISTS ON (b:Beer) ASSERT b.id IS UNIQUE;
CREATE CONSTRAINT style_unique IF NOT EXISTS ON (s:Style) ASSERT s.name IS UNIQUE;

// import beers
// password for the created user is "password"
CALL apoc.periodic.iterate(
'CALL apoc.load.csv("file:///beer_reviews.csv") YIELD map RETURN map',
'MERGE (br:Brewery { id: map.brewery_id}) SET br.name = map.brewery_name
MERGE (be:Beer { id: map.beer_beerid }) SET be.name = map.beer_name
MERGE (s:Style { name: map.beer_style })
MERGE (be)-[:BREWED_BY]->(br)
MERGE (be)-[:STYLE]->(s)
MERGE (u:User {
    email: map.review_profilename + "@gmail.com",
    name: map.review_profilename,
    password: "$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi"
})
CREATE (r:Review {
    palate: map.review_palate,
    appearance: map.review_appearance,
    aroma: map.review_aroma,
    overall: map.review_overall,
    taste: map.review_taste,
    created_at: map.review_time
})
MERGE (u)-[:WROTE]->(r)
MERGE (r)-[:ABOUT]->(be);
', {batchSize:10000, iterateList:true});

// set the id of the newly created users
MATCH (u:User)
WHERE NOT exists(u.id)
SET u.id = apoc.create.uuid();
