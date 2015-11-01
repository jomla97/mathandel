<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="stylesheets/admin/add-category.css">
	</head>
	<body>
		<main>
			<h1>Lägg till en produktkategori till databasen</h1>
			<form method="POST" action="index.php?page=admin&action=add_category">
				<input class="text-input" type="text" name="name" placeholder="Namn.." required>

				<input class="add" type="submit" value="Lägg till">
			</form>
		</main>
	</body>
</html>