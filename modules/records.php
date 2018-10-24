<!DOCTYPE html>
<html>
	<head>
		<link rel='stylesheet' href='/static/css/module.css'>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	</head>
	<body>
<?php
include '../config.php';
page_protect();
if(!checkAdmin())
	header('Location: /user_dashboard.php');

if(isset($_POST['add_submit']))
{
	$result = mysqli_query($link, "SELECT * FROM books WHERE book_id = $_POST[book_id];");
	if(mysqli_num_rows($result) > 0) die("Book already exists.<br><a href='records.php'>Back</a>");
	mysqli_query($link,
	"INSERT INTO books VALUES($_POST[book_id], '$_POST[book_name]', NULL, 0, NULL, NULL, '$_POST[publisher]', $_POST[cost], $_POST[year], '$_POST[language]', '$_POST[author]')");
	echo "Added successfully.";
}
?>
		<div align="center">
			<button onclick="window.location.replace('admin_search.php')">Search Books</button>
			<button onclick="window.location.replace('reports.php')">List Books</button>
			<hr>
			<button onclick="$(function() {$('#add_form').slideToggle()})">Add Books</button>
			<div id="add_form" style="display: none;">
				<h2>Add a book record</h2>
				<form method="post" action="">
					<input type="text" name="book_id" placeholder="Book ID (Type or Scan barcode...)" required><br>
					<input type="text" name="book_name" placeholder="Book Name..." required><br>
					<input type="text" name="publisher" placeholder="Publisher..." required><br>
					<input type="number" name="cost" placeholder="Cost..." required><br>
					<input type="number" name="year" placeholder="Year of Publishing..." required><br>
					<input type="text" name="language" placeholder="Language..." required><br>
					<input type="text" name="author" placeholder="Author..." required><br>
					<input type="submit" name="add_submit" value="Add"><br>
				</form>
			</div>
			<br><br>
			<hr>
		</div>