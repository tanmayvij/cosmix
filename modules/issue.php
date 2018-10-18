<?php
include '../config.php';
page_protect();
if(!checkAdmin())
	header('Location: /user_dashboard.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel='stylesheet' href='/static/css/module.css'>
	</head>
<?php
if(isset($_POST['submit']))
{
	// Check if the specified user ID is valid
	$result = mysqli_query($link, "SELECT full_name FROM users WHERE user_name = '$_POST[user]'");
	if(mysqli_num_rows($result) == 0)
	{
		die("Error: The specified user doesn't exist.<br><a href='issue.php'>Back</a>");
	}
	list($user_name) = mysqli_fetch_row($result);
	
	// Check if the book is available
	$result = mysqli_query($link, "SELECT status FROM books WHERE book_id = $_POST[book];");
	if(mysqli_num_rows($result) == 0)
	{
		die("Error: The specified book doesn't exist.<br><a href='issue.php'>Back</a>");
	}
	list($status) = mysqli_fetch_row($result);
	if($status == 1)
	{
		$result = mysqli_query($link, "SELECT username, dor FROM books WHERE book_id = $_POST[book];");
		list($user, $dor) = mysqli_fetch_row($result);
		die("Error: The specified book has already been issued to " . $user . ". Expected Return Date: " . $dor . "<br><a href='issue.php'>Back</a>");
	}
	
	// Check if the user has been already issued a book
	$result = mysqli_query($link, "SELECT * FROM books WHERE username = '$_POST[user]';");
	if(mysqli_num_rows($result) > 0)
	{
		die("Error: The specified user has already been issued a book.<br><a href='issue.php'>Back</a>");
	}
	
	// Issue the book
	mysqli_query($link,
	"UPDATE books SET status=1, doi=now(), dor=DATE_ADD(CURDATE(), INTERVAL " . days_allowed . " DAY), username='$_POST[user]' WHERE book_id = $_POST[book]");
	$result = mysqli_query($link, "SELECT book_name FROM books WHERE book_id = $_POST[book];");
	list($book_name) = mysqli_fetch_row($result);
	die ("Success! " . $book_name . " successfully issued to " . $user_name . " for " . days_allowed . " days.<br><a href='issue.php'>Back</a>");
}
?>
<body>
	<h2>Issue a Book</h2>
	<div align="center">
		<form action="" method="post">
			<input type="text" name="book" placeholder="Book ID (Type or Scan barcode...)" required><br><br>
			<input type="text" name="user" placeholder="User ID (Type or Scan barcode...)" required><br><br>
			<input type="submit" value="Issue" name="submit">
		</form>
	</div>
</html>