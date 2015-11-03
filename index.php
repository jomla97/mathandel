<?php

  require "includes/model.php";
  require "templates/header.php";

  $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_URL);
  $logout = filter_input(INPUT_GET, 'logout' , FILTER_SANITIZE_URL);

  $pdo = pdo();

  //Check if the user has logged out
  if($page == "logout"){
    if(logged_in()){
      //call the function in model.php called "logout" to log the user out of the account and destroy the active session
      logout();

      //redirect the user to the startpage after logout
      header("location:index.php");
    }
    else{
      //if the user is not logged in just redirect the user to the startpage again
      header("location:index.php");
    }
  }


  //CHECK WHAT PAGE THE USER IS ON
  else if($page == 'login' && logged_in() == false){
     if(isset($_POST['username']) && isset($_POST['password'])){

     	$login = login($_POST['username'], $_POST['password']);

        if($login == 1){
        	header("location:index.php");
        }
        else if($login == 2){
           $login_error = "Wrong username or password! Try again.";
        }
        else if($login == 3){
           $login_error = "There is no account with that username.";
        }
      }

      require "templates/login-page.php";
  }

  else if($page == "register"){
  	if(logged_in()){
  		header("location:index.php");
  	}
  	else{
  		if(isset($_POST['username'])){
  			$result = register($_POST['username'], $_POST['password'], $_POST['email'], $_POST['surname'], $_POST['lastname'], $_POST['street'], $_POST['ort'], $_POST['postalcode']);
  			
  			if($result == 1){
  				$registration_error = "Användarnamnet '" . $_POST['username'] . "' är tagen. Försök med en annan.";
  			}
  			else if($result == 2){
  				$registration_error = "Email-adressen '" . $_POST['email'] . "' är tagen. Försök med en annan.";
  			}
  			else if($result == 3){
  				header("location:index.php");
  			}
  		}
  		require "templates/register-page.php";
  	}
  }

  else if($page == "account" && logged_in()){
  	if(isset($_GET['action']) && $_GET['action'] == "edit_profile"){
  		if(isset($_POST['email']) && isset($_POST['surname']) && isset($_POST['lastname']) && isset($_POST['street']) && isset($_POST['ort']) && isset($_POST['postalcode']) && isset($_POST['password'])){
  			$result = edit_profile($_POST['email'], $_POST['surname'], $_POST['lastname'], $_POST['street'], $_POST['ort'], $_POST['postalcode'], $_POST['password']);

  			if($result == 1){
  				$edit_profile_error = "Email-adressen '" . $_POST['email'] . "' är tagen. Försök med en annan.";
  			}
  			else if($result == 2){
  				header("location:index.php?page=account");
  			}
  			else if($result == 3){
  				$edit_profile_error = "Lösenordet du skrev in som ditt 'nuvarande' är fel. Försök igen.";
  			}
  		}
  		require "templates/edit_profile.php";
  	}
  	else if(isset($_GET['action']) && $_GET['action'] == "change_password"){
  		if(isset($_POST['new_password'])){

  			$result = change_password($_SESSION['username'], $_POST['new_password'], $_POST['current_password']);

  			if($result == 1){
  				$change_password_error = "Lösenordet du skrev in som ditt 'nuvarande' är fel. Försök igen.";
  			}
  			else if($result == 2){
  				header("location:index.php?page=account");
  			}
  		}
  		require "templates/change-password.php";
  	}
    else if(isset($_GET['action']) && $_GET['action'] == 'delete_product' && isset($_GET['id'])){
      delete_product($_GET['id']);
    }
  	else{
  		require "templates/account-page.php";
  	}
  }

  else if($page == 'admin' && isset($_SESSION['admin']) && $_SESSION['admin'] == true && isset($_GET['action'])){
  	if($_GET['action'] == 'add_product'){
  		if(isset($_POST['name'])){
  			add_product($_POST['name'], $_POST['contents'], $_POST['amount'], $_POST['nutriments'], $_POST['allergens'], $_POST['category'], $_POST['price'], $_POST['comparement_price'], $_POST['comparement_type']);
  		}

  		require "templates/admin/add-product.php";
  	}
  	else if($_GET['action'] == 'add_category'){
  		if(isset($_POST['name'])){
  			add_category($_POST['name']);
  		}

  		require "templates/admin/add-category.php";
  	}
  }

  else{
    require "templates/startpage.php";
  }

  require "templates/footer.php";

?>