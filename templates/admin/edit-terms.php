<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="stylesheets/admin/add-category.css">
	</head>
	<body>
		<main>
			<h1>Redigera 'allmänna villkor'</h1>
			<form method="POST" action="index.php?page=admin&action=edit_terms">
				<?php
					foreach($pdo->query("SELECT * FROM website_info WHERE slug LIKE 'terms'") as $row){
						echo '<textarea class="text-input" class="textarea" type="text" name="text" placeholder="Om oss...">' . $row['text'] . '</textarea>';
					}
				?>

				<input class="add" type="submit" value="Redigera">
			</form>
		</main>
	</body>
</html>