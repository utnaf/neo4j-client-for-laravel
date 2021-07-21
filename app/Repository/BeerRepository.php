<?php

namespace App\Repository;

use App\Collection\BeerCollection;
use App\Models\Beer;
use App\Models\BeerStats;
use Illuminate\Database\Eloquent\Model;
use Laudis\Neo4j\Types\CypherMap;

class BeerRepository extends AbstractRepository implements BeerRepositoryInterface
{

    public function getAll(int $skip, int $limit): BeerCollection
    {
        $query = <<<CYPHER
MATCH (b:Beer)-[:STYLE]->(s:Style)
MATCH (b)-[:BREWED_BY]->(br:Brewery)
MATCH (br)-[:FROM]->(c:City)
MATCH (c)-[:IN]->(st:State)
MATCH (r:Review)-[:ABOUT]->(b)
RETURN
    b.id AS id,
    b.name AS name,
    b.abv AS abv,
    b.ibu AS ibu,
    s.name AS style,
    br.name AS brewery,
    c.name AS city,
    TRIM(st.name) AS state,
    count(r) AS review_count,
    avg(r.rating) AS overall_rating
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
        $beer->ibu = $beerNode->get("ibu");
        $beer->abv = $beerNode->get("abv");
        $beer->brewery = $beerNode->get("brewery");
        $beer->brewery_city = $beerNode->get("city");
        $beer->brewery_state = $beerNode->get("state");
        $beer->review_count = $beerNode->get("review_count");
        $beer->rating = $beerNode->get("overall_rating");

        return $beer;
    }
}
