<?php

namespace App\Repository;

use App\Collection\ReviewCollection;
use App\Models\Review;
use Laudis\Neo4j\Types\CypherMap;

class ReviewRepository extends AbstractRepository implements ReviewRepositoryInterface
{

    public function getByUserId(string $userId): ReviewCollection
    {
        $query = <<<CYPHER
MATCH (u:User {id: \$userId})
MATCH (u)-[:WROTE]->(r:Review)-[:ABOUT]->(b:Beer)-[:STYLE]->(s:Style)
RETURN
    r.rating AS rating,
    b.name AS beerName,
    s.name AS beerStyle
ORDER BY rating DESC
CYPHER;
        $result = $this->client->run($query, [
            'userId' => $userId
        ]);

        return new ReviewCollection($result->map(function($reviewNode) {
            return $this->nodeToReview($reviewNode);
        }));
    }

    private function nodeToReview(CypherMap $reviewNode): Review
    {
        $review = new Review;
        $review->rating = $reviewNode->get('rating');
        $review->beerName = $reviewNode->get('beerName');
        $review->beerStyle = $reviewNode->get('beerStyle');

        return $review;
    }
}
