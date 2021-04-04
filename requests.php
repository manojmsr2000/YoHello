<?php
include("includes/header.php");
?>
<main class = "content friend_req">
  <div class="container-fluid text-light">
    <h4>Friend Requests</h4>
    <br>
    <?php
      $query = mysqli_query($con, "SELECT * FROM friend_requests where user_to='$userLoggedIn'");
      if(mysqli_num_rows($query) == 0){
        echo "You have no friend requests!";
      }
      else{
        while($row = mysqli_fetch_array($query)){
          $user_from = $row['user_from'];
          $user_from_obj = new User($con, $user_from);
          echo $user_from_obj->getFirstAndLastName()." sent you a friend request!";
          $user_from_friend_array = $user_from_obj->getFriendArray();

          if(isset($_POST['accept_request'.$user_from])){
            $add_friend_query = mysqli_query($con,"UPDATE users set friend_array=CONCAT(friend_array,'$user_from,') where username='$userLoggedIn'");
            $add_friend_query = mysqli_query($con,"UPDATE users set friend_array=CONCAT(friend_array,'$userLoggedIn,') where username='$user_from'");
            $delete_query = mysqli_query($con,"DELETE FROM friend_requests where user_to='$userLoggedIn' and user_from='$user_from'");
            echo "You are now friends!";
            header("location: requests.php");
          }
          if(isset($_POST['ignore_request'.$user_from])){
            $delete_query = mysqli_query($con,"DELETE FROM friend_requests where user_to='$userLoggedIn' and user_from='$user_from'");
            echo "You ignored!";
            header("location: requests.php");
          }
          ?>
          <form action="requests.php" method="post">
            <button type="submit" name="accept_request<?php echo $user_from; ?>" class="btn btn-success mt-2 mr-2" id='accept_button'>Accept</button>
            <button type="submit" name="ignore_request<?php echo $user_from; ?>" class="btn btn-danger mt-2 mr-2" id='ignore_button'>Ignore</button>
          </form>
          <br>
          <?php
        }
      }
    ?>
  </div>
</main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
