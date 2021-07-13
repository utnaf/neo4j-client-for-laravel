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
MATCH (r)-[:ABOUT]->(b)
RETURN
    b.id AS id,
    b.name AS name,
    s.name AS style,
    br.name AS brewery,
    count(r) AS review_count,
    avg(r.overall) AS overall_score_avg,
    avg(r.appearance) AS appearance_score_avg,
    avg(r.aroma) AS aroma_score_avg,
    avg(r.palate) AS palate_score_avg,
    avg(r.taste) AS taste_score_avg
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
        $stats = new BeerStats;

        $stats->overall = round($beerNode->get("overall_score_avg"), 2);
        $stats->taste = round($beerNode->get("taste_score_avg"), 2);
        $stats->palate = round($beerNode->get("palate_score_avg"), 2);
        $stats->aroma = round($beerNode->get("aroma_score_avg"), 2);
        $stats->appearance = round($beerNode->get("appearance_score_avg"), 2);
        $beer->stats = $stats;

        return $beer;
    }
}
