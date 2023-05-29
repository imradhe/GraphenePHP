<?php
// Home URL
function home(){    
    require('config.php');
    return (empty($config['APP_SLUG']))? $config['APP_URL'] : $config['APP_URL'].$config['APP_SLUG']."/";
}
      
// Get URl
function url(){
    require('config.php');
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   ? "https://"   :  "http://";   
    // Append the host(domain name, ip) to the URL.   
    $url.= $_SERVER['HTTP_HOST'];   
    
    // Append the requested resource location to the URL   
    $url.= $_SERVER['REQUEST_URI'];    
    $url = (empty($config['APP_SLUG']))?substr(explode("?", $_SERVER['REQUEST_URI'])[0], 1):substr(explode("?", $_SERVER['REQUEST_URI'])[0], strlen($config['APP_SLUG'])+2);
    $url = (empty($url))? home():home().$url;  
    return $url;
}


function errors($enable = true) {
  if ($enable) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
  } else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
  }
  return $enable;
}


function unauthorized(){
    header("HTTP/1.0 401 Unauthorized");
    http_response_code(401);
    include('views/errors/401.php');
    exit;
}

function pageNotFound(){
    http_response_code(404);
    header("HTTP/1.0 404 Page Not Found");
    include('views/errors/404.php');
    exit;
}
// Lock a file
function locked($role = ['user']){
  controller("Auth");
  $auth = new Auth();
  if(empty(getSession())){
    redirectIfLocked(); 
    exit;
  }elseif(!in_array(getUser()['role'], $role)){
    return unauthorized();
  }
}


function redirectIfLocked(){
  if(url() != route("login")) return header("Location:".route("login")."?back=".url().str_replace('&','$',queryString()));
}


 
function assets($path){
  echo home()."assets/".$path;
}


function view($fileName){
  return require("views/".$fileName.".php");
}

// Get url for a route
function route($path){
  return home().$path;
}

// Get Query String
function queryString(){
  return "?".$_SERVER['QUERY_STRING'];
}

// Get Complete URl
function getRoute(){
  return url()."?".$_SERVER['QUERY_STRING'];
}

// Add Controller
function controller($className){
  return require('controllers/'.$className.'Controller.php');
}

// Add API Controller
function APIController($className){
  return require('controllers/api/'.$className.'Controller.php');
}


/**
* visitor analytical functions
* */





  function getOS()
  {
      $user_agent = $_SERVER['HTTP_USER_AGENT'];
      $os_platform = "Unknown OS Platform";
      $os_array = array(
          '/windows nt 11/i' => 'Windows 11',
          '/windows nt 10/i' => 'Windows 10',
          '/windows nt 6.3/i' => 'Windows 8.1',
          '/windows nt 6.2/i' => 'Windows 8',
          '/windows nt 6.1/i' => 'Windows 7',
          '/windows nt 6.0/i' => 'Windows Vista',
          '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
          '/windows nt 5.1/i' => 'Windows XP',
          '/windows xp/i' => 'Windows XP',
          '/windows nt 5.0/i' => 'Windows 2000',
          '/windows me/i' => 'Windows ME',
          '/win98/i' => 'Windows 98',
          '/win95/i' => 'Windows 95',
          '/win16/i' => 'Windows 3.11',
          '/macintosh|mac os x/i' => 'Mac OS X',
          '/mac_powerpc/i' => 'Mac OS 9',
          '/linux/i' => 'Linux',
          '/ubuntu/i' => 'Ubuntu',
          '/iphone/i' => 'iPhone',
          '/ipod/i' => 'iPod',
          '/ipad/i' => 'iPad',
          '/android/i' => 'Android',
          '/blackberry/i' => 'BlackBerry',
          '/webos/i' => 'Mobile'
      );
  
      foreach ($os_array as $regex => $value) {
          if (preg_match($regex, $user_agent)) {
              $os_platform = $value;
              break; // Added break to exit the loop once a match is found
          }
      }
  
      return $os_platform;
  }
  
  
  
  function getIP(){
        if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED"];
        } elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_FORWARDED_FOR"];
        } elseif (isset($_SERVER["HTTP_FORWARDED"])) {
            $ip = $_SERVER["HTTP_FORWARDED"];
        } else {
            $ip = $_SERVER["REMOTE_ADDR"];
        }
  
        // Strip any secondary IP etc from the IP address
        if (strpos($ip, ',') > 0) {
            $ip = substr($ip, 0, strpos($ip, ','));
        }
        return $ip;
  }
  
  function getDevice(){ 
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";
  
    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
      $platform = 'linux';
    }elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
      $platform = 'mac';
    }elseif (preg_match('/windows|win32/i', $u_agent)) {
      $platform = 'windows';
    }
  
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)){
      $bname = 'Internet Explorer';
      $ub = "MSIE";
    }elseif(preg_match('/Firefox/i',$u_agent)){
      $bname = 'Mozilla Firefox';
      $ub = "Firefox";
    }elseif(preg_match('/OPR/i',$u_agent)){
      $bname = 'Opera';
      $ub = "Opera";
    }elseif(preg_match('/Chrome/i',$u_agent) && !preg_match('/Edge/i',$u_agent)){
      $bname = 'Google Chrome';
      $ub = "Chrome";
      if(preg_match('/Edg/i',$u_agent)){
        $bname = 'Edge';
        $ub = "Edge";
      }elseif(preg_match('/OPR/i',$u_agent)){
        $bname = 'Opera';
        $ub = "Opera";
      }
    }elseif(preg_match('/Safari/i',$u_agent) && !preg_match('/Edge/i',$u_agent)){
      $bname = 'Apple Safari';
      $ub = "Safari";
    }elseif(preg_match('/Netscape/i',$u_agent)){
      $bname = 'Netscape';
      $ub = "Netscape";
    }elseif(preg_match('/Edg/i',$u_agent)){
      $bname = 'Edge';
      $ub = "Edge";
    }elseif(preg_match('/Trident/i',$u_agent)){
      $bname = 'Internet Explorer';
      $ub = "MSIE";
    }
  
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
  ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
      // we have no matching number just continue
    }
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
      //we will have two since we are not using 'other' argument yet
      //see if version is before or after the name
      if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
          $version= $matches['version'][0];
      }else {
          $version= $matches['version'][1];
      }
    }else {
      $version= $matches['version'][0];
    }
  
    // check if we have a number
    if ($version==null || $version=="") {$version=null;}
  
    return array(
      'userAgent' => $u_agent,
      'browser'      => $bname,
      'os' => getOS(),
      'version'   => $version,
      'platform'  => $platform,
      'ip' => getIP()
    );
  } 
  




/**
* API functions
* */

function sendRequest($url, $token = "", $method, $fields = ""){
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => $method,
    CURLOPT_HTTPHEADER => array(
      'Authorization: Bearer '.$token
    ),
    CURLOPT_POSTFIELDS => $fields
  ));
  $response = curl_exec($curl);

  curl_close($curl);

  return $response;
}



function getAuthorizationHeader(){
  $headers = null;
  if (isset($_SERVER['Authorization'])) {
      $headers = trim($_SERVER["Authorization"]);
  }
  else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
      $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
  } elseif (function_exists('apache_request_headers')) {
      $requestHeaders = apache_request_headers();
      // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
      $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
      //print_r($requestHeaders);
      if (isset($requestHeaders['Authorization'])) {
          $headers = trim($requestHeaders['Authorization']);
      }
  }
  return $headers;
}

/**
* get access token from header
* */
function getBearerToken() {
  $headers = getAuthorizationHeader();
  // HEADER: Get the access token from the header
  if (!empty($headers)) {
      if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
          return $matches[1];
      }
  }
  return null;
}


function getAPIToken() {
  $headers = null;
  if (isset($_SERVER['token'])) {
      $headers = trim($_SERVER["token"]);
  }
  elseif (isset($_SERVER['HTTP_TOKEN'])) {
      $headers = trim($_SERVER["HTTP_TOKEN"]);
  }
  elseif (isset($_SERVER['HTTP_ACCEPT'])) {
      $headers = trim($_SERVER["HTTP_ACCEPT"]);
  }
  elseif (function_exists('apache_request_headers')) {
      $requestHeaders = apache_request_headers();
      // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
      $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
      //print_r($requestHeaders);
      if (isset($requestHeaders['token'])) {
          $headers = trim($requestHeaders['token']);
      }
  }
  return $headers;
}


/**
* encryption functions
* */

function sha256($string) {
  return hash('sha256', $string);
}

function sha512($string) {
  return hash('sha512', $string);
}




function integerToRoman($integer)
{
 $integer = intval($integer);
 $result = '';
 
  $lookup = array('M' => 1000,
 'CM' => 900,
 'D' => 500,
 'CD' => 400,
 'C' => 100,
 'XC' => 90,
 'L' => 50,
 'XL' => 40,
 'X' => 10,
 'IX' => 9,
 'V' => 5,
 'IV' => 4,
 'I' => 1);
 
 foreach($lookup as $roman => $value){
     
  $matches = intval($integer/$value);
 
  $result .= str_repeat($roman,$matches);
 
  $integer = $integer % $value;
 }
 
 return $result;
}

function relativeTime($timestamp){
        $time = strtotime($timestamp);
        $difference = time() - $time;
        $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
        $lengths = array("60","60","24","7","4.35","12","10");
        for($j = 0; $difference >= $lengths[$j]; $j++)
            $difference /= $lengths[$j];
        $difference = round($difference);
        if($difference != 1) $periods[$j].= "s";
        return $text = "$difference $periods[$j] ago";
}

function slugify($text, string $divider = '-'){
  $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
  $text = preg_replace('~[^-\w]+~', '', $text);
  $text = trim($text, $divider);
  $text = preg_replace('~-+~', $divider, $text);
  $text = strtolower($text);
  if (empty($text)) {
    return 'n-a';
  }
  return $text;
}


// Convert currency value to INR
function strtoinr($price){ 
  $locale = 'hi';
  $currency = 'INR';
  $inr = new NumberFormatter($locale, NumberFormatter::CURRENCY);
  $price = (int)$price;
  $formattedPrice = $inr->formatCurrency($price, $currency);
  return substr($formattedPrice, 0, strlen($formattedPrice)-3);
}

