<?php
include("includes/header.php");

if(isset($_GET['profile_username'])){
  $username = $_GET['profile_username'];
  $user_details_query = mysqli_query($con,"SELECT * FROM users where username='$username'");
  $user_array = mysqli_fetch_array($user_details_query);
  $num_friends = (substr_count($user_array['friend_array'],",")) - 1;
}

if(isset($_POST['remove_friend'])){
  $user = new User($con,$userLoggedIn);
  $user->removeFriend($username);
}

if(isset($_POST['add_friend'])){
  $user = new User($con,$userLoggedIn);
  $user->sendRequest($username);
}

if(isset($_POST['respond_request'])){
  header("Location: requests.php");
}

?>

  <main class = "content">
    <div class="container-fluid">
      <div class="pic_left">
        <img src="<?php echo $user_array['profile_pic'] ?>" alt="" class="img-fluid rounded-circle mb-2">
      </div>
      <div class="font-weight-bold text-light"><h4><?php echo $user_array['first_name']." ".$user_array['last_name']; ?></h4></div>
      <div class="profile_info">
        <p class="text-light"><?php echo "Posts: ".$user_array['num_posts']; ?></p>
        <p class="text-light"><?php echo "Likes: ".$user_array['num_likes']; ?></p>
        <p class="text-light"><?php echo "Friends: ".$num_friends; ?></p>
      </div>
      <form action="<?php echo $username; ?>" method="POST">
        <?php

        $profile_user_obj = new User($con, $username);
        if($profile_user_obj->isClosed()){
          header("Location: user_closed.php");
        }

        $logged_in_user_obj = new User($con,$userLoggedIn);
        if($userLoggedIn != $username){
          if($logged_in_user_obj->isFriend($username)){
            echo "<button type='submit' name='remove_friend' class='btn btn-danger'>Remove Friend</button><br>";
          }
          else if($logged_in_user_obj->didReceiveRequest($username)){
            echo "<button type='submit' name='respond_request' class='btn btn-warning'>Respond to Request</button><br>";
          }
          else if($logged_in_user_obj->didSendRequest($username)){
            echo "<button type='submit' name='' class='btn btn-secondary'>Request Sent</button><br>";
          }
          else{
            echo "<button type='submit' name='add_friend' class='btn btn-info'>Add Friend</button><br>";
          }
        }

        ?>
      </form>
      <br>
      <button type="submit" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#post_form">
        Post Something
      </button>
    </div>
  </main>

  <!-- Modal -->
  <div class="modal fade" id="post_form" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Post Something</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>This will appear on the user's profile page and also their newsfeed for your friends to see!</p>
          <form class="profile_post" action="" method="POST">
            <div class="mb-3">
              <textarea class="form-control" id="exampleFormControlTextarea1" name='post_body' rows="3"></textarea>
              <input type="hidden" class="form-control"  placeholder="Password" name='user_from' value="<?php echo $userLoggedIn; ?>">
              <input type="hidden" class="form-control"  placeholder="Password" name='user_to' value="<?php echo $username; ?>">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" name='post_button' id='submit_profile_post'>Post</button>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  $(document).ready(function(){
    $(".main .hamburger").click(function(){
      $("#sidebar").toggleClass("toggled");
    });
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
<script src="assets/javascript/yohello.js" charset="utf-8"></script>
</body>
</html>
