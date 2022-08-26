<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Support\Facades\Request;

class GenreController extends Controller
{
    public function index()
    {
        $perPage = Request::input('perPage') ?: 5;

        return Inertia::render('Genres/Index', [
            'genres' => Genre::query()
                            ->when(Request::input('search'), function($query, $search) {
                                $query->where('title', 'like', "%{$search}%");
                            })
                            ->paginate($perPage)
                            ->withQueryString(),
            'filters' => Request::only(['search', 'perPage'])
        ]);
    }
}
