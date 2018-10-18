<?php

function page_protect() {
session_start();
$link = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
global $db; 


if (isset($_SESSION['HTTP_USER_AGENT']))
{
    if ($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT']))
    {
        logout();
        exit;
    }
}

if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_name']) ) 
{
	if(isset($_COOKIE['user_id']) && isset($_COOKIE['user_key'])){
	
	$cookie_user_id  = filter($_COOKIE['user_id']);
	$rs_ctime = mysqli_query($link,"select `ckey`,`ctime` from `users` where `id` ='$cookie_user_id'") or die(mysqli_error($link));
	list($ckey,$ctime) = mysqli_fetch_row($rs_ctime);
	
	if( (time() - $ctime) > 60*60*24*COOKIE_TIME_OUT) {

		logout();
		}

	 if( !empty($ckey) && is_numeric($_COOKIE['user_id']) && isUserID($_COOKIE['user_name']) && $_COOKIE['user_key'] == sha1($ckey)  ) {
	 	  session_regenerate_id();
	
		  $_SESSION['user_id'] = $_COOKIE['user_id'];
		  $_SESSION['user_name'] = $_COOKIE['user_name'];
		
		  list($user_level) = mysqli_fetch_row(mysqli_query($link,"select user_level from users where id='$_SESSION[user_id]'"));

		  $_SESSION['user_level'] = $user_level;
		  $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
		  
	   } else {
	   logout();
	   }

  } else {
	header("Location: /login.php");
	exit();
	}
}
}

function verify_session() {
session_start();
if(!empty($_SESSION['user_name'])){header("Location: /");}
}

function filter($data) {
	$link = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
	$data = trim(htmlentities(strip_tags($data)));
	
	if (get_magic_quotes_gpc())
		$data = stripslashes($data);
	
	$data = mysqli_real_escape_string($link,$data);
	
	return $data;
}



function EncodeURL($url)
{
$new = strtolower(ereg_replace(' ','_',$url));
return($new);
}

function DecodeURL($url)
{
$new = ucwords(ereg_replace('_',' ',$url));
return($new);
}

function ChopStr($str, $len) 
{
    if (strlen($str) < $len)
        return $str;

    $str = substr($str,0,$len);
    if ($spc_pos = strrpos($str," "))
            $str = substr($str,0,$spc_pos);

    return $str . "...";
}	

function isEmail($email){
  return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? TRUE : FALSE;
}

function isUserID($username)
{
	if (preg_match('/^[a-z\d_]{5,20}$/i', $username)) {
		return true;
	} else {
		return false;
	}
 }	
 
function isURL($url) 
{
	if (preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $url)) {
		return true;
	} else {
		return false;
	}
} 

function checkPwd($x,$y) 
{
if(empty($x) || empty($y) ) { return false; }
if (strlen($x) < 4 || strlen($y) < 4) { return false; }

if (strcmp($x,$y) != 0) {
 return false;
 } 
return true;
}

function GenPwd($length = 7)
{
  $password = "";
  $possible = "0123456789bcdfghjkmnpqrstvwxyz"; //no vowels
  
  $i = 0; 
    
  while ($i < $length) { 

    
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
       
    
    if (!strstr($password, $char)) { 
      $password .= $char;
      $i++;
    }

  }

  return $password;

}

function GenKey($length = 7)
{
  $password = "";
  $possible = "0123456789abcdefghijkmnopqrstuvwxyz"; 
  
  $i = 0; 
    
  while ($i < $length) { 

    
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
       
    
    if (!strstr($password, $char)) { 
      $password .= $char;
      $i++;
    }

  }

  return $password;

}


function logout()
{
global $db;
session_start();
$link = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
$sess_user_id = strip_tags(mysqli_real_escape_string($link,isset($_SESSION['user_id'])));
$cook_user_id = strip_tags(mysqli_real_escape_string($link,isset($_COOKIE['user_id'])));

if(isset($sess_user_id) || isset($cook_user_id)) {
mysqli_query($link,"update `users` 
			set `ckey`= '', `ctime`= '' 
			where `id`='$sess_user_id' OR  `id` = '$cook_user_id'") or die(mysqli_error($link));
}		

unset($_SESSION['user_id']);
unset($_SESSION['user_name']);
unset($_SESSION['user_level']);
unset($_SESSION['HTTP_USER_AGENT']);
session_unset();
session_destroy(); 
setcookie("user_id", '', time()-60*60*24*COOKIE_TIME_OUT, "/");
setcookie("user_name", '', time()-60*60*24*COOKIE_TIME_OUT, "/");
setcookie("user_key", '', time()-60*60*24*COOKIE_TIME_OUT, "/");

header("Location: /login.php");
}

function PwdHash($pwd, $salt = null)
{
    if ($salt === null)     {
        $salt = substr(md5(uniqid(rand(), true)), 0, SALT_LENGTH);
    }
    else     {
        $salt = substr($salt, 0, SALT_LENGTH);
    }
    return $salt . sha1($pwd . $salt);
}

function checkAdmin() {

if($_SESSION['user_level'] == ADMIN_LEVEL) {
return 1;
} else { return 0 ;
}

}



?>