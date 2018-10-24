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

if(isset($_GET['q']))
{
	if(empty($_GET['type'])) die("Invalid Request.");
	$search_q = mysqli_real_escape_string($link, $_GET['q']);
	if($_GET['type'] == 'year' || $_GET['type'] == 'book_id')
	$result = mysqli_query($link, "SELECT * FROM books WHERE $_GET[type] = $search_q;");
	else
		$result = mysqli_query($link, "SELECT * FROM books WHERE $_GET[type] LIKE '%$search_q%';");
	if(mysqli_num_rows($result) == 0) die("No records found.<br><a href='records.php'>Back</a>");
	
?>
<div align="center">
	<table border="1">
			<tr>
				<th>Book ID</th>
				<th>Book Name</th>
				<th>Publisher</th>
				<th>Year of Publishing</th>
				<th>Language</th>
				<th>Author</th>
				<th>Cost</th>
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
						echo"<td>".$row['publisher']."</td>";
						echo"<td>".$row['year']."</td>";
						echo"<td>".$row['language']."</td>";
						echo"<td>".$row['author']."</td>";
						echo"<td>INR ".$row['cost']."</td>";
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
	<br><br><br>
</div>
<?php
}
else {
?>

		<div align="center">
			<form method="get" action="">
				<input type="text" name="q" placeholder="Search for a book..." id="search_field" required><br>
				<span style="font-weight: bold; font-size: 20px;">Search By:</span><br>
				<input type="radio" name="type" value="book_id" checked> Book ID
				<input type="radio" name="type" value="book_name"> Book Name
				<input type="radio" name="type" value="publisher"> Publisher
				<input type="radio" name="type" value="author"> Author
				<input type="radio" name="type" value="language"> Language
				<input type="radio" name="type" value="year"> Year of Publishing
				<input type="radio" name="type" value="username"> Issued to (User ID)
			</form>
		</div>
<?php
}
?>