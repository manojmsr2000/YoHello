<?php
include("includes/header.php");

if(isset($_GET['id'])){
  $id = $_GET['id'];
} else {
  $id=0;
}

$num_friends = (substr_count($user['friend_array'],",")) - 1;


?>

<main class = "content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 col-lg-3">
        <div class="pic_left">
          <img src="<?php echo $user['profile_pic'] ?>" alt="profile-pic" class="img-fluid rounded-3 mb-2">
        </div>
        <div class="font-weight-bold text-light"><h4><?php echo $user['first_name']." ".$user['last_name']; ?></h4></div>
        <div class="profile_info">
          <p class="text-light"><?php echo "Posts: ".$user['num_posts']; ?></p>
          <p class="text-light"><?php echo "Likes: ".$user['num_likes']; ?></p>
          <p class="text-light"><?php echo "Friends: ".$num_friends; ?></p>

        </div>
      </div>
      <div class="col-12 col-lg-9 mt-3">
        <div class="posts_area"></div>
        <?php
          $post = new Post($con, $userLoggedIn);
          $post->getSinglePost($id);
        ?>
      </div>
    </div>
  </div>
</main>
