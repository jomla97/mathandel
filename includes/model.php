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

	/*
	function add_project($title, $description){
		//thumbnail
		$file = $_FILES['image']['tmp_name'];
		$image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
		$image_name = addslashes($_FILES['image']['name']);
		$image_size = getimagesize($file);

		if($image_size == FALSE){
			return "ERROR";
		}
		else{
			$pdo = pdo();
			$statement = $pdo->prepare("INSERT INTO projects (title, description) VALUES (?, ?)");
			$statement->bindParam(1, $title);
			$statement->bindParam(2, $description);

			$project_id = getSingleDbValue("id", "projects", "title", $title);
			$statement2 = $pdo->prepare("INSERT INTO images (name, image) VALUES ($image_name, $image)");
			$statement2->execute();
		}

		if($statement->execute()){
			return "SUCCESS";
		}
		else{
			return "ERROR";
		}
	}
	*/

	function upload_project_images($title, $description, $thumbnail_destination){
		if(!empty($_FILES['images']['name'][0])){
			$pdo = pdo();

			//declare new variable for ease of use
			$files = $_FILES['images'];

			//count the amount of failed uploads
			$failed_uploads = 0;

			//uploaded and failed arrays to store both successful uploads as well as failed uploads
			$uploaded = array();
			$failed = array();

			//get the project id
			$project_id = getSingleDbValue('id', 'projects', 'thumbnail', $thumbnail_destination);

			//for each file the user has selected to upload, do this
			foreach($files['name'] as $position => $file_name){
				$file_tmp = $files['tmp_name'][$position];
				$file_error = $files['error'][$position];

				//get the file extension so that the filename can be changed later on
				$file_ext = explode('.', $file_name);
				$file_ext = strtolower(end($file_ext));

				if($file_error === 0){
					//declare new randomized filename
					$file_name_new = uniqid('', true) . '.' . $file_ext;
					$file_destination = 'uploads/project_images/' . $file_name_new;

					//upload the selected file
					if(move_uploaded_file($file_tmp, $file_destination)){
						$uploaded[$position] = $file_destination;
						
						$statement = $pdo->prepare("INSERT INTO project_images (project_id, file_destination) VALUES (?, ?)");
						$statement->bindParam(1, $project_id);
						$statement->bindParam(2, $file_destination);
						
						if($statement->execute()){
							echo 'Database upload successful! <br>';
						}
						else{
							echo 'Database upload failed! <br>';
							print_r($statement->errorInfo());
							echo '<br>';
						}
					}
					else{
						//if the upload failed, add it to the array for failed uploads
						$failed[$position] = "[{$file_name}] failed to upload file.";
						$failed_uploads++;
					}
				}
				else{
					//if the upload failed, add it to the array for failed uploads
					$failed[$position] = "[{$file_name}] error with code {$file_error}.";
					$failed_uploads++;
				}
			}
			echo 'Successful uploads: <br>';
  			print_r($uploaded);
			echo '<br>Failed uploads: <br>';
  			print_r($failed);
		}
		
		return $failed_uploads;
	}

	function add_new_category($category){
		$pdo = pdo();
		$statement = $pdo->prepare("INSERT INTO project_categories (category) VALUES (?)");
		$statement->bindParam(1, $category);

		if($statement->execute()){
			echo 'Database upload successful!';
		}
		else{
			echo 'Database upload failed! <br>';
			print_r($statement->errorInfo());
		}
	}

	//take the form input from the user and send it to my email
	function contact($email, $name, $message){
	 	mail('email@johannesnyman.se', 'Message through your website form', $message, 'From: ' . $name . ' - ' . $email);
	 	echo '<script>
	 				var result = alert("Your message has been sent. I will get back to \''.$email.'\' as soon as possible.");
	 				window.location = "index.php";
	 			</script>';
	}

?>