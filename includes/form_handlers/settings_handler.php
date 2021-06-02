<?php
if(isset($_POST['update_details'])){
  $first_name = $_POST['first_name'];
  $last_name = $_POST['last_name'];
  $email = $_POST['email'];

  $email_check = mysqli_query($con, "SELECT * from users where email='$email'");
  $row = mysqli_fetch_array($email_check);
  $matched_user = $row['username'];
  if($matched_user == "" || $matched_user == $userLoggedIn){
    $message = "<span style = 'color: #00917c'>Details updated!</span><br /><br />";
    $query = mysqli_query($con, "UPDATE users set first_name='$first_name', last_name='$last_name', email='$email' where username='$userLoggedIn'");
  } else {
    $message = "<span class='text-danger'>Email is already in use!</span><br /><br />";
  }
} else {
  $message = "";
}

// -----------------------------------------------
if(isset($_POST['change_password'])){
  $old_password = strip_tags($_POST['old_password']);
  $new_password = strip_tags($_POST['new_password']);
  $new_password_confirm = strip_tags($_POST['new_password_confirm']);

  $password_query = mysqli_query($con, "SELECT password from users where username='$userLoggedIn'");
  $row = mysqli_fetch_array($password_query);
  $db_password = $row['password'];

  if(md5($old_password) == $db_password){
    if($new_password == $new_password_confirm){
      if(strlen($new_password) <= 4){
        $password_message = "<span class='text-danger'>Password must be longer than 4 characters!</span><br /><br />";
      } else{
        $new_password_md5 = md5($new_password);
        $password_query = mysqli_query($con, "UPDATE users set password='$new_password_md5' where username='$userLoggedIn'");
        $password_message = "<span style = 'color: #00917c'>Password has been changed!</span><br /><br />";
      }
    } else {
      $password_message = "<span class='text-danger'>New passwords don't match!</span><br /><br />";
    }
  } else {
    $password_message = "<span class='text-danger'>The old password is incorrect!</span><br /><br />";
  }

} else {
  $password_message = "";
}

if(isset($_POST['delete_account'])){
  header("Location: close_account.php");
}
?>
