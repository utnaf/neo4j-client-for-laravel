<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repository\RecommendationRepositoryInterface;
use App\Repository\ReviewRepositoryInterface;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    private ReviewRepositoryInterface $reviewRepository;
    private RecommendationRepositoryInterface $recommendationRepository;

    public function __construct(
        ReviewRepositoryInterface $reviewRepository,
        RecommendationRepositoryInterface $recommendationRepository)
    {
        $this->reviewRepository = $reviewRepository;
        $this->recommendationRepository = $recommendationRepository;
    }

    public function index(Request $request) {
        assert($request->user() instanceof User, "User must be logged in.");

        $reviewCount = $this->reviewRepository->getByUserId($request->user()->id)->count();
        $recommendations = null;
        if ($reviewCount === 0) {
            $recommendations = $this->recommendationRepository->coldStartRecommendation();
        }
        else {
            $recommendations = $this->recommendationRepository->getRecommendedBeers($request->user());
        }

        return view('dashboard', [
            'recommendedBeers' => $recommendations
        ]);
    }
}
