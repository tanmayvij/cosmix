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
if(isset($_POST['book_name']))
{
	mysqli_query($link,
	"UPDATE books SET book_name = '$_POST[book_name]', publisher = '$_POST[publisher]', cost = $_POST[cost],
	year = $_POST[year], language = '$_POST[language]', author = '$_POST[author]' where book_id = $_GET[id]");
	die("Modified successfully.<br><a href='records.php'>Back</a>");
}
else
{
	echo '<div align="center">
		<form action="" method="post">
			<input type="text" name="book_name" placeholder="New Book Name..." required><br>
			<input type="text" name="publisher" placeholder="Publisher..." required><br>
				<input type="number" name="cost" placeholder="Cost..." required><br>
				<input type="number" name="year" placeholder="Year of Publishing..." required><br>
				<input type="text" name="language" placeholder="Language..." required><br>
				<input type="text" name="author" placeholder="Author..." required><br>
			<input type="submit" name="submit" value="Update"><br>
		</form>
	</div>
	';
}
?>