<?php

if(isset($_POST['submit']))
{
	include 'functions.php';
	$file = fopen("conn.php", "w");
	$text = '
	<?php
	define ("DB_HOST", "'.$_POST['db_host'].'");
	define ("DB_USER", "'.$_POST['db_user'].'");
	define ("DB_PASS", "'.$_POST['db_pwd'].'");
	define ("DB_NAME", "'.$_POST['db_name'].'");
	
	define("days_allowed", '.$_POST['days'].');
	define("fine_perday", '.$_POST['fine'].');
	?>
	';
	fwrite($file, $text);
	fclose($file);
	$pass = PwdHash($_POST['admin_pwd']);
	$link = mysqli_connect($_POST['db_host'], $_POST['db_user'], $_POST['db_pwd']) or die("Error: Invalid database credentials");
	$query = "
	CREATE DATABASE IF NOT EXISTS `".$_POST['db_name']."`;";
	mysqli_query($link, $query) or die(mysqli_error($link));
	$db = mysqli_select_db($link, $_POST['db_name']);
	$admin_address = mysqli_real_escape_string($link, $_POST['admin_address']);
	$query = "
	CREATE TABLE `users` (
  `id` bigint(20) NOT NULL auto_increment,
  `md5_id` varchar(200) collate latin1_general_ci NOT NULL default '',
  `full_name` tinytext collate latin1_general_ci NOT NULL,
  `user_name` varchar(200) collate latin1_general_ci NOT NULL default '',
  `user_email` varchar(220) collate latin1_general_ci NOT NULL default '',
  `user_level` tinyint(4) NOT NULL default '1',
  `pwd` varchar(220) collate latin1_general_ci NOT NULL default '',
  `address` text collate latin1_general_ci NOT NULL,
  `country` varchar(200) collate latin1_general_ci NOT NULL default '',
  `tel` varchar(200) collate latin1_general_ci NOT NULL default '',
  `fax` varchar(200) collate latin1_general_ci NOT NULL default '',
  `website` text collate latin1_general_ci NOT NULL,
  `date` date NOT NULL default '0001-01-01',
  `users_ip` varchar(200) collate latin1_general_ci NOT NULL default '',
  `approved` int(1) NOT NULL default '0',
  `activation_code` int(10) NOT NULL default '0',
  `banned` int(1) NOT NULL default '0',
  `ckey` varchar(220) collate latin1_general_ci NOT NULL default '',
  `ctime` varchar(220) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `user_email` (`user_email`),
  FULLTEXT KEY `idx_search` (`full_name`,`address`,`user_email`,`user_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=55 ;
";
mysqli_query($link, $query) or die(mysqli_error($link));
$query = "
INSERT INTO `users` VALUES (54, 'a684eceee76fc522773286a895bc8436', '$_POST[admin_name]', 'admin', '$_POST[admin_email]', 5, '$pass', '$admin_address', 'India', '$_POST[admin_tel]', '', '', now(), '', 1, 0, 0, 'uqd1y4v', '1272992243');
";
mysqli_query($link, $query) or die(mysqli_error($link));
$query = "
CREATE TABLE `books` (
  `book_id` int(11) PRIMARY KEY NOT NULL,
  `username` varchar(100) NOT NULL,
  `book_name` varchar(200) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `doi` date DEFAULT NULL,
  `dor` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
";
	mysqli_query($link, $query) or die(mysqli_error($link));
	mysqli_close();
	header("Location: /");
}

?>

<!DOCTYPE html>
<html>
	<head>

		<title>Configuration | Cosmix 1.0 | The Cosmos of books</title>
		<link rel="stylesheet" href="/static/css/install.css">
		<link rel="stylesheet" href="/static/css/modal.css">		
		<link rel="stylesheet" href="/static/css/elegant-icon.css">
		<link rel="icon" href="/static/img/favicon.ico">
		<link href="https://fonts.googleapis.com/css?family=Chakra+Petch" rel="stylesheet">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	</head>
	<body>
		<h2>CONFIGURE COSMIX</h2>
		<div align="center">
			<form action="" method="post">
				<table>
					<tr>
						<td>
							<h3>Database Connectivity:</h3>
							<input type="text" name="db_host" placeholder="Host" required><br><br>
							<input type="text" name="db_user" placeholder="Username" required><br><br>
							<input type="password" name="db_pwd" placeholder="Password" required><br><br>
							<input type="text" name="db_name" placeholder="Database Name" required><br><br>
							
							<h3>Book Return Restrictions</h3>
							<input type="number" name="days" placeholder="Days allowed" required><br><br>
							<input type="number" name="fine" placeholder="Late Return Fine" required><br><br>
						</td>
						<td>
							<h3>Admin Details:</h3>
							<input type="text" name="admin_name" placeholder="Name" required><br><br>
							<input type="text" name="admin_email" placeholder="Email" required><br><br>
							<input type="password" name="admin_pwd" placeholder="Password" required><br><br>
							<input type="tel" name="admin_tel" placeholder="Phone"><br><br>
							<textarea name="admin_address" placeholder="Address"></textarea><br><br>
						</td>
					</tr>
				</table>
				<input type="submit" value="Submit" name="submit"><br><br>
			</form>
		</div>
	</body>
</html>