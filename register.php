<?php
require 'config/config.php';
require 'includes/form_handlers/register_handler.php';
require 'includes/form_handlers/login_handler.php';
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to YoHello!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="assets/javascript/register.js" charset="utf-8"></script>
    <link rel="stylesheet" href="assets/css/reg_style.css">
  </head>
  <body class = "bg-dark">
    <?php
      if(isset($_POST['register_button'])){
        echo "
        <script>
          $(document).ready(function(){
            $('#first').hide();
            $('#second').show();
          });
        </script>
        ";
      }
    ?>
    <div class="container-fluid">
      <div class="row">
        <div class="col col-lg-6 wrapper">

        </div>
        <div class="col col-lg-6 bg-light">
          <h1 style = "color: #29a19c; font-family: 'Berkshire Swash', cursive;">YoHello!</h1>
          <h5 style = "color: #393e46;">Login or signup below!</h5>
          <div class="first" id="first">
            <form action="register.php" method="POST">
        			<input class="form-control" type="email" name="log_email" placeholder = "Email Address" value= "<?php if(isset($_SESSION['log_email'])){
        				echo $_SESSION['log_email'];
        			}?>" required>
        			<br>
        			<input class="form-control" type="password" name="log_password" placeholder = "Password" required>
        			<br>
        			<input class="btn btn-outline-dark" type="submit" name="login_button" value="Login">
        			<br>
        			<?php
        			if(in_array("Email or password was incorrect!<br>",$error_array)){
        				echo "<span style = 'color: #ff4b5c'> Email or password was incorrect!<br>";
        			}
        			?>
              <a style = "color: #29a19c;" class = "nav-link" href="#" id="signup">New here? Register here!</a>
        		</form>
          </div>
          <div class="second" id="second">
            <form action="register.php" method="POST">
                <input class="form-control" type="text" name="reg_fname" placeholder = "First Name" value= "<?php
        				if(isset($_SESSION['reg_fname'])){
        					echo $_SESSION['reg_fname'];
        				}
        				?>" required>
                <br>
        				<?php if(in_array("Your first name must be between 2 and 25 characters<br>",$error_array)) echo "<span style = 'color: #ff4b5c'> Your first name must be between 2 and 25 characters<br>" ;?>
                <input class="form-control" type="text" name="reg_lname" placeholder = "Last Name" value= "<?php
        				if(isset($_SESSION['reg_lname'])){
        					echo $_SESSION['reg_lname'];
        				}
        				?>" required>
                <br>
        				<?php if(in_array("Your last name must be between 2 and 25 characters<br>",$error_array)) echo "<span style = 'color: #ff4b5c'> Your last name must be between 2 and 25 characters<br>" ;?>

                <input class="form-control" type="email" name="reg_email" placeholder = "Email" value= "<?php
        				if(isset($_SESSION['reg_email'])){
        					echo $_SESSION['reg_email'];
        				}
        				?>" required>
                <br>
                <input class="form-control" type="email" name="reg_email2" placeholder = "Confirm Email" value= "<?php
        				if(isset($_SESSION['reg_email2'])){
        					echo $_SESSION['reg_email2'];
        				}
        				?>" required>
                <br>
        				<?php if(in_array("Email already in use<br>",$error_array)) echo "<span style = 'color: #ff4b5c'> Email already in use<br>" ;
        				else if(in_array("Invalid email format<br>",$error_array)) echo "<span style = 'color: #ff4b5c'>Invalid email format<br>" ;
        			else if(in_array("Emails don't match!<br>",$error_array)) echo "<span style = 'color: #ff4b5c'> Emails don't match!<br>";?>

                <input class="form-control" type="password" name="reg_password" placeholder = "Password" required>
                <br>
                <input class="form-control" type="password" name="reg_password2" placeholder = "Confirm password" required>
                <br>

        				<?php if(in_array("Passwords don't match.<br>",$error_array)) echo "<span style = 'color: #ff4b5c'> Passwords don't match.<br>" ;
        				else if(in_array("Your password can only contain english characters or numbers.<br>",$error_array)) echo "<span style = 'color: #ff4b5c'> Your password can only contain english characters or numbers.<br>" ;
        			else if(in_array("Your password must be between 5 and 30 characters<br>",$error_array)) echo "<span style = 'color: #ff4b5c'> Your password must be between 5 and 30 characters<br>";?>

                <button class="btn btn-outline-dark" type="submit" name="register_button">Register</button>
        				<br>
        				<?php if(in_array("<span style = 'color: #00917c'> Thank you for registering! You can login now!</span><br>",$error_array)) echo "<span style = 'color: #00917c'> Thank you for registering! You can login now!</span><br>" ;?>
                <a class = "nav-link" style = "color: #29a19c;" href="#" id="signin">Already have an account? Login here!</a>
            </form>
          </div>
        </div>
      </div>
    </div>
    <footer class="bg-dark" id="footer">
      <p class = "text-light">©YoHello! 2021</p>
  </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
  </body>
</html>
