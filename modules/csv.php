<?php

require '../config.php';
	page_protect();
	if(!checkAdmin())
		header('Location: /user_dashboard.php');

if($_GET['type']!=='import' && $_GET['type'] !== 'export')
	die("Invalid request");

else if($_GET['type']==='import')
{
	if(isset($_POST['submit']))
	{
		$csv_types = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
		
		if(!empty($_FILES['csv_file']['name']) && in_array($_FILES['csv_file']['type'],$csv_types))
		{
			if(is_uploaded_file($_FILES['csv_file']['tmp_name']))
			{
				$file = fopen($_FILES['csv_file']['tmp_name'], 'r');
				//while(!is_numeric($line[0]))
				//	fgetcsv($file);
				//fgetcsv($file);
				$ctr = 0;
				while(($line = fgetcsv($file)) !== FALSE)
				{
					if(is_numeric($line[0]))
					{
						if(empty($line[2]))
						{
							$uname = 'NULL';
						}
						else
						{
							$uname = "'" . $line[2] . "'";
						}
						if(empty($line[4]))
						{
							$doi = 'NULL';
						}
						else
						{
							$doi = "'" . date('Y-m-d', strtotime($line[4])) . "'";
						}
						if(empty($line[5]))
						{
							$dor = 'NULL';
						}
						else
						{
							$dor = "'" . date('Y-m-d', strtotime($line[5])) . "'";
						}
						$result = mysqli_query($link, "SELECT * FROM books WHERE book_id = $line[0]");
						if(mysqli_num_rows($result) > 0)
						{
							mysqli_query($link, "UPDATE `books` SET `book_name`='$line[1]',`username`=$uname,
							`status`=$line[3],`doi`=$doi,`dor`=$dor, `publisher`='$line[6]', `cost`=$line[7], `year`=$line[8],
							`language`='$line[9]', `author`='$line[10]' WHERE book_id = $line[0];");
						}
						else
						{
							
							mysqli_query($link, "INSERT INTO `books` VALUES
							($line[0], '$line[1]', $uname, $line[3], $doi, $dor, '$line[6]', $line[7], $line[8], '$line[9]', '$line[10]');");
						}
						if(empty(mysqli_error($link)))
							$ctr++;
						else
						{
							die("Unexpected error occurred. The CSV file might be corrupted. Please try again. " . $ctr . " Records successfully imported.<br><a href='csv.php?type=import'>Back</a>");
						}
					}
				}
				fclose($file);
				
				echo $ctr . " Records successfully imported.<br><a href='csv.php?type=import'>Back</a>";
			}
			else
			{
				echo "Unexpected error occurred. Please try again.<br><a href='csv.php?type=import'>Back</a>";
			}
		}
		else
		{
			echo "Error: Please upload a valid CSV file.<br><a href='csv.php?type=import'>Back</a>";
		}
	}
	else
	{
		echo "
		<!DOCTYPE html>
		<html>
		<head>
			<link rel='stylesheet' href='/static/css/module.css'>
		</head>
		<body>
			<h2>Upload a CSV file to import book records</h2><br><br>
			<div align='center'>
				<form method='post' action='' enctype='multipart/form-data'>
					<input type='file' name='csv_file' required><br><br>
					<input type='submit' value='Upload' name='submit'>
				</form>
			</div>
		</body>
		</html>
		";
	}
}

else if($_GET['type'] === 'export')
{
	echo "
	<!DOCTYPE html>
		<html>
		<head>
			<link rel='stylesheet' href='/static/css/module.css'>
		</head>
		<body>
			<div align='center'>
				<h2>Export book records to CSV file</h2><br>
				<button onclick='window.open(\"export_script.php\", \"_blank\")'>Export</button><br>
			</div>
		</body>
		</html>			
	";	
}

?>
