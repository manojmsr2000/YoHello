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
<html>
<head>
	<title>YoHello!</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- CSS files-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="stylesheet" href="assets/css/jquery.Jcrop.css">
  <link rel="stylesheet" href="assets/css/style.css">

  <!-- javascript files-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
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
              <a class="sidebar-link" href="index.php">
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
          <div class="container-fluid">
            <a class="navbar-brand me-5" href="index.php">
              <h2 class="logo">YoHello!</h2>
            </a>
            <a class="hamburger sidebar-toggle d-flex ms-5 me-2 text-dark" href="#">
        			  <i class="fas fa-bars fa-2x"></i>
        		</a>

            <form class="d-flex ms-3" action="search.php" method="GET" name="search_form">
            <div class="input-group rounded">
              <input type="search" class="form-control rounded form-control me-2 fa-sm" aria-label="Search"
                aria-describedby="search-addon" placeholder= "Find Someone" id="search_text_input" onkeyup="getLiveSeachUsers(this.value, '<?php echo $userLoggedIn; ?>')" name="q" autocomplete="off" />
              <button class="input-group-text border-0" id="search-addon" type="submit">
                <i class="fas fa-search"></i>
              </button>
            </div>
            </form>



            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
              <div class="navbar-nav">
                <a class="sidebar-link" aria-current="page" href="requests.php"><i class="fas fa-users fa-lg"></i>
                  <?php
                  if($num_requests > 0){
                    echo '<span class="indicator" id="unread_request">'.$num_requests.'</span>';
                  }
                   ?>
                </a>
                <a class="sidebar-link" href="javascript:void(0)" onclick="getDropdownData('<?php echo $userLoggedIn; ?>', 'message')"><i class="far fa-comment-dots fa-lg"></i>
                <?php
                if($num_messages > 0){
                  echo '<span class="indicator" id="unread_message">'.$num_messages.'</span>';
                }
                 ?>
               </a>
                <a class="sidebar-link" href="javascript:void(0)" onclick="getDropdownData('<?php echo $userLoggedIn; ?>', 'notification')">
                  <i class="far fa-bell fa-lg"></i>
                  <?php
                  if($num_notifications > 0){
                    echo '<span class="indicator" id="unread_notification">'.$num_notifications.'</span>';
                  }
                   ?>
                </a>
                <a class="sidebar-link img" href="#"><img src="<?php echo $user['profile_pic']; ?>" class="img-fluid rounded-circle" alt="user_img" width="25px"/></a>
              </div>
            </div>
          </div>
      </nav>
      <div class="dropdown_data_window mt-5" style="height: 0px;">
          <input type="hidden" id="dropdown_data_type" value="">
      </div>

      <div class="search_results">
      </div>

      <div class="search_results_footer_empty">
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
