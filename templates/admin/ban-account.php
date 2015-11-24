<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="stylesheets/admin/add-category.css">
		<script type="text/javascript">
			function confirmdelete(){
				return confirm("Är du säker på att du vill banna detta användarkonto? Detta går inte att ångra.");
			}
		</script>
	</head>
	<body>
		<main>
			<h1>Banna ett konto permanent</h1>
			<form method="POST" action="index.php?page=admin&action=ban_account">
				<select class="text-input" name="id" required>
					<option value="" disabled selected>Välj konto att banna</option>
					<?php
						foreach($pdo->query("SELECT * FROM users WHERE access_level NOT LIKE 'admin' AND status LIKE 'active'") as $row){
							echo '<option value="' . $row['id'] . '">' . $row['username'] . ' - ' . $row['surname'] . ' ' . $row['lastname'] . '</option>';
						}
					?>
				</select>

				<input onclick="return confirmdelete()" class="add" type="submit" value="Banna">
			</form>
		</main>
	</body>
</html>