<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();

Route::get('register','UserController@registerPage');
Route::post('register','UserController@register');
Route::get('login','UserController@loginPage');
Route::post('login','UserController@login');


Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', 'WallController@index');

Route::post('/wall/get_preview', 'WallController@get_url_preview');

# 貼文
Route::resource('/wall/posts', 'WallPostController',['only' => ['create','store','show','edit','destroy']]);

Route::get("/wall/posts","WallPostController@latest");


# 留言 (新增、刪除)
Route::resource('/wall/posts/{post_id}/comments', 'WallCommentController',['only' => ['store','destroy']]);
# 抓取 post 內 留言
Route::get('/wall/posts/{post_id}/comments', 'WallCommentController@getCommentByPostId');

