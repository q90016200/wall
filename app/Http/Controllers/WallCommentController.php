<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\WallComment;

use Notification;
use App\Notifications\Comment;

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
	    	$WallComment->save();

	    	$data["comments"] = $this->getCommentById($WallComment->comment_id);

	    	# 更新 post count
			$this->updatePostCommentCount($request->post_id);
			
			# 發送通知
			// $u = \App\User::where("id",$uid)->first();

			// $notif_data = array(
				
			// );

			// Notification::send($u, new Comment($notif_data));

			

	    	$data["error"] = false;
        }

        // dd($data);

    	return response()->json($data);

    }

    public function destroy($post_id,$comment_id){

    	$data["error"] = true;

    	$user = app(UserController::class)->get_user_info();
        $uid = $user["uid"];

        // 先判斷是否有權限
    	$comment = WallComment::where("comment_id",$comment_id)
        		->where("comment_author",$uid)
        		->get();

       	if(count($comment) > 0){
       		WallComment::where("comment_id",$comment_id)
        		->where("comment_author",$uid)->delete();

        	// 更新post count
	    	$this->updatePostCommentCount($post_id);


       		$data["error"] = false;
       	}
        		
        return response()->json($data);
    }


    // 更新 post 內的 comment數
    public function updatePostCommentCount($post_id = 0){

        if($post_id == 0){
            return false;
        }

        $comment_count = WallComment::where("comment_post_id",$post_id)
                ->where("comment_post_id",$post_id)
                ->count(); 


        $ud = array();
        $ud["post_comment_count"] = $comment_count;

        $_pcq = DB::table("wall_posts")
            ->where("wall_posts.post_id",$post_id)
            ->update($ud);


    }

    // 依照 貼文 id 來查詢出所有 comment
    public function getCommentByPostId($post_id,$limit = 0,$skip = 0){

    	$data = array();

        $data["total"] = 0 ;
        $data["comments"] = array();

        if($post_id){
        	$comments = WallComment::where("comment_post_id",$post_id);

        	$data["total"] = $comments->count();

        	if($limit != 0){
        		$comments = $comments->skip($skip)->take($limit);
        	}

        	$comments = $comments->orderBy('created_at','desc')
        		->get();

        	if(count($comments) > 0){

        		$cd = array();


        		foreach ($comments as $k => $v) {
        			$cd[$k] = $this->exportComment($v);
        		}

        		$cd = array_reverse($cd);

        		$data["comments"] = $cd;

        	}
        }

    	
        return $data;

    }

	/**
	 * 輸出 comment 格式
	 */
    private function exportComment($v){
    	$data["comment_id"] = $v->comment_id;
    	$data["content"] = $v->comment_content;
    	$data["created_at"] = $v->created_at;


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

            $comment = WallComment::where('comment_id', '=', $commnet_id)
                ->get();

            array_push($data, $this->exportComment($comment[0]));

    	}
    	
    	return $data;

    }


}
