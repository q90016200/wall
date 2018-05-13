
@extends('layouts.master')

@section('title', 'Page Title')


@section('content')

    <style>
    button.close{
        right: 5px;
    }
    </style>

    <link href="/open-iconic/font/css/open-iconic-bootstrap.css" rel="stylesheet">

    <div class="bg-info p-3 my-3 rounded box-shadow">
    <h2 >wall</h2>
    </div>

    <div class="my-3 p-3 bg-white rounded box-shadow" id="wall_publish_div">

    </div>
        

@endsection

@section('html_bottom')
    <script src="{{ asset('js/wall_index.js') }}" defer></script>
@endsection

