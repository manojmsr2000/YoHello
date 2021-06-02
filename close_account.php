<?php
include("includes/header.php");

if(isset($_POST['cancel'])){
  header("Location: settings.php");
}

if(isset($_POST['close_account'])){
  $close_query = mysqli_query($con, "UPDATE users set user_closed='yes' where username='$userLoggedIn'");
  session_destroy();
  header("Location: register.php");
}

?>

<main class = "content">
  <div class="container-fluid text-light">
    <h4>Close Account</h4>
    Are you sure you want to delete your account?<br /><br />
    Closing your account will hide your profile and all your activity from other users.<br /><br />
    You can re-open your account at any time by simply logging in.<br /><br />
    <form action="close_account.php" method="post">
      <input type="submit" name="close_account" value="Yes. I'm sure!" class="btn btn-danger" />
      <input type="submit" name="cancel" value="No!"  class="btn btn-success" />
    </form>
  </div>
</main>
