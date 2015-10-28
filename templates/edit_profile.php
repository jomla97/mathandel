<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="stylesheets/edit_profile.css">
	</head>
	<body>
		<main>
			
			<form method="POST" action="index.php?page=account&action=edit_profile">
				<?php
					if(isset($edit_profile_error)){
						echo '
							<div id="edit-profile-error">
								<p>'.$edit_profile_error.'</p>
							</div>
						';
					}

					$username = $_SESSION['username'];
					$password = $_SESSION['password'];
					foreach($pdo->query("SELECT * FROM users WHERE username LIKE '$username' AND password LIKE '$password'") as $row){
						echo '
							<input class="text-input" type="email" name="email" placeholder="Email.." value="' . $row['email'] . '" required>
							<input class="text-input" type="text" name="surname" placeholder="Förnamn.." value="' . $row['surname'] . '" required>
							<input class="text-input" type="text" name="lastname" placeholder="Efternamn.." value="' . $row['lastname'] . '" required>
							<input class="text-input" type="text" name="street" placeholder="Gatuadress.." value="' . $row['street'] . '" required>
							<input class="text-input" type="text" name="ort" placeholder="Ort.." value="' . $row['ort'] . '" required>
							<input class="text-input" type="text" name="postalcode" placeholder="Postkod.." value="' . $row['postalcode'] . '" required>
						';
					}
				?>
				<br>
				<input class="text-input" type="password" name="password" placeholder="Lösenord.." required>
				<input class="update" type="submit" value="Uppdatera">
			</form>
		</main>
	</body>
</html>