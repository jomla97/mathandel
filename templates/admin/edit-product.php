<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="stylesheets/admin/add-product.css">
	</head>
	<body>
		<main>
			<h1>Redigera en produkt</h1>
			<form method="POST" action="index.php?page=admin&action=edit_product&id=<?php echo $_GET['id'] ?>" enctype="multipart/form-data">
				<?php
					if(isset($login_error)){
						echo '
							<div id="login-error">
								<p>'.$login_error.'</p>
							</div>
						';
					}
					$id = $_GET['id'];

					foreach($pdo->query("SELECT * FROM products WHERE id LIKE '$id'") as $row){
						echo '
							<input class="text-input" type="text" name="name" value="' . $row['name'] . '" placeholder="Namn på varan.." required>
							<input class="text-input" type="text" name="contents" value="' . $row['contents'] . '" placeholder="Innehållsförteckning.." required>
							<input class="text-input" type="text" name="amount" value="' . $row['amount'] . '" placeholder="Mängd (volym, vikt).." required>
							<input class="text-input" type="text" name="nutriments" value="' . $row['nutriments'] . '" placeholder="Näringsvärden.." required>
							<input class="text-input" type="text" name="allergens" value="' . $row['allergens'] . '" placeholder="Allergener.." required>
							<input class="text-input" type="number" name="price" value="' . $row['price'] . '" placeholder="Pris.." required>
							<input class="text-input" type="number" name="comparement_price" value="' . $row['comparement_price'] . '" placeholder="Jämförspris..">
							
							<select placeholder="Jmf.typ" value="' . $row['comparement_type'] . '" name="comparement_type">';

							if($row['comparement_type'] == "kr/kg"){
								echo '
									<option value="" disabled>Välj jämförstyp</option>
									<option value="kr/kg" selected>kr/kg</option>
									<option value="kr/liter" >kr/liter</option>
								';
							}
							else{
								echo '
									<option value="" disabled>Välj jämförstyp</option>
									<option value="kr/kg" >kr/kg</option>
									<option value="kr/liter" selected>kr/liter</option>
								';
							}
							
							echo '
							</select>

							<select placeholder="Kategori" value="' . $row['category'] . '" name="category" required>
								<option value="" disabled>Välj kategori</option>';
						foreach($pdo->query("SELECT * FROM categories") as $row2){
							if($row['category'] == $row2['name']){
								echo '<option selected>' . $row2['name'] . '</option>';
							}
							else{
								echo '<option>' . $row2['name'] . '</option>';
							}
						}
						echo '
							</select>
						';
					}
				?>
				

				
				<a href="index.php?page=admin&action=add_category"><p>Lägg till en ny kategori</p></a>

				<input class="add" type="submit" value="Redigera">
			</form>
		</main>
	</body>
</html>