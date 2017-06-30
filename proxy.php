<?php
require "config.php";

function cn_urlencode($url){
    $pregstr = "/[\x{4e00}-\x{9fa5}]+/u";//UTF-8中文正则
    if(preg_match_all($pregstr,$url,$matchArray)){//匹配中文，返回数组
        foreach($matchArray[0] as $key=>$val){
            $url=str_replace($val, urlencode($val), $url);//将转译替换中文
        }
        if(strpos($url,' ')){//若存在空格
            $url=str_replace(' ','%20',$url);
        }
    }
    return $url;
}

$uri = $_GET["dst"] ;
//$uri =  $_SERVER['REQUEST_URI'];
//$uri = "Media/Default/home/视觉天职-1.png";

if(PROXY_SUBFOLDER != '')
    $uri = str_replace(PROXY_SUBFOLDER, '',$_SERVER['REQUEST_URI']);
$url = MASKED_DOMAIN . $uri;

$url = cn_urlencode($url);


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);

$request_headers = array( );
foreach ($_SERVER as $key => $value) {
    if (!strpos($key, 'Connection') ) {
        $headername = str_replace('_', ' ', str_replace('HTTP_', '', $key));
        $headername = str_replace(' ', '-', ucwords(strtolower($headername)));
        if (!in_array($headername, array( 'Host', 'X-Proxy-Url' ))) {
            $request_headers[] = "$headername: $value";
        }
    }
}

//curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);   // (re-)send headers


 $cookies = "";
foreach($_COOKIE as $k=>$v)
    $cookies .= $k.'='.$v.';';
curl_setopt($ch, CURLOPT_COOKIE, '"'.$cookies. '"');


// retrieve response (headers and content)
$response = curl_exec($ch);
curl_close($ch);


// split response to header and content
list($response_headers, $response_content) = preg_split('/(\r\n){2}/', $response, 2);

header_remove();
// (re-)send the headers
$response_headers = preg_split('/(\r\n){1}/', $response_headers);
foreach ($response_headers as $key => $response_header) {
    if (!preg_match('/^(Transfer-Encoding):/', $response_header) && !preg_match('/^(Content-Length):/', $response_header) ) {
        header($response_header, true);
    }
}
function remove_utf8_bom($text)
{
    $bom = pack('H*','EFBBBF');
    $text = preg_replace("/^$bom/", '', $text);
    return $text;
}

echo $response_content;


