<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\WallComment;

class WallCommentController extends Controller
{
    public function store(Request $request){
    	// return $request->input();

    	$data["error"] = true;

    	$user = app(UserController::class)->get_user_info();
        $uid = $user["uid"];

        if($uid != 0 ){

        	$WallComment = new WallComment();
	    	$WallComment->comment_post_id = $request->post_id;
	    	$WallComment->comment_content = $request->content;
	    	$WallComment->comment_author = $uid;
	    	$WallComment->comment_status = "publish";
	    	$WallComment->save();

	    	$data["comments"] = $this->getCommentById($WallComment->comment_id);

	    	$data["error"] = false;
        }

        // dd($data);

    	return response()->json($data);


    }

    public function destroy($comment_id){

    	$data["error"] = true;

    	$user = app(UserController::class)->get_user_info();
        $uid = $user["uid"];

        // 先判斷是否有權限
    	$comment = \App\Models\WallComment::where("comment_status","publish")
        		->where("comment_id",$comment_id)
        		->where("comment_author",$uid)
        		->get();

       	if(count($comment) > 0){
       		\App\Models\WallComment::where("comment_status","publish")
        		->where("comment_id",$comment_id)
        		->where("comment_author",$uid)->delete();
       		$data["error"] = false;
       	}
        		
        return response()->json($data);
    }



    public function latest($post_id,$limit = 0,$skip = 0){

    	$data = array();

        $data["total"] = 0 ;
        $data["comments"] = array();

        if($post_id){
        	$comments = \App\Models\WallComment::where("comment_status","publish")
        		->where("comment_post_id",$post_id);

        	$data["total"] = $comments->count();

        	if($limit != 0){
        		$comments = $comments->skip($skip)->take($limit);
        	}

        	$comments = $comments->orderBy('created_at','desc')
        		->get();

        	if(count($comments) > 0){

        		$cd = array();


        		foreach ($comments as $k => $v) {
        			$cd[$k] = $this->export_comment($v);
        		}

        		$cd = array_reverse($cd);

        		$data["comments"] = $cd;

        	}
        }

    	
        return $data;

    }

    private function export_comment($v){
    	$data["comment_id"] = $v->comment_id;
    	$data["content"] = $v->comment_content;
    	$data["created_at"] = $v->created_at;
    	$data["comment_status"] = $v->comment_status;


    	$user = app(UserController::class)->get_user_info();

    	$data["is_edit"] = 0;
    	if($user["uid"] == $v->comment_author){
    		$data["is_edit"] = 1;
    	}


    	$data["user"] = $user;


    	return $data;
    }

    private function getCommentById($commnet_id){

    	$data = array();

    	if($commnet_id){
    		$comment = \App\Models\WallComment::where('comment_id', '=', $commnet_id)
    			->get();

            array_push($data, $this->export_comment($comment[0]));


    	}
    	
    	return $data;

    }


}
