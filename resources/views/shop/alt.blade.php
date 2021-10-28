@extends('layouts.template')

@section('title', 'Shop -alternative listing')

@section('main')
    <h1>Shop -alternative listing</h1>
    @foreach($genres as $genre)
        <h2>{{$genre->name}}</h2>
        <ul>
        @foreach($genre->records as $record)
            <li>{{$record->artist}}</li>
        @endforeach
        </ul>
    @endforeach
@endsection
