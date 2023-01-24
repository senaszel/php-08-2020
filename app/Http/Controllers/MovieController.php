<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use External\Bar\Movies\MovieService as BarMovieService;
use External\Baz\Movies\MovieService as BazMovieService;
use External\Foo\Movies\MovieService as FooMovieService;

class MovieController extends Controller
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getTitles(Request $request): JsonResponse
    {
        // TODO
        $services = [
            new BarMovieService(),
            new BazMovieService(),
            new FooMovieService()
        ];

        $titles = [];
        foreach ($services as $movieService) {
            $titles[] = array_merge($titles, $movieService->getTitles());
            // I run out of time but I would use transforms and or maps to standardize resulting collection of titles.
            // That should be also error proofed and cached.  
        }

        return response()->json([$titles]);
    }
}
