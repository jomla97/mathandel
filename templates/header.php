<?php
	ob_start();
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Mathandel - Mat Direkt Till Hemmet</title>
		<link rel="stylesheet" type="text/css" href="stylesheets/header.css">
		<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
		<meta charset="UTF-8">
	</head>
	<body>
		<header>
			<a href="index.php"><img id="header-image" src="res/header.jpg"></a>
			<?php
				if(logged_in() == false){
					echo '<a href="index.php?page=register"><div id="register-button">Registrera</div></a>
							<a href="index.php?page=login"><div id="login-button">Logga in</div></a>';
				}
				else{
					echo '<a href="index.php?page=logout"><div id="logout-button">Logga ut</div></a>
							<a href="index.php?page=account"><div id="account-button">Mitt konto</div></a>';
				}
			?>

			<div id="search-and-wares">
				<form id="search-form" name="searchbar" method="GET">
					<input id="searchbar" type="text" name="search_query" required>
					<input id="search-button" type="submit" value="Sök">
				</form>
				<a href="index.php?page=browse"><div id="browse-button">Bläddra vårt sortiment</div></a>
			</div>

			<a href="index.php?page=basket">
				<div id="basket">
					<?php
						$pdo = pdo();
						$username = $_SESSION['username'];
						$statement = $pdo->prepare("SELECT SUM(price) FROM baskets INNER JOIN products ON baskets.item_id=products.id WHERE username LIKE '$username'");

						if($statement->execute()){
							$total = $statement->fetchAll();

							if($total[0]['SUM(price)'] > 0){
								echo 'Totalt: ' . $total[0]['SUM(price)'] . ' kr';
							}
							else{
								echo 'Totalt: 0 kr';
							}
						}
						else{
							echo '<h1>Something must have gone wrong!</h1><br>';
							print_r($statement->errorInfo());
						}
					?>
					<img id="basket-icon" src="res/basket-icon.png">
				</div>
			</a>
		</header>

		<?php
			require "templates/sorting-list.php";
		?>
	</body>
</html>
