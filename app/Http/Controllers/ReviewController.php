<?php

namespace App\Http\Controllers;

use App\Events\ReviewAddedEvent;
use App\Models\User;
use App\Repository\ReviewRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ReviewController extends Controller
{
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

    /**
     * @param Request $request
     * @return  RedirectResponse
     */
    public function store(Request $request):  RedirectResponse
    {
        assert($request->user() instanceof User, "User must be logged in.");
        assert(
            $request->has('rating') && $request->has('beer_id'),
            'rating and beer_id fields are mandatory.'
        );
        $this->reviewRepository->createReview(
            $request->get('rating', 1),
            $request->user()->id,
            (int) $request->get('beer_id')
        );

        ReviewAddedEvent::dispatch($request->user());

        return Redirect::route('dashboard.index');
    }
}
