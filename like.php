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
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="assets/css/style.css">
  </head>
  <body>
    <?php
    $post_id = '';
    if(isset($_GET['post_id'])){
      $post_id = $_GET['post_id'];
    }
    $user_liked='';
    $total_likes='';
    $get_likes = mysqli_query($con, "SELECT likes, added_by from posts where id='$post_id'");
    $row = mysqli_fetch_array($get_likes);
    if(isset($row)){
      $total_likes = $row['likes'];
      $user_liked = $row['added_by'];
    }
    $user_details_query = mysqli_query($con,"SELECT * FROM users where username='$user_liked'");
    $row = mysqli_fetch_array($user_details_query);
    if(isset($row)){
      $total_user_likes= $row['num_likes'];
    }

    //Like Button
    if(isset($_POST['like_button'])){
      $total_likes++;
      $query = mysqli_query($con, "UPDATE posts SET likes='$total_likes' where id='$post_id'");
      $total_user_likes++;

      $user_likes = mysqli_query($con, "UPDATE users SET num_likes='$total_user_likes' where username='$user_liked'");
      $insert_user = mysqli_query($con,"INSERT INTO likes values('','$userLoggedIn','$post_id')");
      //Insert Notification
      if($user_liked != $userLoggedIn){
        $notification = new Notification($con, $userLoggedIn);
        $notification->insertNotification($post_id, $user_liked, "like");
      }
    }
    //Unlike Button
    if(isset($_POST['unlike_button'])){
      $total_likes--;
      $query = mysqli_query($con, "UPDATE posts SET likes='$total_likes' where id='$post_id'");
      $total_user_likes--;
      $user_likes = mysqli_query($con, "UPDATE users SET num_likes='$total_user_likes' where username='$user_liked'");
      $insert_user = mysqli_query($con,"DELETE FROM likes where username='$userLoggedIn' AND post_id='$post_id'");
    }
    //Check for previous likes
    $check_query = mysqli_query($con,"SELECT * FROM likes where username='$userLoggedIn' AND post_id='$post_id'");
    $num_rows = mysqli_num_rows($check_query);
    if($num_rows > 0){
      echo "<form action='like.php?post_id=".$post_id."' method='POST'>
            <input type='submit' class='comment_like' name='unlike_button' value='Unlike'>
            <div class='like_value'>
              ".$total_likes." Like(s)
            </div>
      </form>";
    }
    else{
      echo "<form action='like.php?post_id=".$post_id."' method='POST'>
            <input type='submit' class='comment_like' name='like_button' value='Like'>
            <div class='like_value'>
              ".$total_likes." Like(s)
            </div>
      </form>";
    }

    ?>
  </body>
</html>
