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
	// Check if the book is available
	$result = mysqli_query($link, "SELECT status FROM books WHERE book_id = $_POST[book];");
	if(mysqli_num_rows($result) == 0)
	{
		die("Error: The specified book doesn't exist.<br><a href='return.php'>Back</a>");
	}
	list($status) = mysqli_fetch_row($result);
	if($status == 0)
	{
		die("Error: The specified book has not been issued yet.<br><a href='return.php'>Back</a>");
	}
	
	// Return the book
	$result = mysqli_query($link, "SELECT book_name, doi, dor, username FROM books WHERE book_id = $_POST[book];");
	list($book_name, $doi, $dor, $user_id) = mysqli_fetch_row($result);
	$result = mysqli_query($link, "SELECT full_name FROM users WHERE user_name = '$user_id';");
	list($user_name) = mysqli_fetch_row($result);
	$issue_interval = date_diff(date_create(date('Y-m-d')), date_create($doi))->format('%a days');
	$return_interval = date_diff(date_create(date('Y-m-d')), date_create($dor))->format('%a');
	if(strtotime($dor) < time())
	{
		$days = "<span style='color: red'>" . $issue_interval . "</span>. Please collect a fine of INR " . ($return_interval * fine_perday);
	}
	else
	{
		$days = $issue_interval;
	}
	echo ($book_name . " successfully returned by " . $user_name . " after " . $days . ".<br><a href='return.php'>Back</a>");
	mysqli_query($link,
	"UPDATE books SET status=0, doi=NULL, dor=NULL, username=NULL WHERE book_id = $_POST[book]");
}
?>
<body>
	<h2>Return a Book</h2>
	<div align="center">
		<form action="" method="post">
			<input type="text" name="book" placeholder="Book ID (Type or Scan barcode...)" required><br><br>
			<input type="submit" value="Return" name="submit">
		</form>
	</div>
</html>