<?php 
include '../config.php';
$link = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
session_start();
if(!checkAdmin()) {
header("Location: login.php");
exit();
}

$ret = $_SERVER['HTTP_REFERER'];

foreach($_GET as $key => $value) {
	$get[$key] = filter($value);
}

if($get['cmd'] == 'approve')
{
mysqli_query($link,"update users set approved='1' where id='$get[id]'") or die(mysqli_error($link));
$rs_email = mysqli_query("select user_email from users where id='$get[id]'") or die(mysqli_error($link));
list($to_email) = mysqli_fetch_row($rs_email);

$host  = $_SERVER['HTTP_HOST'];
$host_upper = strtoupper($host);
$login_path = @ereg_replace('admin','',dirname($_SERVER['PHP_SELF']));
$path   = rtrim($login_path, '/\\');


 echo "Active";


}

if($get['cmd'] == 'ban')
{
mysqli_query($link,"update users set banned='1' where id='$get[id]' and `user_name` <> 'admin'");

echo "yes";
exit();

}

if($get['cmd'] == 'edit')
{
$rs_usr_duplicate = mysqli_query($link,"select count(*) as total from `users` where `user_name`='$get[user_name]' and `id` != '$get[id]'") or die(mysqli_error($link));
list($usr_total) = mysqli_fetch_row($rs_usr_duplicate);
	if ($usr_total > 0)
	{
	echo "Sorry! user name already registered.";
	exit;
	} 
$rs_eml_duplicate = mysqli_query($link,"select count(*) as total from `users` where `user_email`='$get[user_email]' and `id` != '$get[id]'") or die(mysqli_error($link));
list($eml_total) = mysqli_fetch_row($rs_eml_duplicate);
	if ($eml_total > 0)
	{
	echo "Sorry! user email already registered.";
	exit;
	}
mysqli_query($link,"
update users set  
`user_name`='$get[user_name]', 
`user_email`='$get[user_email]',
`user_level`='$get[user_level]'
where `id`='$get[id]'") or die(mysqli_error($link));

if(!empty($get['pass'])) {
$hash = PwdHash($get['pass']);
mysqli_query("update users set `pwd` = '$hash' where `id`='$get[id]'") or die(mysqli_error($link));
}

echo "Updated Successfully";
exit();
}

if($get['cmd'] == 'unban')
{
mysqli_query($link,"update users set banned='0' where id='$get[id]'");
echo "no";

}


?>