<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="stylesheets/admin/add-category.css">
		<script type="text/javascript">
			function confirmadminpriviliges(){
				return confirm("Är du säker på att du vill göra detta användarkonto till en admin? Detta går inte att ångra.");
			}
		</script>
	</head>
	<body>
		<main>
			<h1>Gör ett konto till admin</h1>
			<form method="POST" action="index.php?page=admin&action=grant_admin_priviliges">
				<select class="text-input" name="id" required>
					<option value="" disabled selected>Välj konto att göra till admin</option>
					<?php
						foreach($pdo->query("SELECT * FROM users WHERE access_level NOT LIKE 'admin' AND status LIKE 'active'") as $row){
							echo '<option value="' . $row['id'] . '">' . $row['username'] . ' - ' . $row['surname'] . ' ' . $row['lastname'] . '</option>';
						}
					?>
				</select>

				<input onclick="return confirmadminpriviliges()" class="add" type="submit" value="Uppdatera">
			</form>
		</main>
	</body>
</html>