<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WallComment;

class WallCommentController extends Controller
{
    public function store(Request $request){
    	return $request->input();
    }
}
