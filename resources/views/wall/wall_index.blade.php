
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
        <h2 >wall</h2>
    </div>

    <div class="my-3 p-3 bg-white rounded border-bottom" id="wall_publish">

    </div>

    <div class="my-3 p-3 bg-white rounded border-bottom" id="wall_posts">
        {{-- <div class="row">
            <div class="col">123</div>
            <div class="col">123</div>
            <div class="col">123</div>
            <div class="col">123</div>
        </div> --}}
        
        <div>
            <div class="row ">
                <div class="col-auto">
                    <img src="{{ asset('img/avatar.jpg') }}" alt="..." class="rounded-circle" style="width: 40px; height: 40px;">
                </div>

                <div class="col-auto mr-auto">
                    <div>name</div>
                    <div>time</div>
                </div>
                
                <div class="col-auto">
                    <button class="btn bg-white">...</button>
                </div>
            </div>
            <div class="my-3">
                content here
            </div>
        </div>
        

    </div>
        

@endsection

@section('html_bottom')
    <script>
        var user = {!! json_encode($data['user']) !!}
    </script>
    <script src="{{ asset('js/wall_index.js') }}" defer></script>
@endsection

