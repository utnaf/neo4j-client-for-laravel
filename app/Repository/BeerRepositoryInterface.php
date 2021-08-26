<?php

namespace App\Repository;

use App\Collection\BeerCollection;

interface BeerRepositoryInterface
{
    public function getAll(int $skip, int $limit): BeerCollection;
}
