<?php
	include '../config.php';
	page_protect();
	if(!checkAdmin())
		header('Location: /user_dashboard.php');
	$result = mysqli_query($link, "SELECT * FROM books");
	$books = array();
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$books[] = $row;
		}
	}
	$file = fopen('php://output', 'w');
	fputcsv($file, array('Book ID', 'Book Name', 'Username', 'Status', 'Date of Issue', 'Date of Return', 'Publisher', 'Cost', 'Year', 'Language', 'Author'));
	if (count($books) > 0) {
		foreach ($books as $row1) {
			fputcsv($file, $row1);
		}
	}
	fclose($file);
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=Books.csv');
?>