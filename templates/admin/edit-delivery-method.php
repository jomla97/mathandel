<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="stylesheets/admin/add-category.css">
	</head>
	<body>
		<main>
			<h1>Redigera 'hur levererar vi?' sidan</h1>
			<form method="POST" action="index.php?page=admin&action=edit_delivery_method">
				<?php
					foreach($pdo->query("SELECT * FROM website_info WHERE slug LIKE 'delivery_method'") as $row){
						echo '<textarea class="text-input" class="textarea" type="text" name="text" placeholder="Om oss...">' . $row['text'] . '</textarea>';
					}
				?>

				<input class="add" type="submit" value="Redigera">
			</form>
		</main>
	</body>
</html>