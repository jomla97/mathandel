<?php
	
	//Starta en session
	session_start();

	function pdo(){
		//Skapa en uppkoppling till databasen med hjälp av PDO
		$host = "localhost";
		$dbname = "mathandel";
		$user = "mathandel";
		$dbpassword = "1234";
		$attr = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
		$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
		$pdo = new PDO($dsn, $user, $dbpassword, $attr);
		return $pdo;
	}

	//Kolla om användarnamnet och lösenordet som användaren skrivit in på inloggningssidan matchar nåt i databasen
	function login($username, $password){
		//Skapa en uppkoppling till databasen med hjälp av PDO
		$pdo = pdo();

		$statement = $pdo->prepare("SELECT * FROM users WHERE username LIKE '$username'");
		$statement->execute();
		$rowcount = $statement->rowCount();

		if($rowcount >= 1){
			$statement2 = $pdo->prepare("SELECT * FROM users WHERE username LIKE '$username' AND password LIKE '$password'");
			$statement2->execute();
			$rowcount = $statement2->rowCount();
			if($rowcount >= 1){
				foreach($pdo->query("SELECT * FROM users WHERE username LIKE '$username'") as $row){
					$access_level = $row['access_level'];
				}

				if($access_level == "admin"){
					$_SESSION['admin'] = true;
				}

				$_SESSION['username'] = $username;
				$_SESSION['password'] = $password;
				return 1;
				//username and password is a match! Successful login!
			}
			else{
				//wrong username or password!
				return 2;
			}
		}
		else{
			//there is no account with that username
			return 3;
		}
	}

	function register($username, $password, $email, $surname, $lastname, $street, $ort, $postalcode){
		$pdo = pdo();

		//check if the username is taken
		$statement = $pdo->prepare("SELECT * FROM users WHERE username LIKE '$username'");
		$statement->execute();
		$rowcount = $statement->rowCount();

		if($rowcount < 1){
			$statement = $pdo->prepare("SELECT * FROM users WHERE email LIKE '$email'");
			$statement->execute();
			$rowcount = $statement->rowCount();

			if($rowcount < 1){
				//register account
				$statement = $pdo->prepare("INSERT INTO users (username, password, email, surname, lastname, street, ort, postalcode, access_level) VALUES ('$username', '$password', '$email', '$surname', '$lastname', '$street', '$ort', '$postalcode', 'user')");
				if($statement->execute()){
					mail($email, "Bekräftelse email - Mathandel", "Tack for att du registrerat ett konto hos oss, " . $surname . ".");
					return 3;
				}
				else{
					echo '<h1>Something must have gone wrong! Send this error message to an admin and we will look into it.</h1><br>';
					print_r($statement->errorInfo());
				}
			}
			else{
				//email is taken
				return 2;
			}
		}
		else{
			//username is taken
			return 1;
		}
	}

	function edit_profile($email, $surname, $lastname, $street, $ort, $postalcode, $password_from_form){
		$pdo = pdo();

		$username = $_SESSION['username'];
		$password = $_SESSION['password'];

		if($password_from_form == $password){
			$statement = $pdo->prepare("SELECT * FROM users WHERE email LIKE '$email' AND username NOT LIKE '$username'");
			$statement->execute();
			$rowcount = $statement->rowCount();

			if($rowcount < 1){
				//update account details
				$statement = $pdo->prepare("UPDATE users SET email='$email', surname='$surname', lastname='$lastname', street='$street', ort='$ort', postalcode='$postalcode' WHERE username LIKE '$username' AND password LIKE '$password'");
				if($statement->execute()){
					mail($email, "Bekräftelse email - Mathandel", "Tack for att du registrerat ett konto hos oss, " . $surname . ".");
					return 2;
				}
				else{
					echo '<h1>Det verkar som att någonting gick väldigt fel. Skicka detta error-meddelande till en admin så kommer vi ta en titt på det.</h1><br>';
					print_r($statement->errorInfo());
				}
			}
			else{
				//email is taken
				return 1;
			}
		}
		else{
			//wrong password
			return 3;
		}
		
	}

	function change_password($username, $new_password, $current_password){
		$pdo = pdo();

		if($current_password == $_SESSION['password']){
			$statement = $pdo->prepare("UPDATE users SET password='$new_password' WHERE username LIKE '$username'");
			if($statement->execute()){
				$_SESSION['password'] = $new_password;

				$username = $_SESSION['username'];
				foreach($pdo->query("SELECT * FROM users WHERE username LIKE '$username") as $row){
					mail($row['email'], 'Lösenordsändring - Mathandel - Mathandel', 'Hej ' . $row['surname'] . '! Lösenordet för ditt konto på Mathandel har ändrats. Om detta var du kan du ignorera detta mejlet. Om detta inte var du ber vi dig att genast ändrar lösenordet på ditt konto då någon obehörig möjligtvis har fått tillgång till det. http://www.domain.se?page=account&action=change_password');
				}
				return 2;
			}
			else{
				echo '<h1>Det verkar som att någonting gick väldigt fel. Skicka detta error-meddelande till en admin så kommer vi ta en titt på det.</h1><br>';
				print_r($statement->errorInfo());
			}
		}
		else{
			return 1;
		}
	}

	//Kolla om användaren är inloggad
	function logged_in(){
		if(isset($_SESSION['username']) && isset($_SESSION['password'])){
			return true;
		}
		else{
			return false;
		}
	}

	//Logga ut
	function logout(){
		session_unset();
		session_destroy();
	}

	function stringReplacer($haystack){
		$needle = ['å', 'ä', 'ö', ' '];
		$replace = ['a', 'a', 'o', '-'];
		return str_replace($needle, $replace, $haystack);
	}

	function getSingleDbValue($columnName, $tableName, $prop, $value){
		$pdo = pdo();

		foreach($pdo->query("SELECT * FROM $tableName WHERE $prop LIKE '$value'") as $row){
			return $row[$columnName];
		}
	}

	//ADMIN FUNCTIONS------------------------------------------------------------------------

	function add_product($name, $contents, $amount, $nutriments, $allergens, $category, $price, $comparement_price, $comparement_type){
		$pdo = pdo();

		$file = $_FILES['image'];

		//file properties
		$file_name = $file['name'];
		$file_tmp = $file['tmp_name'];
		$file_error = $file['error'];

		//work out the file extension
		$file_ext = explode('.', $file_name);
		$file_ext = strtolower(end($file_ext));

		//upload file
		if($file_error === 0){
			$file_name_new = uniqid('', true) . '.' . $file_ext;
			$file_destination = 'uploads/' . $file_name_new;

			if(move_uploaded_file($file_tmp, $file_destination)){

				//insert all data into the database and create the product
				$statement = $pdo->prepare("INSERT INTO products (name, contents, amount, nutriments, allergens, category, image, price, comparement_price, comparement_type) VALUES ('$name', '$contents', '$amount', '$nutriments', '$allergens', '$category', '$file_destination', '$price', '$comparement_price', '$comparement_type')");
				
				if($statement->execute()){
					header("location:index.php?page=account#admin-panel");
				}
				else{
					echo '<h1>Something must have gone wrong! Send this error message to an admin and we will look into it.</h1><br>';
					print_r($statement->errorInfo());
				}

				//header("location:index.php");
			}
			else{
				echo 'Error. Something must have gone wrong. Try again.';
			}
		}
		else{
			echo "The image failed to upload! Try again. File error: " . $file_error;
		}

		
	}

	function edit_product($name, $contents, $amount, $nutriments, $allergens, $category, $price, $comparement_price, $comparement_type, $id){
		$pdo = pdo();

		//insert all data into the database and create the product
		$statement = $pdo->prepare("UPDATE products SET name='$name', contents='$contents', amount='$amount', nutriments='$nutriments', allergens='$allergens', category='$category', price='$price', comparement_price='$comparement_price', comparement_type='$comparement_type' WHERE id LIKE '$id'");
				
		if($statement->execute()){
			header("location:index.php");
		}
		else{
			echo '<h1>Something must have gone wrong! Send this error message to an admin and we will look into it.</h1><br>';
			print_r($statement->errorInfo());
		}

		
	}

	function delete_product($id){
		$pdo = pdo();

		foreach($pdo->query("SELECT * FROM products WHERE id LIKE '$id'") as $row){
			unlink($row['image']);
		}

		$statement = $pdo->prepare("DELETE FROM products WHERE id LIKE '$id'");
		if($statement->execute()){
			//delete the uploaded image linked to the product
			
			header("location:index.php");
		}
		else{
			echo '<h1>Something must have gone wrong! Send this error message to an admin and we will look into it.</h1><br>';
			print_r($statement->errorInfo());
		}
	}

	function add_category($name){
		$pdo = pdo();

		$statement = $pdo->prepare("INSERT INTO categories (name) VALUES ('$name')");
		
		if($statement->execute()){
			header("location:index.php?page=account#admin-panel");
		}
		else{
			echo '<h1>Something must have gone wrong! Send this error message to an admin and we will look into it.</h1><br>';
			print_r($statement->errorInfo());
		}
	}

?>