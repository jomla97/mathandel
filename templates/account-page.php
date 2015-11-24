<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="stylesheets/account.css">
	</head>
	<body>
		<main>
			<h1>Mitt konto</h1>
			<?php
				$pdo = pdo();
				$username = $_SESSION['username'];
				$password = $_SESSION['password'];

				//general account information
				foreach($pdo->query("SELECT * FROM users WHERE username LIKE '$username' AND password LIKE '$password'") as $row){
					if(isset($_SESSION['admin']) && $_SESSION['admin'] == true){
						echo '
							<h2>Namn</h2>
							<p>' . $row['surname'] . " " . $row['lastname'] . '</p>

							<h2>Adress</h2>
							<p>' . $row['street'] . '</p>
							<p>' . $row['postalcode'] . '</p>
							<p>' . $row['ort'] . '</p>

							<h2>Email</h2>
							<p>' . $row['email'] . '</p>

							<h2>Användarnamn</h2>
							<p>' . $row['username'] . ' (admin)</p>

							<a href="index.php?page=account&action=edit_profile"><p id="edit">Redigera</p></a>
							<a href="index.php?page=account&action=change_password"><p id="change-password">Byt lösenord</p></a>
						';
					}
					else{
						echo '
							<h2>Namn</h2>
							<p>' . $row['surname'] . " " . $row['lastname'] . '</p>

							<h2>Adress</h2>
							<p>' . $row['street'] . '</p>
							<p>' . $row['postalcode'] . '</p>
							<p>' . $row['ort'] . '</p>

							<h2>Email</h2>
							<p>' . $row['email'] . '</p>

							<h2>Användarnamn</h2>
							<p>' . $row['username'] . '</p>

							<a href="index.php?page=account&action=edit_profile"><p id="edit">Redigera</p></a>
							<a href="index.php?page=account&action=change_password"><p id="change-password">Byt lösenord</p></a>
						';
					}
				}

				//admin panel
				if(isset($_SESSION['admin']) && $_SESSION['admin'] == true){
					echo '
						<div id="admin-panel">
							<h1>Admin panel</h1>
							<a href="index.php?page=admin&action=add_product">
								<div class="admin-button-green" title="Lägg till produkt">
									<img src="res/add-product-icon.png">
								</div>
							</a>

							<a href="index.php?page=admin&action=add_category">
								<div class="admin-button-green" title="Lägg till produktkategori">
									<img src="res/add-category-icon.png">
								</div>
							</a>

							<a href="index.php?page=admin&action=delete_account">
								<div class="admin-button-red" title="Ta bort ett användarkonto">
									<img src="res/delete-user-icon.png">
								</div>
							</a>
						</div>
					';
				}
			?>
		</main>
	</body>
</html>