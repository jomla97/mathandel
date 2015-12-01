<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="stylesheets/startpage.css">
	</head>
	<body>
		<main>
			<?php
				$page = $_GET['page'];
				foreach($pdo->query("SELECT * FROM website_info WHERE slug LIKE '$page'") as $row){
					echo '<h1>' . $row['title'] . '</h1><p>' . $row['text'] . '</p>';
				}
			?>
		</main>
	</body>
</html>