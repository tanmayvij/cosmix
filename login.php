<?php 

include 'config.php';
verify_session();
$link = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
$errors = array();

foreach($_GET as $key => $value) {
	$get[$key] = filter($value);
}

if (isset($_POST['doLogin'])=='Login')
{

foreach($_POST as $key => $value) {
	$data[$key] = filter($value);
}


$user_email = $data['usr_email'];
$pass = $data['pwd'];


if (strpos($user_email,'@') === false) {
    $user_cond = "user_name='$user_email'";
} else {
      $user_cond = "user_email='$user_email'";
    
}

	
$result = mysqli_query($link,"SELECT `id`,`user_name`,`pwd`,`full_name`,`approved`,`user_level` FROM users WHERE 
           $user_cond
			AND `banned` = '0'
			") or die (mysqli_error($link)); 
$num = mysqli_num_rows($result);

    if ( $num > 0 ) { 
	
	list($id,$username,$pwd,$full_name,$approved,$user_level) = mysqli_fetch_row($result);
	
	if(!$approved) {
	$errors[] = "Account not activated. Please contact the admin";
	
	 }
	 
	if ($pwd === PwdHash($pass,substr($pwd,0,9))) { 
	if(empty($errors)){			

       session_start();

		$_SESSION['user_id']= $id;  
		$_SESSION['user_name'] = $full_name;
		$_SESSION['username'] = $username;
		$_SESSION['user_level'] = $user_level;
		$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
		
		$stamp = time();
		$ckey = GenKey();
		mysqli_query($link,"update users set `ctime`='$stamp', `ckey` = '$ckey' where id='$id'") or die(mysqli_error($link));
		
	   if(isset($_POST['remember'])){
				  setcookie("user_id", $_SESSION['user_id'], time()+60*60*24*COOKIE_TIME_OUT, "/");
				  setcookie("user_key", sha1($ckey), time()+60*60*24*COOKIE_TIME_OUT, "/");
				  setcookie("user_name",$_SESSION['user_name'], time()+60*60*24*COOKIE_TIME_OUT, "/");
				   }
		  header("Location: /");
		 }
		}
		else
		{
		$errors[] = "Error: Please enter valid credentials.";
		}
	} else {
		$errors[] = "Error: User does not exist.";
	  }		
}
					 
					 

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Cosmix 1.0 | The Cosmos of books</title>
		<link rel="stylesheet" href="/static/css/login.css">
		<link rel="icon" href="/static/img/favicon.ico">
		<link href="https://fonts.googleapis.com/css?family=Chakra+Petch" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Audiowide" rel="stylesheet">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	</head>
	<body onload="<?php if(!empty($errors))  {
	  echo "alert('";
	  foreach ($errors as $e) {
	    echo "$e";
	    }
	  echo "');";	
	   }?>">
	    <section class="animation-box">
			<h1>COSMIX 1.0</h1>
			<h2>The Cosmos of Books</h2>
		</section>
		<div align="center">
			<div id="login_box">
				<form action="" method="post" autocomplete="off">
					<input type="text" name="usr_email">
					<br><br>
					<input type="password" name="pwd"><br>
					<input name="remember" type="checkbox" id="remember" value="1">
					<span style="color: #fff">Stay logged in</span>
					<br><br>
					<input type="submit" value="Login" name="doLogin">
				</form>
			</div>
		</div>
		<footer>
			<hr>
			<p>Efforts By:</p>
			<p>Tanmay Chopra | Sanah Malik (St. Mark's Sr. Sec. Public School, Meera Bagh)</p>
			<p>Special Thanks: Mr. Naveen Gupta</p>
		</footer>
	</body>
</html>