<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB,Auth,Log;


class UserController extends Controller
{
    public function get_user_info($uid = 0){

        $user = array(
            'uid' => 0,
            'name' =>'guest'
        );

        if($uid == 0){

            $auth = Auth::user();

            if ($auth != null) {
                $user['uid']  = $auth->id;
                $user['name']  = $auth->name;
            }
        }else{
            $_uq = DB::table("users")
                ->select("id","name")
                ->where("users.id",$uid)
                ->get();
            if(count($_uq)){
                $user['uid']  = $_uq[0]->id;
                $user['name']  = $_uq[0]->name;
            }
        }

        return $user;
    }
}
