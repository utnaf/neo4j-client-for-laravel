<?php

namespace App\Repository;

use App\Collection\BeerCollection;
use App\Models\Beer;
use App\Models\User;

interface BeerRepositoryInterface
{

    public function getAll(int $skip, int $limit): BeerCollection;

    public function getById(): Beer;

    public function search(): BeerCollection;

}
