<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\TvShow;
use Illuminate\Support\Facades\Request;

class TvShowController extends Controller
{
    public function index()
    {
        $perPage = Request::input('perPage') ?: 5;

        return Inertia::render('TvShows/Index', [
            'tvShows' => TvShow::query()
                            ->when(Request::input('search'), function($query, $search) {
                                $query->where('name', 'like', "%{$search}%");
                            })
                            ->paginate($perPage)
                            ->withQueryString(),
            'filters' => Request::only(['search', 'perPage'])
        ]);
    }
}
