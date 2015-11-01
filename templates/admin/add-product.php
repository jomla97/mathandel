<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="stylesheets/admin/add-product.css">
	</head>
	<body>
		<main>
			<h1>Lägg till en produkt till sortimentet</h1>
			<form method="POST" action="index.php?page=admin&action=add_product" enctype="multipart/form-data">
				<?php
					if(isset($login_error)){
						echo '
							<div id="login-error">
								<p>'.$login_error.'</p>
							</div>
						';
					}
				?>
				<input class="text-input" type="text" name="name" placeholder="Namn på varan.." required>
				<input class="text-input" type="text" name="contents" placeholder="Innehållsförteckning.." required>
				<input class="text-input" type="text" name="amount" placeholder="Mängd (volym, vikt).." required>
				<input class="text-input" type="text" name="nutriments" placeholder="Näringsvärden.." required>
				<input class="text-input" type="text" name="allergens" placeholder="Allergener.." required>
				<select placeholder="Kategori" name="category" required>
					<option value="" disabled selected>Välj kategori</option>
					<?php
						foreach($pdo->query("SELECT * FROM categories") as $row){
							echo '<option>' . $row['name'] . '</option>';
						}
					?>
				</select>
				<a href="index.php?page=admin&action=add_category"><p>Lägg till en ny kategori</p></a>
				Produktbild: <input id="image-upload" type="file" name="image" required>

				<input class="add" type="submit" value="Lägg till">
			</form>
		</main>
	</body>
</html>