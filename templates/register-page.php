<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="stylesheets/register.css">
	</head>
	<body>
		<main>
			
			<form method="POST" action="index.php?page=register">
				<?php
					if(isset($registration_error)){
						echo '
							<div id="registration-error">
								<p>'.$registration_error.'</p>
							</div>
						';
					}
				?>
				<input class="text-input" type="email" name="email" placeholder="Email.." required>
				<input class="text-input" type="text" name="surname" placeholder="Förnamn.." required>
				<input class="text-input" type="text" name="lastname" placeholder="Efternamn.." required>
				<input class="text-input" type="text" name="street" placeholder="Gatuadress.." required>
				<input class="text-input" type="text" name="ort" placeholder="Ort.." required>
				<input class="text-input" type="text" name="postalcode" placeholder="Postkod.." required>
				<input class="text-input" type="text" name="username" placeholder="Username.." required>
				<input class="text-input" type="password" name="password" placeholder="Password.." required>
				<input class="register" type="submit" value="Registrera">
			</form>
		</main>
	</body>
</html>