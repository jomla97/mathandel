<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="stylesheets/product-page.css">
	</head>
	<body>
		<main>
			<?php
				$id = $_GET['id'];
				foreach($pdo->query("SELECT * FROM products WHERE id LIKE '$id'") as $row){
					if(isset($_SESSION['admin']) && $_SESSION['admin'] == true){
					echo '
						<a onclick="return confirm_product_delete()" href="index.php?page=admin&action=delete_product&id=' . $row['id'] . '">
							<div class="product-delete-button">
								<p>X</p>
							</div>
						</a>
						<a href="index.php?page=admin&action=edit_product&id=' . $row['id'] . '">
							<div class="product-edit-button">
								<img src="res/edit-icon.png">
							</div>
						</a>
					';
					}
				}
			?>
			<div id="header">
			<?php
				foreach($pdo->query("SELECT * FROM products WHERE id LIKE '$id'") as $row){
					echo '<img id="product-image" src="' . $row['image'] . '"><h1>' . $row['name'] . '</h1>';
				}
			?>
			</div>
			<div id="info">
				<?php
					foreach($pdo->query("SELECT * FROM products WHERE id LIKE '$id'") as $row){
						echo '
							<h2>Innehåll</h2>
							<p>' . $row['contents'] . '</p>

							<h2>Mängd</h2>
							<p>' . $row['amount'] . '</p>

							<h2>Näringsvärden</h2>
							<p>' . $row['nutriments'] . '</p>

							<h2>Allergener</h2>
							<p>' . $row['allergens'] . '</p>
						';
					}
				?>
			</div>
			<div id="buy">
				<form name="purchase-item">
					<input class="text-input" type="number" name="quantity" min="1" max="99" value="1">
					<p>st</p>
					<input class="buy-button" type="submit" value="Köp">
				</form>
				<?php
					foreach($pdo->query("SELECT * FROM products WHERE id LIKE '$id'") as $row){
						echo '<h1>' . $row['price'] . ' kr</h1>';
						echo '<p id="comparement-price">Jämförspris ' . $row['comparement_price'] . $row['comparement_type'] . '</p>';
					}
				?>
			</div>
		</main>
		<script type="text/javascript">
			function confirm_product_delete(){
				return confirm("Är du säker på att du vill ta bort denna produkten från sortimentet?");
			}
		</script>
	</body>
</html>