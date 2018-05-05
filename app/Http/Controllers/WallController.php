<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WallController extends Controller
{
    public function index(){

        $view = view("wall.index");


        return $view;
    }
}
