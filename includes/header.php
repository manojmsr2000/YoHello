<?php
require 'config/config.php';
include("includes/classes/User.php");
include("includes/classes/Post.php");
if(isset($_SESSION['username'])){
  $userLoggedIn = $_SESSION['username'];
  $user_details_query = mysqli_query($con,"SELECT * FROM users where username='$userLoggedIn'");
  $user = mysqli_fetch_array($user_details_query);
}
else{
  header("location: register.php");
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>YoHello!</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500&display=swap">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
<!-- <link rel="stylesheet" href="https://spark.bootlab.io/css/dark.css"> -->
<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="wrapper">
    <nav id="sidebar" class="sidebar fixed-right bg-light">
        <a class="sidebar-brand" href="index.php">
          <h1>YoHello!</h1>
        </a>
        <div class="sidebar-content">
          <div class="sidebar-user">
            <a href="<?php echo $userLoggedIn; ?>"><img src="<?php echo $user['profile_pic']; ?>" class="img-fluid rounded-circle mb-2" alt="user_img" /></a>
            <div class="font-weight-bold"> <a class="nav-link text-dark" href="<?php echo $userLoggedIn; ?>"><?php echo $user['first_name']." ".$user['last_name']; ?> </a> </div>
          </div>

          <ul class="sidebar-nav">
            <li class="sidebar-item active">
              <a class="sidebar-link" href="index.php">
                <i class="align-middle mr-2 fas fa-home"></i> <span class="align-middle">Home</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="index.php">
                <i class="align-middle mr-2 far fa-envelope"></i> <span class="align-middle">Messages</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="index.php">
                <i class="align-middle mr-2 far fa-comment-dots"></i> <span class="align-middle">Chats</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="index.php">
                <i class="align-middle mr-2 far fa-user"></i> <span class="align-middle">Profile</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="index.php">
                <i class="align-middle mr-2 fas fa-cog"></i> <span class="align-middle">Settings</span>
              </a>
            </li>
            <li class="sidebar-item logout">
              <a class="sidebar-link" href="includes/handlers/logout.php">
                <i class="align-middle mr-2 fas fa-sign-out-alt"></i><span class="align-middle">Logout</span>
              </a>
            </li>
          </ul>
        </div>
      </nav>
      <div class="main">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
          <a class=" hamburger sidebar-toggle d-flex mr-2 text-dark" href="#">
      			  <i class="fas fa-bars fa-2x"></i>
      		</a>
          <div class="container-fluid">
            <form class="d-flex ml-auto">
              <input class="form-control me-2 fa-sm" type="search" placeholder= "Find Someone" aria-label="Search">
              <button class="btn btn-dark btn-sm" type="submit"><i class="fas fa-search"></i></button>
            </form>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
              <div class="navbar-nav">
                <a class="sidebar-link" aria-current="page" href="requests.php"><i class="far fa-envelope fa-lg"></i></a>
                <a class="sidebar-link" href="#"><i class="far fa-bell fa-lg"></i></a>
                <a class="sidebar-link img" href="#"><img src="<?php echo $user['profile_pic']; ?>" class="img-fluid rounded-circle" alt="user_img" width="25px"/></a>
              </div>
            </div>
          </div>
      </nav>
