
@extends('layouts.master')

@section('title', 'wall')
{{-- @section('container-width', 'w-50') --}}


@section('content')

    <style>
    button.close{
        right: 5px;
    }
    </style>

    <link href="/open-iconic/font/css/open-iconic-bootstrap.css" rel="stylesheet">

    <div class="bg-info p-3 my-3 rounded ">
        <a href="/">
            <h2>wall</h2>
        </a>
    </div>

    <div id="spa">
        
    </div>

    <div id="content">
        
    </div>

    
        

@endsection

@section('html_bottom')
    <script>
        var user = {!! json_encode($data['user']) !!}
    </script>
    <script src="{{ asset('js/wall_index.js') }}" defer></script>
@endsection

