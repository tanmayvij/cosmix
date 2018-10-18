<!DOCTYPE html>
<html>
	<head>
		<link rel='stylesheet' href='/static/css/module.css'>
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
	mysqli_query($link, "INSERT INTO books VALUES($_POST[book_id], '$_POST[book_name]', NULL, 0, NULL, NULL)");
	echo "Added successfully.";
}
if(isset($_GET['q']))
{
	$search_q = mysqli_real_escape_string($link, $_GET['q']);
	$result = mysqli_query($link, "SELECT * FROM books WHERE book_name LIKE '%$search_q%';");
	if(mysqli_num_rows($result) == 0) die("No records found.<br><a href='records.php'>Back</a>");
	
?>
<div align="center">
	<table border="1">
			<tr>
				<th>Book ID</th>
				<th>Book Name</th>
				<th>Status</th>
				<th>Issued to</th>
				<th>Date of Issue</th>
				<th>Expected Date of Return</th>
				<th>Delete Book</td>
				<th>Modify</th>
			<tr>
			<?php
				while($row = mysqli_fetch_array($result))
				{
					echo "<tr>";
						echo "<td>".$row['book_id']."</td>";
						echo"<td>".$row['book_name']."</td>";
						if($row['status'] == 1)
						{
							if(strtotime($row['dor']) < time())
								echo "<td style='color: #FF0000'>Overdue</td>";
							else
								echo "<td>Issued</td>";
							echo "<td>".$row['username']."</td>";
							echo "<td>".date('d-m-Y', strtotime($row['doi']))."</td>";
							echo "<td>".date('d-m-Y', strtotime($row['dor']))."</td>";
						}
						else if(!$row['status'])
						{
							echo "<td>Available</td>";
							echo "<td>N/A</td>";
							echo "<td>N/A</td>";
							echo "<td>N/A</td>";
						}
						echo "<td><button onclick='window.location.replace(\"delete.php?id=".$row['book_id']."\")'><img src='/static/img/delete.png'></button></td>";
						echo "<td><button onclick='window.location.replace(\"modify.php?id=".$row['book_id']."\")'><img src='/static/img/modify.png'></button></td>";
					echo "</tr>";
				}
			?>
	</table>
	<br><a href='records.php'>Back</a>
</div>
<?php
}
else {
?>

		<div align="center">
			<form method="get" action="">
				<input type="text" name="q" placeholder="Search for a book..." id="search_field" required>
			</form>
			<button onclick="list()">List all</button>
			<form method="get" action="" id="list_form">
				<input type="hidden" name="q">
			</form>
			<hr>
			<h2>Add a book record</h2>
			<form method="post" action="">
				<input type="text" name="book_id" placeholder="Book ID (Type or Scan barcode...)" required><br>
				<input type="text" name="book_name" placeholder="Book Name..." required><br>
				<input type="submit" name="add_submit" value="Add"><br>
			</form>
			<hr>
		</div>
		<script>
			function list()
			{
				document.getElementById("list_form").submit();
			}
		</script>
<?php
}
?>