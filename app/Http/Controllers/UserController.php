<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB,Auth;

class UserController extends Controller
{
    public function get_user_info($uid = 0){

        $user = array(
            'uid' => 0,
            'name'=>'guest'
        );

        if($uid == 0){
            if (Auth::check()) {
                $auth = Auth::user();
                $user['uid']  = $auth->id;
                $user['name']  = $auth->name;
            }
        }else{
            $_uq = DB::table("users")
                ->select("id","name")
                ->where("users.id",$uid)
                ->get();
            if($_uq){
                $user['uid']  = $_uq[0]->id;
                $user['name']  = $_uq[0]->name;
            }
        }

        return $user;
    }
}
