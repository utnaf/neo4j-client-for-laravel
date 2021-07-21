## Notes

https://www.kaggle.com/rdoume/beerreviews

```
CREATE (r:Review {
    appearance: 3,
    taste: 4,
    aroma: 5,
    palate: 4,
    overall: 4
})
WITH r
MATCH (u:User {email: "fantuzzidavide@gmail.com"})
WITH u, r
CREATE (u)-[:WROTE]->(r)
WITH u, r
MATCH (b:Beer { name: "Allagash Dubbel Ale"})
WITH r, b
CREATE (r)-[:ABOUT]->(b)
```

```
MATCH (u:User {email: "fantuzzidavide@gmail.com"})
RETURN u
```

```
MATCH (u:User)-[:WROTE]-(r:Review)-[:ABOUT]->(b:Beer)
WHERE
    round(r.appearance, 0) = 3 AND
    round(r.taste, 0) = 4 AND
    round(r.overall, 0) = 4 AND
    round(r.palate, 0) = 4 AND
    round(r.aroma, 0) = 5
RETURN COUNT(DISTINCT b)
```

```
CALL gds.graph.create(
    'reviewSimilarityGraph',
    {
        Review: {
            label: 'Review',
            properties: ['appearance', 'aroma', 'palate', 'taste']
        }
    },
    '*'
);
```
