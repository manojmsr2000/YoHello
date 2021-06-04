<?php
include("includes/classes/User.php");
include("includes/classes/Post.php");
include("includes/classes/Notification.php");

require 'config/config.php';
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
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8" content="width=device-width, initial-scale=1.0" description="comment frame">
    <title></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style media="screen">
      *{
      color: #fff;
      font-size: 15px;
      font-family: Arial,Helvetica, sans-serif;
      }
    </style>
  </head>
  <body>

    <script>
      function toggle(){
        let element = document.getElementById("comment_section");
        if(element.style.display == "block")
          element.style.display = 'none';
        else
          element.style.display = 'block';
      }
    </script>

    <?php

      $post_id = '';
      if(isset($_GET['post_id'])){
        $post_id = $_GET['post_id'];
      }

      $user_query = mysqli_query($con, "SELECT added_by, user_to from posts where id = '$post_id'");
      $row = mysqli_fetch_array($user_query);
      if(isset($row)){
      $posted_to = $row['added_by'];
      $user_to = $row['user_to'];
    }
      if(isset($_POST['postComment'.$post_id])){
        $post_body = $_POST['post_body'];
        $post_body = mysqli_real_escape_string($con, $post_body);
        $date_time_now = date("Y-m-d H:i:s");
        $insert_post = mysqli_query($con,"INSERT INTO comments values ('','$post_body','$userLoggedIn','$posted_to','$date_time_now','no','$post_id')");
        if($posted_to != $userLoggedIn){
          $notification = new Notification($con, $userLoggedIn);
          $notification->insertNotification($post_id, $posted_to, "comment");
        }
        if($user_to != 'none' && $user_to!=$userLoggedIn){
          $notification = new Notification($con, $userLoggedIn);
          $notification->insertNotification($post_id, $user_to, "profile_comment");
        }

        $get_commenters = mysqli_query($con, "SELECT * FROM comments where post_id='$post_id'");
        $notified_users = array();
        while($row=mysqli_fetch_array($get_commenters)){
          if($row['posted_by'] != $posted_to && $row['posted_by'] != $user_to && $row['posted_by']!=$userLoggedIn
        && !in_array($row['posted_by'],$notified_users)){
          $notification = new Notification($con, $userLoggedIn);
          $notification->insertNotification($post_id, $row['posted_by'], "comment_non_owner");
          array_push($notified_users, $row['posted_by']);
          }
        }
        echo "<p>Comment Posted!</p>";

      }
    ?>

    <!-- Taking comments -->
    <form action="comment_frame.php?post_id=<?php echo $post_id; ?>" method="POST" id="comment_form" name="postComment<?php echo $post_id ?>">
      <textarea name = "post_body" placeholder="Write something.."></textarea><br>
      <input type="submit" name="postComment<?php echo $post_id ?>" value = "Comment">
    </form>
<!-- Displaying comments -->
    <?php

    $get_comments = mysqli_query($con, "SELECT * FROM `comments` where post_id='$post_id'");
    $count = mysqli_num_rows($get_comments);
    if($count!=0){
      while($comment = mysqli_fetch_array($get_comments)){
        $comment_body = $comment['post_body'];
        $posted_to = $comment['posted_to'];
        $posted_by = $comment['posted_by'];
        $date_added = $comment['date_added'];
        $removed = $comment['removed'];

        $date_time_now = date("Y-m-d H:i:s");
        $start_date = new DateTime($date_added);
        $end_date = new DateTime($date_time_now);
        $interval = $start_date->diff($end_date); //Difference between dates
        if($interval->y >=1){
          if($interval == 1){
            $time_message = $interval->y." year ago";
          }
          else{
            $time_message = $interval->y." years ago";
          }
        }
        else if($interval->m >=1){
          if($interval->d == 0){
            $days = " ago";
          }
          else if($interval->d==1){
            $days = $interval->d." day ago";
          }
          else{
            $days = $interval->d." days ago";
          }

          if($interva->m == 1){
            $time_message = $interval->m." month".$days;
          }
          else{
            $time_message = $interval->m." months".$days;
          }
        }
        else if($interval->d >= 1){
          if($interval->d == 1){
            $time_message = "Yesterday";
          }
          else{
            $time_message = $interval->d." days ago";
          }
        }
        else if($interval->h >= 1){
          if($interval->h == 1){
            $time_message = " an hour ago";
          }
          else{
            $time_message = $interval->h." hours ago";
          }
        }
        else if($interval->i >= 1){
          if($interval->i == 1){
            $time_message = " minute ago";
          }
          else{
            $time_message = $interval->i." minutes ago";
          }
        }
        else{
          if($interval->s < 30){
            $time_message = "Just now";
          }
          else{
            $time_message = $interval->s." seconds ago";
          }
        }

        $user_obj = new User($con, $posted_by);
        ?>
        <div class="comment_section">
          <a href="<?php echo $posted_by; ?>" target="_parent"><img src="<?php echo $user_obj->getProfilePic(); ?>" title=<?php echo $posted_by; ?> style="float: left;" height='30'></a>
          <a href="<?php echo $posted_by; ?>" target="_parent"><b><?php echo $user_obj->getFirstAndLastName(); ?></b></a>
          &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $time_message."<br>".$comment_body;?>
          <hr>
        </div>
        <?php
      }
    }
    else{
      echo "<center>
      <br><br>
      No comments to show!
      </center>";
    }
    ?>



  </body>
</html>
