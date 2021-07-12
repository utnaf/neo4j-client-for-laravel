<?php

namespace App\Repository;

use App\Collection\BeerCollection;
use App\Models\Beer;
use Laudis\Neo4j\Types\CypherMap;

class BeerRepository extends AbstractRepository implements BeerRepositoryInterface
{

    public function getAll(int $skip, int $limit): BeerCollection
    {
        $query = <<<CYPHER
MATCH (b:Beer)-[:STYLE]->(s:Style)
MATCH (b)-[:BREWED_BY]->(br:Brewery)
MATCH (r)-[:ABOUT]->(b)
RETURN b.id AS id, b.name AS name, s.name AS style, br.name AS brewery, count(r) AS review_count
ORDER BY id ASC
SKIP \$skip LIMIT \$limit
CYPHER;
        $result = $this->client->run($query, [
            'skip' => $skip,
            'limit' => $limit
        ]);

        return new BeerCollection($result->map(function($beerNode) {
            return $this->nodeToBeer($beerNode);
        }));
    }

    public function getById(): Beer
    {
        // TODO: Implement getById() method.
    }

    public function search(): BeerCollection
    {
        // TODO: Implement search() method.
    }

    private function nodeToBeer(CypherMap $beerNode): Beer
    {
        $beer = new Beer;
        $beer->id = $beerNode->get("id");
        $beer->name = $beerNode->get("name");
        $beer->style = $beerNode->get("style");
        $beer->brewery = $beerNode->get("brewery");
        $beer->review_count = $beerNode->get("review_count");

        return $beer;
    }
}
