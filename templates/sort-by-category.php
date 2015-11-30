<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="stylesheets/startpage.css">
	</head>
	<body>
		<main>
			<!--
			<div class="product-wrapper">
				<img src="res/vara.jpg">
				<h2>Namn på vara</h2>
				<p>Jmf.pris 49,95 kr/kg</p>
				<div class="info">
					<div class="info-inner">
						<p class="item-price">5,99 kr</p>
						<p>120g</p>
					</div>
					<div class="buy">
						<form name="purchase-item">
							<input class="quantity" type="number" name="quantity" min="1" max="99" value="1">
							<p>st</p>
							<input class="buy-button" type="submit" value="Köp">
						</form>
					</div>
				</div>
			</div>
			-->
			<?php
				echo '<h1 style="margin-bottom: 50px;">Sorterar efter varor i kategorin \'' . $_GET['sort_by'] . '\'</h1>';
				$sort_by = $_GET['sort_by'];
				foreach($pdo->query("SELECT * FROM products WHERE category LIKE '$sort_by'") as $row){
					echo '<a href="index.php?page=product&id=' . $row['id'] . '"><div class="product-wrapper">';

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

					echo '
							<a href="index.php?page=product&id=' . $row['id'] . '"><img src="' . $row['image'] . '"></a>
							<a href="index.php?page=product&id=' . $row['id'] . '"><h2>' . $row['name'] . '</h2></a>';

					if($row['comparement_price'] != "" || $row['comparement_price'] != 0){
						echo '
							<p>Jmf.pris ' . $row['comparement_price'] . " " . $row['comparement_type'] . '</p>
						';
					}

					echo '
							<div class="info">
								<div class="info-inner">
									<p class="item-price">' . $row['price'] . ' kr</p>
								</div>
								<div class="buy">
									<form name="purchase-item">
										<input class="quantity" type="number" name="quantity" min="1" max="99" value="1">
										<p>st</p>
										<input class="buy-button" type="submit" value="Köp">
									</form>
								</div>
							</div>
						</div></a>
					';
				}
			?>
		</main>
		<script type="text/javascript">
			function confirm_product_delete(){
				return confirm("Är du säker på att du vill ta bort denna produkten från sortimentet?");
			}
		</script>
	</body>
</html>