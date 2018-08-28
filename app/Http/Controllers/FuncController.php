<?php
namespace App\Http\Controllers;
use DB,Session,Input;
use Log;

class FuncController extends Controller{
    
	public function __construct() {
        $this->a = "test";
    }

    // 取得今天剩下分鐘
    public function get_today_lastMin(){
        $now_time = strtotime(date("Y-m-d H:i:s"));
        $tomorrow = strtotime(date("Y-m-d",strtotime('+1 day')));
        $last_min = (($tomorrow - $now_time) / (60 * 60)) * 60;

        return floor($last_min);

    }

    // 取得這周開始&結束日期 
    public function get_today_week_period($c_date = "empty"){
    	if($c_date == "empty"){
    		$c_date = date("Y-m-d");
    		// $c_date = "2017-01-14";
    	}

    	$wn = date("w",strtotime($c_date));
    	
    	$r_date = array();

    	if($wn == 0){
    		$r_date["start"] = date("Y-m-d",strtotime('-6 day',strtotime($c_date)));
    		$r_date["end"] = $c_date;	
    	}else{
    		#開始
    		if($wn == 1 ){
    			$r_date["start"] = $c_date;
    		}else{
    			$wn_tmp = $wn ;
    			$start = $c_date;
    			while ($wn_tmp > 1) {
    				$start = date("Y-m-d",strtotime('-1 day',strtotime($start)));
    				$wn_tmp = $wn_tmp - 1;
    			}
    			$r_date["start"] = $start;
    		}
    		#結束
    		$wn_tmp = $wn ;
    		$end = $c_date;

    		while($wn_tmp < 7){
				$end = date("Y-m-d",strtotime('+1 day',strtotime($end)));
				$wn_tmp = $wn_tmp + 1;
    		}
    		$r_date["end"] = $end;

    	}

    	// return dump2($r_date);
    	return $r_date;

    }
    
    // PHP判斷遠程圖片（文件）是否存在
    public function check_remote_file($url){
        $curl = curl_init($url);
        // 不取回數據
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); 

        //設置逾時
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);

        // 發送請求
        $result = curl_exec($curl);
        $found = false;
        // 如果請求沒有發送失敗
        if ($result !== false) {
            // 再檢查http響應碼是否為200
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);  

            // Log::info("statusCode:"+$statusCode);

            if ($statusCode == 200) {
                $found = true;   
            }
        }
        curl_close($curl);
        unset($curl);
     
        return $found;
    }

    public function my_serialize($obj){
        return base64_encode(gzcompress(serialize($obj)));
    }

    public function my_unserialize($txt){
        return unserialize(gzuncompress(base64_decode($txt)));
    }

    // Google reCaptcha
    public function reCaptcha($response){
        
        $secret = "6LeUuv4SAAAAAL292-J_ZpvK29AfqegmnCRqbQn3";


        // $response = Input::get("response");

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array(
            'secret' => $secret,
            'response' => $response
        );

        $headers = array(
            'Content-Type: application/x-www-form-urlencoded',
        );

        $options = array(
            'http' => array (
                'method' => 'POST',
                'content' => http_build_query($data),
                'header' => implode("\r\n", $headers),
            )
        );

        $context  = stream_context_create($options);

        $verify = file_get_contents($url, false, $context);

        $captcha_success=json_decode($verify);

        if($captcha_success->success){
            return "success";
        }else{
            return "fail";
        }
        
    }

    //無條件進位
    public function ceil_dec($v, $precision){
        $c = pow(10, $precision);
        return ceil($v*$c)/$c;
    }
    
    //無條件捨去
    public function floor_dec($v, $precision){
        $c = pow(10, $precision);
        return floor($v*$c)/$c;
    }

    // 取得ip
    public function getUserIP()
    {
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = $_SERVER['REMOTE_ADDR'];
        if(filter_var($client, FILTER_VALIDATE_IP)){
            $ip = $client;
        }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
            $ip = $forward;
        }else{
            $ip = $remote;
        }
        return $ip;
    }

    public function get_gtm_movie_or_series($type,$oCountry){
        
        $movie_or_series = "";
        
        if($type == "0"){
            $movie_or_series = "Movies";
        }elseif($type == "1"){
            if($oCountry == "TW"){
                $movie_or_series = "Taiwan_series";
            }elseif($oCountry == "US" || $oCountry == "GB" || $oCountry == "CA"){
                $movie_or_series = "USA_series";
            }elseif($oCountry == "JP"){
                $movie_or_series = "Japan_series";
            }elseif($oCountry == "KR"){
                $movie_or_series = "Korea_series";
            }elseif($oCountry == "CN"){
                $movie_or_series = "China_series";
            }
        }

        return $movie_or_series;

    }

    function get_browser_name($user_agent){
        if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')) return 'Opera';
        elseif (strpos($user_agent, 'Edge')) return 'Edge';
        elseif (strpos($user_agent, 'Chrome')) return 'Chrome';
        elseif (strpos($user_agent, 'Safari')) return 'Safari';
        elseif (strpos($user_agent, 'Firefox')) return 'Firefox';
        elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')) return 'Internet Explorer';
        
        return 'Other';
    }

    public function clean_xss(&$string, $low = False){
        if (! is_array ($string)){
            $string = trim($string );
            $string = strip_tags( $string );
            $string = htmlspecialchars( $string );
            if ($low){
                return True;
            }
            $string = str_replace( array('"', "\\", "'", "/", "..", "../", "./", "//" ), '', $string );
            $no = '/%0[0-8bcef]/';
            $string = preg_replace( $no, '', $string );
            $no = '/%1[0-9a-f]/';
            $string = preg_replace( $no, '', $string );
            $no = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';
            $string = preg_replace( $no, '', $string );
            return True;
        }
        $keys = array_keys( $string );

        foreach ( $keys as $key ){
            clean_xss ( $string[$key] );
        }
    }
    
    // 建立20位的唯一的 ID
    public function create20b_uniqId(){
        return uniqid().dechex(rand(256,4095)).rand(1000,9999); 
    }

    // 轉換成中文星期幾
    public function get_chinese_weekday($datetime){
        $weekday = date('w', strtotime($datetime));
        return ['日', '一', '二', '三', '四', '五', '六'][$weekday];
    }

    public function getUA() { #判斷用戶端設備
        $ua = isset($_SERVER['HTTP_USER_AGENT']) && !empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
        
        if($ua==null) return 'desktop';
        
        $iphone = strstr(strtolower($ua), 'mobile'); //Search for 'mobile' in user-agent (iPhone have that)
        $android = strstr(strtolower($ua), 'android'); //Search for 'android' in user-agent
        $windowsPhone = strstr(strtolower($ua), 'phone'); //Search for 'phone' in user-agent (Windows Phone uses that)



        $androidTablet = androidTablet($ua); //Do androidTablet function
        $ipad = strstr(strtolower($ua), 'ipad'); //Search for iPad in user-agent

        if ($androidTablet || $ipad) { //If it's a tablet (iPad / Android)
            return 'tablet';
        } elseif ($iphone && !$ipad || $android && !$androidTablet || $windowsPhone) { //If it's a phone and NOT a tablet
            return 'mobile';
        } else { //If it's not a mobile device
            return 'desktop';
        }
    }

    public function sign_shift($strs, $width) {


        $charTable = array(
            "　" => " ",
            "！" => "!",
            "＂" => "\"",
            "＃" => "#",
            "＄" => "$",
            "％" => "%",
            "＆" => "&",
            "＇" => "'",
            "（" => "(",
            "）" => ")",
            "＊" => "*",
            "＋" => "+",
            "，" => ",",
            "－" => "-",
            "．" => ".",
            "／" => "/",
            "：" => ":",
            "；" => ";",
            "＜" => "<",
            "＝" => "=",
            "＞" => ">",
            "？" => "?",
            "＠" => "@",
            "［" => "[",
            "＼" => "\\",
            "］" => "]",
            "＾" => "^",
            "＿" => "_",
            "｀" => "`",
            "｛" => "{",
            "｜" => "|",
            "｝" => "}",
            "～" => "~",
        );

        if ($width == 'full') {
            // 轉全形
            $strtmp = str_replace($charTable, array_flip($charTable), $strs);
        } elseif($width == 'half') {
            // 轉半形
            $strtmp = str_replace(array_flip($charTable), $charTable, $strs);
        }

        return $strtmp;
    }

    public function addLinkText($str){
        $regex = "{ ((https?|telnet|gopher|file|wais|ftp):[\/\/[\.\-\_\/a-zA-Z0-9\~\?\%\#\=\@\:\&\;\*\\\-]+?)(?=[.:?\\-]*(?:[^\/\/[\.\-\_\/a-zA-Z0-9\~\?\%\#\=\@\:\&\;\*\\\-]|$)) }x";
        $str = preg_replace($regex,"<a href=\"$1\" target=\"_blank\" >$1</a>",$str);

        return $str;
    }

    public function get_timestamp_millisecond(){
        $time = number_format(microtime(true)*1000,0,'.','');
        return $time;  
    }


    // Transfrom relative path into absolute URL using PHP 相對路徑 轉換成 絕對路徑

    public function rel2abs( $rel, $base ) {

        // parse base URL  and convert to local variables: $scheme, $host,  $path
        extract( parse_url( $base ) );

        if ( strpos( $rel,"//" ) === 0 ) {
            return $scheme . ':' . $rel;
        }

        // return if already absolute URL
        if ( parse_url( $rel, PHP_URL_SCHEME ) != '' ) {
            return $rel;
        }

        // queries and anchors
        if ( $rel[0] == '#' || $rel[0] == '?' ) {
            return $base . $rel;
        }

        // remove non-directory element from path
        $path = preg_replace( '#/[^/]*$#', '', $path );

        // destroy path if relative url points to root
        if ( $rel[0] ==  '/' ) {
            $path = '';
        }

        // dirty absolute URL
        $abs = $host . $path . "/" . $rel;

        // replace '//' or  '/./' or '/foo/../' with '/'
        $abs = preg_replace( "/(\/\.?\/)/", "/", $abs );
        $abs = preg_replace( "/\/(?!\.\.)[^\/]+\/\.\.\//", "/", $abs );

        // absolute URL is ready!
        return $scheme . '://' . $abs;
    }

}