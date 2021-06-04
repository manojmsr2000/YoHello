<?php
require 'config/config.php';
include("includes/classes/User.php");
include("includes/classes/Post.php");
include("includes/classes/Message.php");
include("includes/classes/Notification.php");

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
<html lang="eng">
<head>
	<title>YoHello!</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=yes" description="It's a social networking website!">

  <!-- CSS files-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
  <link rel=preload href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <link rel="stylesheet" href="assets/css/jquery.Jcrop.css">
  <link rel="stylesheet" href="assets/css/style.css">

  <!-- javascript files-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="assets/javascript/jcrop_bits.js" charset="utf-8"></script>
  <script src="assets/javascript/jquery.Jcrop.js" charset="utf-8"></script>
  <script src="assets/javascript/yohello.js" charset="utf-8"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>

</head>
<body>
  <div class="wrapper">
    <nav id="sidebar" class="sidebar bg-light">
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
              <a class="sidebar-link" href="messages.php">
                <i class="align-middle mr-2 far fa-comment-dots"></i> <span class="align-middle">Chats</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?php echo $userLoggedIn; ?>">
                <i class="align-middle mr-2 far fa-user"></i> <span class="align-middle">Profile</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="settings.php">
                <i class="align-middle mr-2 fas fa-cog"></i> <span class="align-middle">Settings</span>
              </a>
            </li>
            <li class="sidebar-item logout">
              <a class="sidebar-link" href="includes/handlers/logout.php">
                <i class="align-middle mr-2 fas fa-sign-out-alt"></i><span class="align-middle">Logout</span>
              </a>
            </li>
            <li class="sidebar-item">
              <p class="ms-3 mt-5 text-success">
                Trending words
              </p>
                <?php
                  $query = mysqli_query($con, "SELECT * from trends order by hits desc limit 9");
                  foreach ($query as $row) {
                    $word = $row['title'];
                    $word_dot = strlen($word) >= 14 ? "...": "";
                    $trimmed_word = str_split($word, 14);
                    $trimmed_word = $trimmed_word[0];

                    echo "<div class='ms-3 text-muted' style='padding: 1px;'>";
                    echo $trimmed_word . $word_dot;
                    echo "<br /></div>";
                  }
                 ?>
            </li>

          </ul>
        </div>
      </nav>
      <div class="main">
        <?php
        //Unread messages
          $messages = new Message($con, $userLoggedIn);
          $num_messages = $messages->getUnreadNumber();

          //Unread notifications
            $notifications = new Notification($con, $userLoggedIn);
            $num_notifications = $notifications->getUnreadNumber();

            //Unread notifications
              $user_obj = new User($con, $userLoggedIn);
              $num_requests = $user_obj->getNumberOfFriendRequests();
        ?>
        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
          <div class="container-fluid ">
            <a class="navbar-brand" href="index.php">
              <h2 class="logo">YoHello!</h2>
            </a>
            <div class="navbar-nav me-auto justify-content-start">
              <form class="d-flex form-group has-search" action="search.php" method="GET" name="search_form">
                  <span class="fa fa-search form-control-feedback"></span>
                  <input type="text" class="form-control rounded me-2" placeholder= "Find Someone" id="search_text_input" onkeyup="getLiveSeachUsers(this.value, '<?php echo $userLoggedIn; ?>')" name="q" autocomplete="off">
              </form>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
              <div class="navbar-nav ms-auto flex-row flex-wrap ms-me-auto justify-content-center">
                <a class="nav-link p-3" aria-current="page" href="requests.php"><i class="fas fa-users fa-lg"></i></a>
                <?php
                if($num_requests > 0){
                  echo '<span class="counter counter-lg" id="unread_request">'.$num_requests.'</span>';
                }
                 ?>
                <a class="nav-link p-3" href="javascript:void(0)" onclick="getDropdownData('<?php echo $userLoggedIn; ?>', 'message')"><i class="far fa-comment-dots fa-lg"></i>
               </a>
               <?php
               if($num_messages > 0){
                 echo '<span class="counter counter-lg" id="unread_message">'.$num_messages.'</span>';
               }
                ?>
                <a class="nav-link p-3" href="javascript:void(0)" onclick="getDropdownData('<?php echo $userLoggedIn; ?>', 'notification')">
                  <i class="far fa-bell fa-lg"></i>
                </a>
                <?php
                if($num_notifications > 0){
                  echo '<span class="counter counter-lg" id="unread_notification">'.$num_notifications.'</span>';
                }
                 ?>
                <a class="nav-link p-3" href="includes/handlers/logout.php">
                  <i class="align-middle mr-2 fas fa-sign-out-alt fa-lg"></i>
                </a>

                  <a class="nav-link p-3" href="<?php echo $userLoggedIn; ?>"><?php echo $user['first_name']; ?></a>
                </div>
              </div>
          </div>
      </nav>

      <a id="hamburger" class="hamburger sidebar-toggle text-success" href="#">
          <i class="fas fa-bars fa-lg"></i>
      </a>

      <div class="dropdown_data_window mt-5 bg-dark">
          <input type="hidden" id="dropdown_data_type" value="">
      </div>
      <div class="search_results_box">
        <div class="search_results">
        </div>

        <div class="search_results_footer_empty">
        </div>
      </div>
      <script>
      var userLoggedIn = '<?php echo $userLoggedIn; ?>';

      $(document).ready(function() {

        $('.dropdown_data_window').scroll(function() {
          var page = $('.dropdown_data_window').find('.nextPageDropDownData').val();
          var noMoreData = $('.dropdown_data_window').find('.noMoreDropDownData').val();

          if ( noMoreData == 'false') {
            var pageName;
            var type = $('#dropdown_data_type').val();

            if(type == 'notification')
              pageName = "ajax_load_notifications.php";
            else if (type == "message")
              pageName = "ajax_load_messages.php"

            var ajaxReq = $.ajax({
              url: "includes/handlers/" + pageName,
              type: "POST",
              data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
              cache:false,

              success: function(response) {
                $('.dropdown_data_window').find('.nextPageDropDownData').remove(); //Removes current .nextpage
                $('.dropdown_data_window').find('.noMoreDropDownData').remove(); //Removes current .nextpage

                $('.dropdown_data_window').append(response);
              }
            });

          } //End if

          return false;

        }); //End (window).scroll(function())


      });

      </script>
