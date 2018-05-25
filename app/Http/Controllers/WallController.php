<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Auth;

class WallController extends Controller
{   
    function __construct(){
        // $this->user = $this->get_user_info();;
    }


    public function index(){

        // return $auth->id;

        $data = array();
        $data["user"] = app(UserController::class)->get_user_info();

        // dd($data);

        $view = view("wall.wall_index")->with("data",$data);


        return $view;
    }

    // 取得網址內的meta資訊
    public function get_url_preview(Request $request){
        $url = $request->input("url","");

        $data = array();
        $data["error"] = true;

        if($url != ""){

            # 驗證url
            if(filter_var($url, FILTER_VALIDATE_URL)){

                // parse
                $urlData = parse_url($url);

                // dd($urlData);

                // Log::info("getUrlPreview:test");
        
                if( !($urlData['scheme'] =='http' || $urlData['scheme'] =='https')  ){
                    // return json_encode($data);
                    return response()->json($data);
                }

                # 對youtube網址進行處理
                if($urlData['host'] == 'youtu.be'){
                    if(isset($urlData['path'])){
                        $videoid = str_replace('/', '', $urlData['path']);
                        $url = "https://youtu.be/".$videoid;
                    }
                    
                }else if($urlData['host'] == 'www.youtube.com'){
                    if(isset($urlData['query'])){
                        parse_str($urlData['query'],$parse_str);
                        $videoid = $parse_str['v'];
                        $url = "https://www.youtube.com/watch?v=".$videoid;
                    }
                }

                // Log::info("getUrlPreview:test2");

                // 是否可連
                $url_exists = false;

                $ch = curl_init();

                //偽裝成googlebot已避免被阻擋


                // curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept-Language: zh-Hant']);


                // curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36');
                
                // curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'); 

                curl_setopt($ch, CURLOPT_USERAGENT, 'Google bot'); 

                $header = array();
                $header[] = 'Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5'; 
                $header[] = 'Cache-Control: max-age=0'; 
                $header[] = 'Content-Type: text/html; charset=utf-8'; 
                $header[] = 'Connection: keep-alive'; 
                $header[] = 'Keep-Alive: 300'; 
                $header[] = 'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7'; 
                $header[] = 'Accept-Language: zh-TW,zh;q=0.9,en-US;q=0.8,en;q=0.7'; 
                $header[] = 'Pragma:'; 
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                curl_setopt($ch, CURLOPT_REFERER, 'https://www.google.com'); 
                curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
                curl_setopt($ch, CURLOPT_AUTOREFERER, true); 

                // Log::info("url :".$url);

                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

                # 設置 curl 逾時
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);

                $curl_result = curl_exec($ch);
                // $curl_http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $curl_headers = curl_getinfo($ch);


                $curl_http_code = $curl_headers['http_code'];

                curl_close($ch);
                unset($ch);

                // Log::info("curl_http_code:".$curl_http_code);


                if ($curl_result !== false) {
                    if($curl_http_code == 200 || $curl_http_code == 404){
                        $url_exists = true;
                        // Log::info("getUrlPreview:test3");
                    }
                }

                // Log::info("curl_http_code:".$curl_http_code);

                // 抓取 meta
                if($url_exists){

                    // 判斷網頁格式
                    $curl_content_type = explode(";",$curl_headers['content_type']);
                    $curl_content_type = $curl_content_type[0];

                    // return $curl_content_type;

                    $image_arr = array('image/jpeg','image/png','image/gif');
                    $web_arr = array('text/html');

                    $data["preview_data"] = array();

                    if( in_array($curl_content_type,$web_arr)){ # 網頁

                        $data["preview_data"]["link_title"] = null;
                        $data["preview_data"]["link_description"] = null;
                        $data["preview_data"]["link_image"] = null;
                        $data["preview_data"]["link_image_width"] = 0;
                        $data["preview_data"]["link_image_height"] = 0;


                        $html = new \DOMDocument();

                        // 避免亂碼
                        $curl_result = '<meta http-equiv="Content-Type" content="text/html; charset="utf-8"/>'.$curl_result; 

                        @$html->loadHTML($curl_result);

                        // return dd($html);

                        $metaData = array();

                        //Get all meta tags and loop through them.
                        foreach($html->getElementsByTagName('meta') as $meta) {
                            //If the property attribute of the meta tag is og:image
                            // if($meta->getAttribute('property')=='og:image'){ 
                            //     //Assign the value from content attribute to $meta_og_img
                            //     $metaData["og:image"] = $meta->getAttribute('content');
                            // }

                            $name = $meta -> getAttribute('name');
                            if ($meta -> getAttribute('property') != '') {
                                $name = $meta -> getAttribute('property');
                            }
                           
                            if ($name != '') {
                                $metaData[$name] = $meta -> getAttribute('content');
                            }

                        }

                        // return dd($metaData);

                        if(isset($metaData['og:image'])){

                            // Log::info("og:image :".$metaData['og:image']);

                            if(filter_var($metaData['og:image'], FILTER_VALIDATE_URL)){

                                try{
                                    $img_data = getimagesize($metaData["og:image"]);

                                    $data["preview_data"]["link_image"] = $metaData["og:image"];

                                    

                                    $data["preview_data"]["link_image_width"] = $img_data[0];
                                    $data["preview_data"]["link_image_height"] = $img_data[1];
                                } catch (\Exception $e){
                                    
                                }
                                
                            }
                        }else{ // 沒有 og:image ， 另外抓取原始碼內的img tag

                            $img_check_length = 0;

                            $src_tmp_arr = array();

                            foreach($html->getElementsByTagName('img') as $img){

                                $src = $img->getAttribute('src');
                                
                                if($src != "" && $img_check_length < 10 && !in_array($src,$src_tmp_arr)){

                                    array_push($src_tmp_arr, $src);

                                    $img_check_length ++;

                                    // 相對路徑 轉換成 絕對路徑
                                    $base_url = $urlData["scheme"]."://".$urlData["host"].$urlData["path"];

                                    $src = app(\App\Http\Controllers\FuncController::class)->rel2abs($src,$base_url);

                                    // Log::info("{$img_check_length} src:{$src}");

                                    if(filter_var($src, FILTER_VALIDATE_URL)){

                                        try{
                                            $img_data = getimagesize($src);

                                            if($img_data[0] > 300 || $img_data[1] > 300){
                                                $data["preview_data"]["link_image"] = $src;

                                                $data["preview_data"]["link_image_width"] = $img_data[0];
                                                $data["preview_data"]["link_image_height"] = $img_data[1];

                                                break;
                                            }
                                        } catch (\Exception $e){
                                            continue;
                                        }
                                    }

                                    
                                }
                            }

                            unset($src_tmp_arr);

                        }

                        $data["preview_data"]["link_title"] = '';
                        if(isset($metaData['og:title'])){
                            $data["preview_data"]["link_title"] = $metaData["og:title"];
                        }else{
                            if(preg_match("/<title>(.*)<\/title>/s", $curl_result, $match)){
                                $data["preview_data"]["link_title"] = $match[1];
                            }
                            
                            
                        }

                        $data["preview_data"]["link_description"] = '';
                        if(isset($metaData['og:description'])){
                            $data["preview_data"]["link_description"] = $metaData["og:description"];
                        }elseif(isset($metaData['description'])){
                            $data["preview_data"]["link_description"] = $metaData["description"];
                        }

                        unset($html);
                        unset($curl_result);

                        $data["error"] = false;

                        
                    }elseif(in_array($curl_content_type, $image_arr)){ # url 是圖片

                        $data["error"] = false;   

                        $data["preview_data"]["link_image"] = $url; 

                        $img_data = getimagesize($url);

                        $data["preview_data"]["link_image_width"] = $img_data[0];
                        $data["preview_data"]["link_image_height"] = $img_data[1];

                    }

                    # 更新到資料庫上
                    if(count($data["preview_data"]) > 0 ){

                        $ud = array();
                        foreach ($data["preview_data"] as $k => $v) {
                            $v = str_replace(array("\r", "\n", "\r\n", "\n\r"), '', $v);
                            $ud[$k] = trim($v);
                        }

                        if(count($ud)>0){

                            $ud["link_url"] = $url;
                            $ud["link_updated"] = date("Y-m-d H:i:s");

                            $_wpl = DB::table("wall_preview_links")
                                ->where("wall_preview_links.link_url",$url)
                                ->take(1)
                                ->get();

                            // dd($_wpl);

                            if(count($_wpl)){
                                DB::table("wall_preview_links")->where("wall_preview_links.link_id",$_wpl[0]->link_id)->update($ud);
                            }else{
                                DB::table("wall_preview_links")->insert($ud);
                            }
                        }
                        

                    }


                }
                
                
            }

        }
        

        if($data["error"] != true){
            $data["preview_data"] = $this->get_preview_link_info($url);
        }

        // return json_encode($data);
        return response()->json($data);
    }
    

    // 取得 preview link info
    public function get_preview_link_info($url){

        $data = array();
        $data["url"] = $url;

        $urlData = parse_url($url);

        $surl = $url;

        # 對youtube網址進行處理
        if($urlData['host'] == 'youtu.be'){
            if(isset($urlData['path'])){
                $videoid = str_replace('/', '', $urlData['path']);
                $surl = "https://youtu.be/".$videoid;

                $data["youtube"] = $videoid ;
            }
        }else if($urlData['host'] == 'www.youtube.com'){
            if(isset($urlData['query'])){
                parse_str($urlData['query'],$parse_str);
                $videoid = $parse_str['v'];
                $surl = "https://www.youtube.com/watch?v=".$videoid;
                $data["youtube"] = $videoid ;
            }
        } 

        $_q = DB::table("wall_preview_links")
            ->where("link_url",$surl)
            ->take(1)
            ->get();

        if($_q){
            $data["link_url"] = $url;
            $data["link_title"] = $_q[0]->link_title;
            $data["link_description"] = $_q[0]->link_description;
            $data["link_image"] = $_q[0]->link_image;
            $data["link_image_width"] = $_q[0]->link_image_width;
            $data["link_image_height"] = $_q[0]->link_image_height;

        }

        return $data;

    }
}
