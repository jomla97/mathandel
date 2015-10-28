<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="stylesheets/login.css">
	</head>
	<body>
		<main>
			
			<form method="POST" action="index.php?page=login">
				<?php
					if(isset($login_error)){
						echo '
							<div id="login-error">
								<p>'.$login_error.'</p>
							</div>
						';
					}
				?>
				<input class="text-input" type="text" name="username" placeholder="Username.." required>
				<input class="text-input" type="password" name="password" placeholder="Password.." required>
				<input class="login" type="submit" value="Logga in">
			</form>
		</main>
	</body>
</html>