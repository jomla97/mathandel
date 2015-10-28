<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="stylesheets/change-password.css">
	</head>
	<body>
		<main>
			
			<form method="POST" action="index.php?page=account&action=change_password" autocomplete="off">
				<?php
					if(isset($change_password_error)){
						echo '
							<div id="change-password-error">
								<p>'.$change_password_error.'</p>
							</div>
						';
					}
				?>
				<!-- fake fields are a workaround for chrome autofill -->
				<input style="display:none" type="text" name="fakeusernameremembered"/>
				<input style="display:none" type="password" name="fakepasswordremembered"/>
				<input class="text-input" type="password" name="new_password" placeholder="Nytt lösenord.." required>
				<br>
				<input class="text-input" type="password" name="current_password" placeholder="Nuvarande lösenord.." required>
				<input class="change" type="submit" value="Logga in">
			</form>
		</main>
	</body>
</html>