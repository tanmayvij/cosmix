<?php 
include 'config.php';
page_protect();
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
		<style>
			button
			{
				float: right;
				background: none;
				border:none;
				cursor: pointer;
			}
			button:focus
			{
				outline: none;
			}
		</style>
	</head>

	<body>
		<div align="center">
			<header>
				<h1>Welcome <?php echo $_SESSION['user_name'];?></h1>
				<button title="Logout" onclick="window.location.href='logout.php'">
					<img src="/static/img/tiles/logout.png">
				</button>
				<input type="text" name="q" placeholder="Search the library..." id="search_field"><br>
				<span style="font-weight: bold; font-size: 20px;">Search By:</span><br>
				<input type="radio" name="type" value="book_name" checked> Book Name
				<input type="radio" name="type" value="publisher"> Publisher
				<input type="radio" name="type" value="author"> Author
				<input type="radio" name="type" value="language"> Language
				<input type="radio" name="type" value="year"> Year of Publishing
				<hr>
			</header>
			<div id="stat">
				<?php
					$result = mysqli_query($link, "Select book_id, book_name, doi, dor from books where username = '$_SESSION[username]'");
					if(mysqli_num_rows($result) == 0)
					{
						echo "<p>You have not issued any books.</p>";
					}
					else
					{
						list($book_id, $book_name, $doi, $dor) = mysqli_fetch_row($result);
						echo "<h2>Books Issued:</h2>";
						echo "
						<table>\n
							<tr>\n
								<th>Book ID</th>\n
								<th>Book Name</th>\n
								<th>Date of Issue</th>\n
								<th>Due Date of Return</th>\n
							</tr>\n
							<tr>\n
								<td>".$book_id."</td>\n
								<td>".$book_name."</td>\n
								<td>".date('d-m-Y', strtotime($doi))."</td>\n";
								if(strtotime($dor) < time())
									echo "<td style='color: #FF0000'>".date('d-m-Y', strtotime($dor))."</td>\n";
								else
									echo "<td>".date('d-m-Y', strtotime($dor))."</td>\n";
							echo "</tr>\n
						</table>
						";					
					}
				?>
			</div>
			<div id="popup_box" class="modal">
				<div class="modal-content">
					<span class="close" id="close">&times;</span>
					<iframe src="" id="iframe"></iframe>
				</div>
			</div>
		</div>
		<script>
			var modal = document.getElementById('popup_box');
			var field = document.getElementById('search_field');
			var span = document.getElementById('close');
			var iframe = document.getElementById('iframe');
			
			field.addEventListener("keyup", function(event) {
				event.preventDefault();
				if (event.keyCode === 13) {
					iframe.setAttribute('src', 'search.php?q=' + document.getElementById('search_field').value + '&type=' + $("input[type='radio'][name='type']:checked").val());
					modal.style.display = "block";
				}
			});
			span.onclick = function()
			{
				modal.style.display= "none";
			}
			window.onclick = function(event) {
				if (event.target == modal)
					modal.style.display = "none";
			}
		</script>
    </body>
</html>