<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\TvShow;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

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

    public function store()
    {
        $tvShow = TvShow::where('tmdb_id', Request::input('tvShowTMDBId'))->first();

        if($tvShow) {
            return Redirect::back()->with('flash.banner', 'Tv Show Exists.');
        }

        $tmdb_tv = Http::get(config('services.tmdb.endpoint') . 'tv/' . Request::input('tvShowTMDBId') . '?api_key=' . config('services.tmdb.secret') . '&language=en-US');

        if ($tmdb_tv->successful()) {
            TvShow::create([
                'tmdb_id' => $tmdb_tv['id'],
                'name' => $tmdb_tv['name'],
                'poster_path' => $tmdb_tv['poster_path'],
                'created_year' => $tmdb_tv['first_air_date']
            ]);
            return Redirect::back()->with('flash.banner', 'Tv Show Created.');
        } else {
            return Redirect::back()->with('flash.banner', 'Api Error.');
        }
    }

    public function edit(TvShow $tvShow)
    {
        return Inertia::render('TvShows/Edit', ['tvShow' => $tvShow]);
    }

    public function update(TvShow $tvShow)
    {
        $validated = Request::validate([
            'name' => 'required',
            'poster_path' => 'required'
        ]);

        $tvShow->update($validated);

        return Redirect::route('admin.tv-shows.index')->with('flash.banner', 'Tv Show Updated.');
    }

    public function destroy(TvShow $tvShow)
    {
        $tvShow->delete();

        return Redirect::route('admin.tv-shows.index')->with('flash.banner', 'Tv Show Deleted.')->with('flash.bannerStyle', 'danger');
    }
}
