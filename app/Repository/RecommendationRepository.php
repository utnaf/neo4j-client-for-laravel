<?php

namespace App\Repository;

use App\Collection\RecommendationCollection;
use App\Models\Recommendation;
use App\Models\User;
use Laudis\Neo4j\Databags\Statement;
use Laudis\Neo4j\Types\CypherMap;

class RecommendationRepository extends AbstractRepository implements RecommendationRepositoryInterface
{
    private const RECOMMENDATION_LIMIT = 4;

    public function coldStartRecommendation(): RecommendationCollection
    {
        $query = <<<CYPHER
MATCH (b:Beer)<-[:ABOUT]-(r:Review)
WITH b AS beer, AVG(r.rating) AS average, STDEV(r.rating) AS stddev, COUNT(r) AS reviewCount
WHERE stddev < 0.2 AND average > 3
RETURN beer.name AS beerName, beer.id AS beerId, b.style AS style, ['coldStart'] AS reason
ORDER BY reviewCount DESC, average DESC, stddev DESC
LIMIT \$recommendationLimit
CYPHER;

        $result = $this->client->run($query, [
            'recommendationLimit' => self::RECOMMENDATION_LIMIT
        ]);

        return new RecommendationCollection($result->map(function($reccomendationNode) {
            return $this->nodeToRecommendation($reccomendationNode);
        }));
    }

    public function getRecommendedBeers(User $user): RecommendationCollection
    {
        $query = <<<CYPHER
MATCH (u:User {id: \$userId})<-[r:RECCOMENDED_TO]-(beer:Beer)
WHERE NOT((u)-[:WROTE]->(:Review)-[:ABOUT]->(beer))
RETURN beer.name AS beerName, beer.id AS beerId, beer.style AS style, r.reason AS reason
ORDER BY r.created_at DESC
LIMIT \$recommendationLimit
CYPHER;

        $result = $this->client->run($query, [
            'userId' => $user->id,
            'recommendationLimit' => self::RECOMMENDATION_LIMIT
        ]);

        return new RecommendationCollection($result->map(function($reccomendationNode) {
            return $this->nodeToRecommendation($reccomendationNode);
        }));
    }

    public function updateSimilarUsers(User $user): void
    {
        $similarUserQuery = <<<CYPHER
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
MERGE (user1)-[r:SIMILAR_USER]->(user2) SET r.userSimilarity = similarity
CYPHER;

        $updateRecommendedBeersQuery = <<<CYPHER
MATCH (u:User {id: \$userId})-[:SIMILAR_USER]->(other:User)
MATCH (other)-[:WROTE]->(:Review)-[:ABOUT]->(b:Beer)
WHERE NOT((u)-[:WROTE]->(:Review)-[:ABOUT]->(b))
WITH b, COUNT(b) AS beers
WITH b ORDER BY beers DESC LIMIT 3
MATCH (u:User {id: \$userId})
CREATE (b)-[r:RECCOMENDED_TO]->(u)
SET r.created_at = timestamp(), r.reason=['userSimilarity']
RETURN b
CYPHER;

        $this->client->runStatements([
            Statement::create($similarUserQuery),
            Statement::create($updateRecommendedBeersQuery, ['userId' => $user->id])
        ]);
    }

    private function nodeToRecommendation(CypherMap $recommendationNode): Recommendation
    {
        $recommendation = new Recommendation();
        $recommendation->beerId = $recommendationNode->get('beerId');
        $recommendation->style = $recommendationNode->get('style');
        $recommendation->beerName = $recommendationNode->get('beerName');
        $recommendation->reason = $recommendationNode->get('reason')->toArray();

        return $recommendation;
    }
}
