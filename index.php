<?php
include 'config.php';
page_protect();
if(!checkAdmin())
	header('Location: user_dashboard.php');
?>
<!DOCTYPE html>
<html>
	<head>

		<title>Cosmix 1.0 | The Cosmos of books</title>
		<link rel="stylesheet" href="/static/css/dashboard.css">
		<link rel="stylesheet" href="/static/css/modal.css">		
		<link rel="stylesheet" href="/static/css/elegant-icon.css">
		<link rel="icon" href="/static/img/favicon.ico">
		<link href="https://fonts.googleapis.com/css?family=Chakra+Petch" rel="stylesheet">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	</head>

	<body>
		<header>
			<h1>Welcome <?php echo $_SESSION['user_name'];?></h1>
			<ul>
				<li><i class="icon_profile"></i>
					<span>
						<?php
							$result = mysqli_query($link, "select * from users where user_level = 1;");
							echo mysqli_num_rows($result);
						?> Users Registered
					</span>
				</li>
				<li><i class="icon_book"></i>
				<?php
					$result = mysqli_query($link, "select * from books where status=1;");
					echo mysqli_num_rows($result);
				?> Books Issued</li>
				<li><i class="icon_error-triangle"></i>
				<?php
					$result = mysqli_query($link, "select * from books where dor < now();");
					echo mysqli_num_rows($result);
				?> Books Overdue</li>
			</ul>
			<hr>
		</header>
		<div id="tiles" align="center">
			<table>
				<tr>
					<td>
						<button class="popup_link"><img src="/static/img/tiles/records.png"></button><br>
						Manage Books
					</td>
					<td>
						<button class="popup_link"><img src="/static/img/tiles/import.png"></button><br>
						Import data from CSV
					</td>
					<td>
						<button class="popup_link"><img src="/static/img/tiles/export.png"></button><br>
						Export data to CSV
					</td>
					<td>
						<button class="popup_link"><img src="/static/img/tiles/issue.png"></button><br>
						Issue Book
					</td>
				</tr>
				<tr>
					<td>
						<button class="popup_link"><img src="/static/img/tiles/return.png"></button><br>
						Return Book
					</td>
					<td>
						<button class="popup_link"><img src="/static/img/tiles/user_panel.png"></button><br>
						Manage Customers
					</td>
					<td>
						<button class="popup_link"><img src="/static/img/tiles/profile.png"></button><br>
						My Profile
					</td>
					<td>
						<button onclick="window.location.href='logout.php'"><img src="/static/img/tiles/logout.png"></button><br>
						Logout
					</td>
				</tr>
			</table>
		</div>
		<div align="center">
				<div id="popup_box_0" class="modal">
					<div class="modal-content">
						<span class="close">&times;</span>
						<iframe src="modules/records.php"></iframe>
					</div>
				</div>
			</div>

			<div align="center">
				<div id="popup_box_1" class="modal">
					<div class="modal-content">
						<span class="close">&times;</span>
						<iframe src="modules/csv.php?type=import"></iframe>
					</div>
				</div>
			</div>

				<div align="center">
				<div id="popup_box_2" class="modal">
					<div class="modal-content">
						<span class="close">&times;</span>	
						<iframe src="modules/csv.php?type=export"></iframe>	
						</div>
				</div>
				</div>

				<div align="center">
				<div id="popup_box_3" class="modal">
					<div class="modal-content">
						<span class="close">&times;</span>
						<iframe src="modules/issue.php"></iframe>	
					</div>
				</div>
				</div>

				<div align="center">
				<div id="popup_box_4" class="modal">
					<div class="modal-content">
						<span class="close">&times;</span>
						<iframe src="modules/return.php"></iframe>	
					</div>
				</div>
				</div>

				<div align="center">
				<div id="popup_box_5" class="modal">
					<div class="modal-content">
						<span class="close">&times;</span>
						<iframe src="modules/user_control.php"></iframe>
					</div>
				</div>
				</div>

				<div align="center">
				<div id="popup_box_6" class="modal">
					<div class="modal-content">
						<span class="close">&times;</span>
						<iframe src="modules/profile.php"></iframe>	
					</div>
				</div>
				</div>
		
		<script type="text/javascript" src="/static/js/modal.js"></script>
    </body>
</html>

	