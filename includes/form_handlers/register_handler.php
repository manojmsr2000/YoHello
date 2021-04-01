<?php
//Declaring variables
$fname = "";
$lname = "";
$em = "";
$em2 = "";
$password = "";
$password2 = "";
$date = "";
$error_array = array(); //Holds error messages

if(isset($_POST['register_button'])){
  //Registrration form values

  //first name
  $fname = strip_tags($_POST['reg_fname']); //remove html tags
  $fname = str_replace(' ','',$fname); //remove spaces
  $fname = ucfirst(strtolower($fname)); //uppercase first letter
	$_SESSION['reg_fname'] = $fname;
//last name
  $lname = strip_tags($_POST['reg_lname']); //remove html tags
  $lname = str_replace(' ','',$lname); //remove spaces
  $lname = ucfirst(strtolower($lname)); //uppercase first letter
	$_SESSION['reg_lname'] = $lname;

//email
  $em = strip_tags($_POST['reg_email']); //remove html tags
  $em = str_replace(' ','',$em); //remove spaces
  $em = ucfirst(strtolower($em)); //uppercase first letter
	$_SESSION['reg_email'] = $em;

//confirm email
  $em2 = strip_tags($_POST['reg_email2']); //remove html tags
  $em2 = str_replace(' ','',$em2); //remove spaces
  $em2 = ucfirst(strtolower($em2)); //uppercase first letter
	$_SESSION['reg_email2'] = $em2;

//password
  $password = strip_tags($_POST['reg_password']); //remove html tags
	$_SESSION['reg_password'] = $password;

  $password2 = strip_tags($_POST['reg_password2']); //remove html tags
	$_SESSION['reg_password2'] = $password2;

//date
  $date = date("Y-m-d"); //current date

  if($em == $em2){
		if(filter_var($em, FILTER_VALIDATE_EMAIL)){
			$em = filter_var($em, FILTER_VALIDATE_EMAIL);
			//check if email exists
			$e_check = mysqli_query($con,"SELECT email FROM users WHERE email='$em'");
			//count no of rows returned
			$num_rows = mysqli_num_rows($e_check);

			if($num_rows>0){
				array_push($error_array, "Email already in use<br>");
			}
		}
		else{
			array_push($error_array,"Invalid email format<br>");
		}
  }
  else{
    array_push($error_array, "Emails don't match!<br>");
  }

	if(strlen($fname)> 25 || strlen($fname) < 2){
		array_push($error_array, "Your first name must be between 2 and 25 characters<br>");
	}
	if(strlen($lname) > 25 || strlen($lname) < 2){
		array_push($error_array, "Your last name must be between 2 and 25 characters<br>");
	}
	if($password != $password2){
		array_push($error_array, "Passwords don't match.<br>");
	}
	else{
		if(preg_match('/[^A-Za-z0-9]/',$password)){
			array_push($error_array, "Your password can only contain english characters or numbers.<br>");
		}
	}
	if(strlen($password) > 30 || strlen($password)<5){
		array_push($error_array, "Your password must be between 5 and 30 characters<br>");
	}

	if(empty($error_array)){
		$password = md5($password); //Encrypt password
		//Generate username
		$username = strtolower($fname."_".$lname);
		$check_username_query = mysqli_query($con, "SELECT username from users where username = '$username'");

		$i = 0;
		//if username exists add number to $username
		while(mysqli_num_rows($check_username_query)!=0){
			$i++;
			$username = $username."_".$i;
			$check_username_query = mysqli_query($con, "SELECT username from users where username = '$username'");
		}
		$rand = rand(1,2); //random number between 1 and 2
		if($rand == 1)
		{
		$profile_pic = "assets/images/profile_pics/defaults/head_wet_asphalt.png";
	}
	else if($rand==2){
		$profile_pic = "assets/images/profile_pics/defaults/head_turqoise.png";
	}
	$query = mysqli_query($con,"INSERT into users values('','$fname','$lname','$username','$em','$password','$date','$profile_pic','0','0','no',',')");
	array_push($error_array,"<span style = 'color: #00917c'> Thank you for registering! You can login now!</span><br>");

	//clear session variables
	session_unset();
	}

}
?>
