<?php

namespace App\Listeners;

use App\Events\ReviewAddedEvent;
use App\Repository\RecommendationRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Log\Logger;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UpdateSimilarUsers
{
    private RecommendationRepositoryInterface $recommendationRepository;

    public function __construct(RecommendationRepositoryInterface $recommendationRepository)
    {
        $this->recommendationRepository = $recommendationRepository;
    }

    public function handle(ReviewAddedEvent $event)
    {
        $this->recommendationRepository->updateSimilarUsers($event->getUser());
    }
}
