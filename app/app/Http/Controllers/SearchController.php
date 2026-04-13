<?php

namespace App\Http\Controllers;

use App\Http\Resources\MealItemResource;
use App\MealDb\MealDbRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController
{
    public function __invoke(Request $request, MealDbRepository $repository): View
    {
        $search = trim($request->get('q', ''));

        $results = $repository->search($search);

        return view('search', [
            'searchQuery' => $search,
            'results' => MealItemResource::collection($results, $request->user()),
        ]);
    }
}
