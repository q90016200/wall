<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Auth;
use Log;
use Validator;


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
            if(count($_uq) > 0){
                $user['uid']  = $_uq[0]->id;
                $user['name']  = $_uq[0]->name;
            }
        }

        return $user;
    }

    /**
     * 註冊頁
     */
    public function registerPage(){
        $view = view('aaa');
        return $view;
    }

    /**
     * 註冊
     */
    public function register(Request $request){
        $validator = $request->validate([
            'type' => 'required',
        ]);
        
        if($validator->fails()){
            
        }

        switch ($request->input("type")) {
            case 'fb':
                # code...
                break;
            
            case 'google':
                break;
            
            default:
                return $this->siteRegister($request->all());
                break;
        }

    }

    /**
     * 站內註冊
     */
    private function siteRegister($data){

        // $message = [
        //     'required' => ':attribute 必須欄位',
        //     'email' => ':attribute 格式不符合',
        //     ''
        // ];

        $validator = Validator::make($data,[
            'email' => 'required|email|max:255',
            'password' => 'required|max:40',
            'name' => 'required|max:255|string'
        ]);
        
        if($validator->fails()){
            $errors = $validator->errors();

            dd($errors);

            return response()->json([
                'error' => true,
                'message' => ''
            ]);
        }


        $password = encrypt($data['password']);


        return response()->json([
            'error' => false,
            'message' => 'register success'
        ]);

    }


    /**
     * 登入頁
     */
    public function loginPage(){
        $view = view('auth.login');
        return $view;
    }

    /**
     * 登入
     */
    public function login(Request $request){
        $validator = $request->validate([
            'type' => 'required',
        ]);
        
        if($validator->fails()){
            
        }

        $view = view('auth.register');
        return $view;
    }
    
    /**
     * 站內登入
     */
    public function siteLogin($data){

        $data['password'] = encrypt($data['password']);

        $validator = Validator::make($data,[
            'email' => 'required|max:255|email|exists:connection.users',
            'password' => 'required|max:40|exists:connection.users'
        ]);

        if($validator->fails()){
            
        }

        # 驗證用戶
        $user = DB::table('users')
            ->where('email',$data['email'])
            ->where('password',$data['password'])
            ->first();

        if(count($user) > 0){
            # 記住用戶
            if($data['remember'] == true){
                Auth::login($user,true);
            } else {
                Auth::login($user);
            }

        }
        

        return response()->json([
            'error' => false,
            'message' => 'login success'
        ]);

    }


}
