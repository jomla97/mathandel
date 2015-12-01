<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="stylesheets/admin/add-category.css">
		<script type="text/javascript">
			function confirmadminpriviliges(){
				return confirm("Är du säker på att du vill göra detta adminkonto till ett vanligt konto?");
			}
		</script>
	</head>
	<body>
		<main>
			<h1>Gör ett konto till admin</h1>
			<form method="POST" action="index.php?page=admin&action=delete_admin_priviliges">
				<select class="text-input" name="id" required>
					<option value="" disabled selected>Välj konto att göra till admin</option>
					<?php
						foreach($pdo->query("SELECT * FROM users WHERE access_level LIKE 'admin' AND status LIKE 'active'") as $row){
							if($row['username'] != $_SESSION['username']){
								echo '<option value="' . $row['id'] . '">' . $row['username'] . ' - ' . $row['surname'] . ' ' . $row['lastname'] . '</option>';
							}
						}
					?>
				</select>

				<input onclick="return confirmadminpriviliges()" class="add" type="submit" value="Uppdatera">
			</form>
		</main>
	</body>
</html>