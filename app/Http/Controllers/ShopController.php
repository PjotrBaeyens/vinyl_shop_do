<?php

namespace App\Http\Controllers;

use App\Genre;
use App\Helpers\Json;
use App\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShopController extends Controller
{
    // Master Page: http://vinyl_shop.test/shop or http://localhost:3000/shop
    public function index(Request $request)
    {   $genre_id = $request->input('genre_id') ?? '%'; // $request->input('genre_id') OR $request->genre_id OR $request['genre_id'];;
        $artist_title = '%' . $request->input('artist') . '%'; // $request->input('artist') OR $request->artist OR $request['artist'];;
        $records = Record::with('genre')
            ->orderBy('artist')
            ->where([
                ['artist','like', $artist_title],
                ['genre_id', 'like', $genre_id]
            ])
            ->orwhere([
                ['title', 'like', $artist_title],
                ['genre_id', 'like', $genre_id]])
            ->paginate(12);
            //->appends(['artist'=> $request->input('artist'), 'genre_id' => $request->input('genre_id')]);
        foreach ($records as $record) {
            $record->badge = ($record->stock == 0) ? 'badge-danger': 'badge-success';
            $record->price = number_format($record->price,2);
            $record->cover = $record->cover ?? "https://coverartarchive.org/release/{$record->title_mbid}/front-250.jpg";
        }
        $genres = Genre::orderBy('name')
        ->has('records')
        ->withCount('records')
        ->get()
        ->transform(function ($item, $key) {
            $item->name = ucfirst($item->name) . ' (' . $item->records_count . ')';
            return $item;
        })
        ->makeHidden(['created_at', 'updated_at', 'records_count']);
        $result = compact('genres', 'records');
        \Facades\App\Helpers\Json::dump($result);                                        // open http://vinyl_shop.test/shop?json
        return view('shop.index', $result);         // add $result as second parameter
    }



    // Detail Page: http://vinyl_shop.test/shop/{id} or http://localhost:3000/shop/{id}
    public function show($id)
    {
        $record = Record::with('genre')->findOrFail($id);
        // dd($record);
// Real path to cover image
        $record->cover = $record->cover ?? "https://coverartarchive.org/release/$record->title_mbid/front-500.jpg";
// Combine artist + title
        $record->title = $record->artist . ' - ' . $record->title;
// Links to MusicBrainz API
// https://wiki.musicbrainz.org/Development/JSON_Web_Service
        $record->recordUrl = 'https://musicbrainz.org/ws/2/release/' . $record->title_mbid . '?inc=recordings+url-rels&fmt=json';
// If stock > 0: button is green, otherwise the button is red and disabled
        $record->btnClass = $record->stock > 0 ? 'btn-outline-success' : 'btn-outline-danger disabled';
// You can't overwrite the attribute genre (object) with a string, so we make a new attribute
        $record->genreName = $record->genre->name;
// Use the PHP function number_format() to show 2 decimal digits of the price
        $record->price = number_format($record->price,2);
// Hide attributes you don't need for the view
        $record->makeHidden(['genre', 'artist', 'genre_id', 'created_at', 'updated_at', 'title_mbid', 'genre']);

// get record info and convert it to json
        $response = Http::get($record->recordUrl)->json();
        $tracks = $response['media'][0]['tracks'];
        $tracks = collect($tracks)
            ->transform(function ($item, $key) {
                $item['length'] = date('i:s', $item['length'] / 1000);
                unset($item['id'], $item['recording'], $item['number']);
                return $item;
            });
        $result = compact('tracks','record');
        \Facades\App\Helpers\Json::dump($result);
        return view('shop.show',$result);
    }
    public function alt()
    {
        $genres = Genre::with('records')->get();

        $result = compact('genres');
        \Facades\App\Helpers\Json::dump($result);
        return view('shop.alt', $result);
    }
}

