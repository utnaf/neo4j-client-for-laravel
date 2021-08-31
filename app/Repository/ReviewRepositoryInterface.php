<?php

namespace App\Repository;

use App\Collection\ReviewCollection;

interface ReviewRepositoryInterface
{
    public function getByUserId(string $userId): ReviewCollection;

    public function createReview(int $rating, string $userId, int $beerId): void;
}
