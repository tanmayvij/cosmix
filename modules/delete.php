<!DOCTYPE html>
<html>
	<head>
		<link rel='stylesheet' href='/static/css/module.css'>
	</head>
	<body>
<?php
include '../config.php';
if(!isset($_GET['id']) || empty($_GET['id']))
{
	die("Invalid Request.<br><a href='records.php'>Back</a>");
}
$result = mysqli_query($link, "Select book_id from books where book_id = $_GET[id]");
if(mysqli_num_rows($result) == 0)
{
	die("Invalid Book ID.<br><a href='records.php'>Back</a>");
}
mysqli_query($link, "DELETE FROM books where book_id = $_GET[id]");
die("Deleted successfully.<br><a href='records.php'>Back</a>");
?>