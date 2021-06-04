<?php
include("includes/header.php");
include("includes/form_handlers/settings_handler.php");
?>

<main class = "content friend_req">
  <div class="container-fluid text-light">
    <h3>Account Settings</h3>
    <?php
    echo "<img src='".$user['profile_pic']."' height='100px' class='rounded-3 mb-3' />";
    ?>
    <br />
    <a href="upload.php">Uplaod new profile picture</a><br /><br /><br />
    <?php
    $user_data_query = mysqli_query($con, "SELECT first_name, last_name, email from users where username='$userLoggedIn'");
    $row = mysqli_fetch_array($user_data_query);
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $email = $row['email'];
    ?>
      <form action="settings.php" method="POST">
        <div class="row g-3 align-items-center mb-3">
          <div class="col-12 col-md-4">
            <label class="col-form-label">First Name: </label>
            <input type="text" class="form-control" name="first_name" value="<?php echo $first_name; ?>" />
          </div>
          <div class="col-12 col-md-4">
            <label class="col-form-label">Last Name: </label>
            <input type="text" class="form-control" name="last_name" value="<?php echo $last_name; ?>" />
          </div>
          <div class="col-12 col-md-6">
            <label class="col-form-label">Email: </label>
            <input type="text" class="form-control" name="email" value="<?php echo $email; ?>" />
          </div>
        </div>
        <?php echo $message; ?>
        <input type="submit" name="update_details" class="btn btn-info mb-3" value="Update"/>
      </form>
      <h4>Change Password</h4>
      <form action="settings.php" method="POST">
        <div class="row g-3 align-items-center mb-3">
          <div class="col-12 col-md-4">
            <label class="col-form-label">Old Password: </label>
            <input type="password" class="form-control" name="old_password" />
          </div>
          <div class="col-12 col-md-4">
            <label class="col-form-label">New Password: </label>
            <input type="password" class="form-control" name="new_password" />
          </div>
          <div class="col-12 col-md-4">
            <label class="col-form-label">New Password Confirm: </label>
            <input type="password" class="form-control" name="new_password_confirm" />
          </div>
        </div>
        <?php echo $password_message; ?>
        <input type="submit" name="change_password" class="btn btn-info mb-3" value="Change Password"/>
      </form>
      <h4>Close Account</h4>
      <form action="close_account.php" method="post">
        <input type="submit" name="delete_account" class="btn btn-danger mb-3" value="Close Account"/>
      </form>
  </div>
</main
