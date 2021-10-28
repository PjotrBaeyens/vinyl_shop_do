<?php

namespace App\Http\Controllers;

use App\Genre;
use App\Helpers\Json;
use App\Record;
use Illuminate\Http\Request;

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
        return view('shop.show', ['id' => $id]);
    }
    public function alt()
    {
        $genres = Genre::with('records')->get();

        $result = compact('genres');
        \Facades\App\Helpers\Json::dump($result);
        return view('shop.alt', $result);
    }
}

