<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repository\ReviewRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public const PER_PAGE = 9;

    private ReviewRepositoryInterface $reviewRepository;

    /**
     * BeerController constructor.
     * @param ReviewRepositoryInterface $reviewRepository
     */
    public function __construct(ReviewRepositoryInterface $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(Request $request)
    {
        assert($request->user() instanceof User, "User must be logged in.");
        $reviews = $this->reviewRepository->getByUserId($request->user()->id);

        return view("review.index", [
            'reviews' => $reviews
        ]);
    }
}
