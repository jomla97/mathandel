<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="stylesheets/admin/add-category.css">
		<script type="text/javascript">
		function confirmdelete(){
			return confirm("Denna kategori kan innehåll produkter. Dessa produkter kommer att läggas i kategorin: 'okänt'. Vill du fortsätta?");
		}
		</script>
	</head>
	<body>
		<main>
			<h1>Ta bort en produktkategori från databasen</h1>
			<form method="POST" action="index.php?page=admin&action=delete_category">
				<select class="text-input" name="id" required>
					<option value="" disabled selected>Välj produktkategori att radera</option>
				<?php
					foreach($pdo->query("SELECT * FROM categories") as $row){
						echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
					}
				?>
				</select>

				<input onclick="return confirmdelete()" class="add" type="submit" value="Radera">
			</form>
		</main>
	</body>
</html>