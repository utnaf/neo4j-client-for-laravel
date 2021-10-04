<?php

namespace App\Repository;

use App\Collection\RecommendationCollection;
use App\Models\User;

interface RecommendationRepositoryInterface
{

    public function coldStartRecommendation(): RecommendationCollection;

    public function getRecommendedBeers(User $user): RecommendationCollection;

    public function updateSimilarUsers(User $user);

}
