<?php

  require "includes/model.php";
  require "templates/header.php";

  $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_URL);
  $logout = filter_input(INPUT_GET, 'logout' , FILTER_SANITIZE_URL);

  $pdo = pdo();

  //Check if the user has logged out
  if($logout){
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
  if($page == 'login' && logged_in() == false){
     if(isset($_POST['username']) && isset($_POST['password'])){
        $result = login($_POST['username'], $_POST['password']);
        if($result['header'] == 'LOGIN FAILED'){
           $login_error = "Login failed. Check your username and password and try again.";
          //require "templates/login-page.php";
         }
        else{
           header("location:index.php");
         }
      }

      require "templates/login-page.php";
  }

  else{
    
  }

  require "templates/footer.php";

?>