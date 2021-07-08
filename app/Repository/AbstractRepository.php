<?php

namespace App\Repository;

use Laudis\Neo4j\Contracts\ClientInterface;

abstract class AbstractRepository
{
    protected ClientInterface $client;

    /**
     * AbstractRepository constructor.
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }
}
