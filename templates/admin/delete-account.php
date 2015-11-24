<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="stylesheets/admin/add-category.css">
	</head>
	<body>
		<main>
			<h1>Ta bort ett användarkonto från databasen</h1>
			<form method="POST" action="index.php?page=admin&action=delete_account">
				<select class="text-input" name="id" required>
					<option value="" disabled selected>Välj konto att radera</option>
				<?php
					foreach($pdo->query("SELECT * FROM users WHERE access_level NOT LIKE 'admin'") as $row){
						echo '<option value="' . $row['id'] . '">' . $row['username'] . ' - ' . $row['surname'] . ' ' . $row['lastname'] . '</option>';
					}
				?>
				</select>

				<input class="add" type="submit" value="Radera">
			</form>
		</main>
	</body>
</html>