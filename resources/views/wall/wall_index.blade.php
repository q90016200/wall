
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

    <div class="my-3 p-3 bg-white rounded box-shadow" id="">
        <div class="d-flex justify-content-between bd-highlight mb-3">
            <div>
                <span class="oi oi-person"></span>
                <span class="ml-1">@guest guest @else {{ Auth::user()->name }}  @endguest</span>    
            </div>

            <div></div>
            
            <div class="">
                <a href="" >
                    <span class="oi oi-image"></span>
                </a>
                <input type="file">
            </div>
            
        </div>    
       
        <form>
            <div class="form-group">
                <textarea class="form-control" id="" rows="3" placeholder="分享內容"></textarea>
            </div>
        </form>
        
        {{-- 分享 --}}
        <div class="card mt-3">
            <img class="card-img-top" src="..." alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title">Card title</h5>
                <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
            </div>
        </div>

        {{-- 圖片 --}}
        <div class="card mt-3" >
            <img class="card-img-top" src="..." alt="Card image cap">
        </div>


        {{-- 送出 --}}
        <div class="text-right mt-3">
            <button type="button" class="btn btn-info">送出</button>
        </div>

        
    </div>
    
    

        

@endsection

@section('html_bottom')
    <script src="{{ asset('js/wall_index.js') }}" defer></script>
@endsection

