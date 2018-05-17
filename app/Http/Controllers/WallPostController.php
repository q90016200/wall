<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth,DB;

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
        $user = app(WallController::class)->get_user_info();
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

}
