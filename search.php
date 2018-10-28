<!DOCTYPE html>
<html>
	<head>
		<style>
			@import url('https://fonts.googleapis.com/css?family=Chakra+Petch');
			table
			{
				width: 90%;
			}
			table, td, th
			{
				border-radius: 15px;
			}
			td, th
			{
				padding: 15px 15px 15px 15px;
				font-family: 'Chakra Petch', sans-serif;
			}
		</style>
	</head>
<?php 
include 'config.php';
page_protect();

if(empty($_GET['q']) || empty($_GET['q'])) die("Invalid Request.");
$search_q = mysqli_real_escape_string($link, $_GET['q']);
if($_GET['type'] == 'year')
	$result = mysqli_query($link, "SELECT * FROM books WHERE $_GET[type] = $search_q;");
else
	$result = mysqli_query($link, "SELECT * FROM books WHERE $_GET[type] LIKE '%$search_q%';");
if(mysqli_num_rows($result) == 0) die("No records found.");
	
?>
<body>
<div align="center">
	<table border="1">
			<tr>
				<th>Book ID</th>
				<th>Book Name</th>
				<th>Status</th>
				<th>Expected Date of Return</th>
			<tr>
			<?php
				while($row = mysqli_fetch_array($result))
				{
					echo "<tr>";
						echo "<td>".$row['book_id']."</td>";
						echo"<td>".$row['book_name']."</td>";
						if($row['status'] == 1)
						{
							echo "<td>Unavailable</td>";
							echo "<td>".date('d-m-Y', strtotime($row['dor']))."</td>";
						}
						else if(!$row['status'])
						{
							echo "<td>Available</td>";
							echo "<td>N/A</td>";
						}
					echo "</tr>";
				}
			?>
	</table>
</div>
</body>
</html>