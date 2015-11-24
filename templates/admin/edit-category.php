<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="stylesheets/admin/add-category.css">
	</head>
	<body>
		<main>
			<h1>Redigera en produktkategori</h1>
			<form method="POST" action="index.php?page=admin&action=edit_category">
				<select class="text-input" name="id">
					<option value="" disabled selected>Välj produktkategori att redigera</option>
					<?php
						foreach($pdo->query("SELECT * FROM categories") as $row){
							echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
						}
					?>
				</select>
				<input class="text-input" type="text" name="name" placeholder="Nytt namn.." required>

				<input class="add" type="submit" value="Redigera">
			</form>
		</main>
	</body>
</html>