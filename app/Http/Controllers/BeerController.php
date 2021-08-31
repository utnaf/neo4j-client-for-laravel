<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repository\BeerRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BeerController extends Controller
{
    public const PER_PAGE = 9;

    private BeerRepositoryInterface $beerRepository;

    /**
     * BeerController constructor.
     * @param BeerRepositoryInterface $beerRepository
     */
    public function __construct(BeerRepositoryInterface $beerRepository)
    {
        $this->beerRepository = $beerRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(Request $request)
    {
        assert($request->user() instanceof User, "User must be logged in.");
        $beers = $this->beerRepository->getAll(
            self::PER_PAGE * $request->get('page', 0),
            self::PER_PAGE,
            $request->user()->id
        );

        return view("beer.index", [
            'beers' => $beers
        ]);
    }
}
