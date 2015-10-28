<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="stylesheets/account.css">
	</head>
	<body>
		<main>
			<?php
				$pdo = pdo();
				$username = $_SESSION['username'];
				$password = $_SESSION['password'];
				foreach($pdo->query("SELECT * FROM users WHERE username LIKE '$username' AND password LIKE '$password'") as $row){
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
			?>
		</main>
	</body>
</html>