<?php
session_start();

// initializing variables
$firstname = "";
$lastname = "";
$email    = "";
$errors = array(); 

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'registration');

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $firstname = mysqli_real_escape_string($db, $_POST['firstname']);
  $lastname = mysqli_real_escape_string($db, $_POST['lastname']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($firstname)) { array_push($errors, "Firstname is required"); }
  if (empty($lastname)) { array_push($errors, "Lastname is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
	array_push($errors, "The two passwords do not match");
  }

  // first check the database to make sure 
  // a user does not already exist with the same email
  $user_check_query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['email'] === $email) {
      array_push($errors, "User with this email already exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($password_1);//encrypt the password before saving in the database

    $query = "INSERT INTO users (firstname, lastname, email, password) 
  			  VALUES('$firstname', '$lastname', '$email', '$password')";
  	mysqli_query($db, $query);
  	$_SESSION['firstname'] = $firstname;
  	$_SESSION['success'] = "You are now logged in";
    header('location: index.php');
    
  }
}

//SEND EMAIL VALIDATION
// didnt manage to get email sending to work.
function sendEmail($to, $hash) {
  $message = "Thanks for registering at this site. To finish registration, click on this link:\n";
  if (mail($to, "testing php mail function", $message)) {
    array_push($errors, "email sent succesfully");
  } else {
    array_push($errors, "email sending failed");
  }
}

// LOGIN USER
if (isset($_POST['login_user'])) {
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($email)) {
  	array_push($errors, "Email address is required");
  }
  if (empty($password)) {
  	array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
  	$password = md5($password);
  	$query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
  	$results = mysqli_query($db, $query);
  	if (mysqli_num_rows($results) == 1) {
  	  $_SESSION['firstname'] = $firstname;
  	  $_SESSION['success'] = "You are now logged in";
      header('location: index.php');
      sendEmail("francisks.jemeljanovs@gmail.com", "gfghj");
  	}else {
  		array_push($errors, "Wrong email/password combination");
  	}
  }
}

// VALIDATE EMAIL FROM DATABASE
if (isset($_GET['q'])) {
  $email = $_GET['q'];
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);
  if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_check_query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = mysqli_query($db, $email_check_query);
    if (mysqli_num_rows($result) == 1) {
      //var_dump($result);
      echo "email taken";
    }
  } else {
    echo "email address not valid";
  }
}

?>