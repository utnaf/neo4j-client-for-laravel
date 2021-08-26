<?php

namespace App\Repository;

use App\Collection\BeerCollection;
use App\Models\Beer;
use App\Models\Brewery;
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
OPTIONAL MATCH (r:Review)-[:ABOUT]->(b)
RETURN
    b.id AS id,
    b.name AS name,
    b.abv AS abv,
    b.ibu AS ibu,
    s.name AS style,
    br.name AS brewery,
    c.name AS city,
    st.name AS state,
    count(r) AS review_count,
    avg(r.rating) AS overall_rating
ORDER BY review_count DESC, id ASC
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

    private function nodeToBeer(CypherMap $beerNode): Beer
    {
        $beer = new Beer;
        $beer->id = $beerNode->get("id");
        $beer->name = $beerNode->get("name");
        $beer->style = $beerNode->get("style");
        $beer->ibu = $beerNode->get("ibu");
        $beer->abv = $beerNode->get("abv");
        $beer->review_count = $beerNode->get("review_count");
        $beer->rating = $beerNode->get("overall_rating");

        $brewery = new Brewery();
        $brewery->name = $beerNode->get("brewery");
        $brewery->city = $beerNode->get("city");
        $brewery->state = $beerNode->get("state");

        $beer->brewery = $brewery;

        return $beer;
    }
}
