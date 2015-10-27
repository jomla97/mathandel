<?php
	
	//Starta en session
	session_start();

	function pdo(){
		//Skapa en uppkoppling till databasen med hjälp av PDO
		$host = "localhost";
		$dbname = "portfolio";
		$user = "jomla97";
		$dbpassword = "smygenäger";
		$attr = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
		$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
		$pdo = new PDO($dsn, $user, $dbpassword, $attr);
		return $pdo;
	}

	//Kolla om användarnamnet och lösenordet som användaren skrivit in på inloggningssidan matchar nåt i databasen
	function login($username, $password){
		//Skapa en uppkoppling till databasen med hjälp av PDO
		$pdo = pdo();

		if($username == "jomla97" && $password == "smygen"){
			$_SESSION['username'] = $username;
			$_SESSION['password'] = $password;
			$result = array("header" => "LOGGED IN", "content" => "Welcome Johannes! You are now logged in.");
			return $result;
		}
		else{
			$result = array("header" => "LOGIN FAILED", "content" => "Login failed! Wrong username or password! <a href=\"index.php?page=admin\">Try again.</a>");
		    return $result;
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
		$value = '%'.$value.'%';

		$statement = $pdo->prepare("SELECT * FROM $tableName WHERE $prop LIKE ?");
		$statement->bindParam(1, $value);
		if($statement->execute())
		{
			$row = $statement->fetch(PDO::FETCH_ASSOC);
			return $row["$columnName"];
		}
		else{
			print_r($statement->errorInfo());
		}
	}

	function portfolio(){
		$pdo = pdo();
		$model = array();

		foreach($pdo->query("SELECT * FROM projects") as $row){

		}
	}

	function upload_thumbnail(){

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

	function add_project($title, $description, $category){
		$file = $_FILES['thumbnail'];

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
			$file_destination = 'uploads/thumbnails/' . $file_name_new;

			if(move_uploaded_file($file_tmp, $file_destination)){
				//insert all data into the database and create the project
				$pdo = pdo();
				$statement = $pdo->prepare("INSERT INTO projects (title, description, thumbnail, category) VALUES (?, ?, ?, ?)");
				$statement->bindParam(1, $title);
				$statement->bindParam(2, $description);
				$statement->bindParam(3, $file_destination);
				$statement->bindParam(4, $category);

				if($statement->execute()){
					$failed_uploads = upload_project_images($title, $description, $file_destination);
					if($failed_uploads > 0){
						echo '
							<script>
								alert("' . $failed_uploads . ' failed.");
							</script>
						';
					}

					//header("location:index.php");
				}
				else{
					echo 'Error. Something must have gone wrong. Try again.';
				}

				//header("location:index.php");
			}
			else{
				echo 'Error. Something must have gone wrong. Try again.';
			}
		}
		header("location:index.php");
	}

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

	function delete_project($project_id){
		$pdo = pdo();

		//delete project images
		foreach($pdo->query("SELECT * FROM project_images WHERE project_id LIKE $project_id") as $row){
			unlink($row['file_destination']);
		}
		//delete project thumbnail
		foreach($pdo->query("SELECT * FROM projects WHERE id LIKE $project_id") as $row){
			unlink($row['thumbnail']);
		}

		//delete projectdata from the database
		$statement2 = $pdo->prepare("DELETE FROM projects WHERE id LIKE ?");
		$statement2->bindParam(1, $project_id);

		if($statement2->execute()){
			echo 'Project deleted!';
			return true;
		}
		else{
			echo 'Delete failed! <br>';
			print_r($statement->errorInfo());
			return false;
		}
	}

	function edit_project($title, $description, $category, $project_id){
		$pdo = pdo();

		$statement = $pdo->prepare("UPDATE projects SET title=?, description=?, category=? WHERE id LIKE ?");
		$statement->bindParam(1, $title);
		$statement->bindParam(2, $description);
		$statement->bindParam(3, $category);
		$statement->bindParam(4, $project_id);

		if($statement->execute()){
			echo 'Project successfully edited!';
			return true;
		}
		else{
			echo 'Edit failed! <br>';
			print_r($statement->errorInfo());
			return false;
		}
	}

	function add_skill($skill, $skill_level){
		$pdo = pdo();

		$statement = $pdo->prepare("INSERT INTO skills (skill, skill_level) VALUES (?, ?)");
		$statement->bindParam(1, $skill);
		$statement->bindParam(2, $skill_level);

		if($statement->execute()){
			echo 'Skill successfully added!';
			header("location:index.php#myskills");
		}
		else{
			echo 'Operation failed! The skill has not been added. <br>';
			print_r($statement->errorInfo());
		}
	}

	function edit_skill($skill, $skill_level, $skill_id){
		$pdo = pdo();

		$statement = $pdo->prepare("UPDATE skills SET skill=?, skill_level=? WHERE id LIKE ?");
		$statement->bindParam(1, $skill);
		$statement->bindParam(2, $skill_level);
		$statement->bindParam(3, $skill_id);

		if($statement->execute()){
			echo 'Skill successfully added!';
			header("location:index.php#myskills");
		}
		else{
			echo 'Operation failed! The skill has not been added. <br>';
			print_r($statement->errorInfo());
		}
	}

	function delete_skill($skill_id){
		$pdo = pdo();

		$statement = $pdo->prepare("DELETE FROM skills WHERE id LIKE ?");
		$statement->bindParam(1, $skill_id);

		if($statement->execute()){
			echo 'Skill successfully deleted!';
			header("location:index.php#myskills");
		}
		else{
			echo 'Operation failed! The skill has not been deleted. <br>';
			print_r($statement->errorInfo());
		}
	}

?>