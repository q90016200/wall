<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth,DB,Log;

class WallPostController extends Controller
{   

    function __construct(){
        
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //test
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->input();
        // return $request->file();

        // 目前使用者
        $user = app(UserController::class)->get_user_info();
        $uid = $user["uid"];

         // 發文內容
        $post_content = $request->input("post_content","");
        $post_content = trim($post_content);

        $data = array();
        $data["error"] = true;

        if(Auth::check()){
            $post_image_exist = false;
            $post_image_status = false;

            if ($request->hasFile('post_img')) { # 確認檔案是否有上傳
                if($request->file('post_img')->isValid()){ # 確認上傳的檔案是否有效

                    $post_image_exist = true;

                    $post_img = $request->file('post_img');

                    $post_image_status = $this->post_image_check($post_img);
                }
            }

            $_itd = array();
            $_itd["post_author"] = $uid;
            $_itd["post_content"] = $post_content;
            $_itd["post_create_date"] = date("Y-m-d H:i:s");
            $_itd["post_create_timestamp"] = number_format(microtime(true)*1000,0,'.','');
            $_itd["post_sort_time"] = $_itd["post_create_timestamp"];

            # 沒有上傳圖片才能存預覽網址
            if(!$post_image_exist && !$post_image_status){
                $preview_link = $request->input("post_preview_link","");

                if($preview_link != ""){
                    $_itd["post_preview_link"] = $preview_link;
                }
            }

            $post_id = DB::table("wall_posts")->insertGetId($_itd);

            // 檢查檔案 ok 才上傳圖片
            if($post_image_exist && $post_image_status){

                # img 移動 真實目錄
                $upload_img = $this->post_image_upload($post_img,$post_id);


                # 更新貼文總圖片數
                $_piud = array();
                $_piud["post_image_count"] = 1;
                
                DB::table("wall_posts")
                    ->where("post_id",$post_id)
                    ->update($_piud);
            }

            $data["posts_data"] = $this->getPostById($post_id);
            $data["user"] = app(UserController::class)->get_user_info($uid); 

            $data["error"] = false;
        }



        // return $request->file('post_img');


        return response()->json($data);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // 檢查 post 新增 img 格式
    private function post_image_check($image){
        
        $image_status = true;

        # 上傳圖片文件類型列表
        $uptypes = array(
            "image/jpg",
            "image/jpeg",
            "image/pjpeg",
            "image/png"
        );

        $file_type = $image->getMimeType();

        if($file_type == "application/octet-stream"){
            $imageMime = getimagesize($image);
            $file_type = $imageMime['mime'];
        }

        if (!in_array($file_type,$uptypes)){ # 如果不在組類，提示處理
            $image_status = false;
        }
        

        # 檔案大於 10 MB
        if($image->getClientSize() > 10485760){
            $image_status = false;
        }


        return $image_status;

    }

    private function post_image_upload($image,$post_id){

        $img_info = getimagesize($image);
        $img_mime = $img_info["mime"];

        // return $img_info;

        switch($img_mime){
            case "image/jpeg":
                $file_type = "jpg";
                break;
            case "image/png":
                $file_type = "png";
                break;
            case "image/gif":
                $file_type = "gif";
                break;
            default:
                $file_type = "jpg";
                break;
        }

        $file_name = $post_id."_".uniqid();
        $file_name = $file_name.".".$file_type;

        $file_dir = "wall/posts/{$post_id}/";
        
        $image->move(public_path('img/'.$file_dir),$file_name);

        $filepath = public_path('img/'.$file_dir).$file_name;

        // #查看圖片是否有轉向 並修正 fix_orientation
        if($img_mime == "image/jpeg"){
           $this->image_fix_orientation($filepath);
        }
        
        // 裁圖

        $img_info = getimagesize($filepath);

        $width = $img_info[0];
        $height = $img_info[1];

        $new_width = 1000; 
        if ($width <= $new_width) { # 原圖小於 new width
            $new_width = $width;
            $new_height = $height;
        } else {
            $percent = $new_width / $width;
            $new_height = $height * $percent;
        }


        $image_new = imagecreatetruecolor($new_width, $new_height);

        switch ($img_mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($filepath);
                break;

            case 'image/png':
                $image = imagecreatefrompng($filepath);
                break;

            case 'image/gif':
                $image = imagecreatefromgif($filepath);
                break;

            default:
                $image = imagecreatefromjpeg($filepath);
                break;
        }

        imagecopyresampled($image_new, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        switch ($img_mime) {
            case 'image/jpeg':
                imagejpeg($image_new, $filepath, 100);

                break;

            case 'image/png':
                imagepng($image_new, $filepath);

                break;

            case 'image/gif':
                imagegif($image_new, $filepath, 100);
                break;

            default:
                imagejpeg($image_new, $filepath, 100);
                break;
        }    

        $_piit = array();
        $_piit["img_post_id"] = $post_id;
        $_piit["img_path"] = $file_dir.$file_name;

        DB::table("wall_post_imgs")->insert($_piit);


    }


    // 查看圖片是否有轉向 並修正 fix_orientation
    public function image_fix_orientation( $filepath) {
        $exif = @exif_read_data($filepath);

        if($exif){

            try {
                $image = imagecreatefromjpeg($filepath);
            } catch(\ErrorException $e) {
                //0xd9
                if (strpos($e->getMessage(), '0xd9')!==false) {
                    $cmd = "/usr/local/bin/jpegoptim -os --all-progressive --strip-all {$filepath} >> /dev/null 2>&1";
           
                    // exec($cmd);
                }
            }

            if (!empty($exif['Orientation'])) {
                switch ($exif['Orientation']) {
                    case 3:
                        $image = imagerotate($image, 180, 0);
                        break;

                    case 6:
                        $image = imagerotate($image, -90, 0);
                        break;

                    case 8:
                        $image = imagerotate($image, 90, 0);
                        break;
                }

                imagejpeg($image, $filepath, 90);
            }
        }
        
    }

    public function latest(){

        $limit = 5;

        $data["error"] = true;

        // 取得 取得最新貼文的時間
        $get_time = 0 ;
        if(isset($_GET["t"])){
            $get_time = $_GET["t"];
        }

        $page = 1;

        if(isset($_GET["page"])){
            $page = $_GET["page"];
        }


        $_p = DB::table("wall_posts");
        
        if($get_time > 0 ){
            $_p = $_p->where("post_create_timestamp","<",$get_time);
        }

        $_p = $_p->where("post_status","publish");

        # 取得貼文總數
        $post_count = $_p->count();

        $_p = $_p->orderBy("wall_posts.post_sort_time","desc")
            ->skip(($page-1)*$limit)
            ->take($limit)
            ->get();

        $data["page"] = $page;
        $data["total_page"] = ceil($post_count / $limit);
        $data["posts"] = array();

        if($_p){
            // return dd($_p);

            $data["error"] = false;
            

            $pd = array();

            foreach ($_p as $k => $v) {
                $pd[$k] = $this->export_post_data($v);
            }

            $data["posts"] = $pd;
        }


        return response()->json($data);
        
        
    }

    private function export_post_data($v){
        $user = app(UserController::class)->get_user_info();
        $uid = $user["uid"];

        $data["post_id"] = $v->post_id;

        $content = $v->post_content;
        $content = htmlspecialchars($content);

        $data["content"] = $content;

        unset($content);

        $data["create_date"] = date("Y/m/d H:i:s",($v->post_create_timestamp/1000));

        if($v->post_modify_date != null){
            $data["post_modify_date"] =  date("Y/m/d H:i:s",strtotime($v->post_modify_date));
        }

        # 貼文 like
        $data["like_count"] = $v->post_like_count;
        $data["is_liked"] = 0;

        if(isset($v->like_status )){
            if($data["like_count"] > 0 && $v->like_status != null && $v->like_status != "delete"){
                $data["is_liked"] = 1;
            }
        }


        # 貼文 link preview
        if($v->post_preview_link != null){
            
            $data["preview"] = app(WallController::class)->get_preview_link_info($v->post_preview_link);
        }

        # 貼文 image
        if($v->post_image_count > 0){

            $post_image_arr = $this->getPostImage($v->post_id);

            if(count($post_image_arr) > 0){
                $data["images"] = $post_image_arr;
            }

        }

        # 編輯&刪除 貼文 權限
        $data["is_edit"] = 0;


        if($uid == $v->post_author ){
            $data["is_edit"] = 1;
        }                

        # 貼文狀態
        $data["post_status"] = $v->post_status;

        $data["user"] = app(UserController::class)->get_user_info($v->post_author);

        $data["comment_data"] = app(WallCommentController::class)->latest($v->post_id,3);


        return $data;
    }

    // 取得貼文 image
    private function getPostImage($post_id){
        $data = array();

        $_q = DB::table("wall_post_imgs")
            ->where("img_post_id",$post_id)
            ->get();
        
        if($_q){
            foreach ($_q as $k => $v) {
                $data[$k]["origin"] = "https://img.mvmv.com.tw/".$v->img_path;
                $data[$k]["img_id"] = $v->img_id;                            
            }
        }


        return $data;
    }

    // 依照 post id 取得 post data
    public function getPostById($post_id = 0){
 
        $user = app(UserController::class)->get_user_info();
        $uid = $user["uid"];

        $_pq = DB::table("wall_posts")
            ->select("wall_posts.post_id","wall_posts.post_author","wall_posts.post_content","wall_posts.post_like_count","wall_posts.post_comment_count","wall_posts.post_image_count","wall_posts.post_preview_link","wall_posts.post_create_timestamp","wall_posts.post_modify_date","wall_posts.post_top","wall_posts.post_status","wall_posts.post_category","wall_posts.post_category","post_tag_works","post_tag_actors");
        
        if($uid != 0){
            // $_pq = $_pq->addSelect("wall_post_likes.like_status");
            // $_pq = $_pq->leftJoin("wall_post_likes",function($join) use($uid,$post_id){
            //     $join->on("wall_post_likes.like_post_id","=","wall_posts.post_id")
            //         ->where("wall_post_likes.like_uid","=",$uid);
            // });
        }

        
        $_pq = $_pq->where("wall_posts.post_status","publish");
        

        $_pq = $_pq->where("wall_posts.post_id",$post_id)
            ->get();

        $pd = array();

        if(count($_pq)){
            array_push($pd, $this->export_post_data($_pq[0]));
        }    

        return $pd;
    }   


}
