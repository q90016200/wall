
@extends('layouts.app')

@section('title', 'Page Title')


@section('content')

<link href="/open-iconic/font/css/open-iconic-bootstrap.css" rel="stylesheet">

    <div class="container">


		<div class="row">
			<div class="col bg-info">
			    <h2 >wall</h2>
		    </div>
		</div>

		<div class="row border p-2" >
            <div class="col ">
                <div class="row">
                    <div class="col-auto mr-auto">
                        <span class="oi oi-person"></span>
                        <span class="ml-1">@guest guest @else {{ Auth::user()->name }}  @endguest</span>
                    </div>
                    <div class="col-auto">
                        <a href="">
                            <span class="oi oi-image"></span>
                        </a>
                        <input type="file">
                    </div>    
                </div>
                
                <div class="row mt-2">
                    <div class="col">
                        <form>
                            <div class="form-group">
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="分享內容"></textarea>
                            </div>
                        </form>
                    </div>
                </div>

                
                {{-- 分享 --}}
                <div class="card mb-3">
                    <img class="card-img-top" src="..." alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                        <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                    </div>
                </div>

                {{-- 圖片 --}}
                <div class="card" >
                    <img class="card-img-top" src="..." alt="Card image cap">
                </div>


                {{-- 送出 --}}
                <div class="row justify-content-end mt-2">
                    <button type="button" class="btn btn-info">送出</button>
                </div>

            </div>
			
		</div>


    	
    </div>

@endsection

@section('html_bottom')
<script>
    window.onload = function(){
        autosize(document.querySelectorAll('textarea'));
    }
</script>
@endsection

