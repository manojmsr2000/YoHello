<?php
include("includes/header.php");

if(isset($_POST['post'])){
  $post = new Post($con, $userLoggedIn);
  $post->submitPost($_POST['post_text'],'none');
  header("Location: index.php");
}
// session_destroy();
?>

  <main class = "content">
    <div class="container-fluid">
      <div class="header">
					<h1 class="header-title">
						Welcome back, <?php echo $user['first_name'];?>!
					</h1>
			</div>
      <form class="post_form" action="index.php" method="post">
        <textarea name="post_text" id="post_text" class = "form-control" rows="3" cols="80" placeholder="What's on your mind ?"></textarea>
        <input class = "btn btn-outline-light rounded-pill" type="submit" name="post" value="Post">
      </form>
      <hr>
      <br>

      <div class="posts_area"></div>
      <img id="loading" src="assets/images/icons/loading.gif">

    </div>
  </main>
</div>
<script>
var userLoggedIn = '<?php echo $userLoggedIn; ?>';

$(document).ready(function() {

  $('#loading').show();

  //Original ajax request for loading first posts
  $.ajax({
    url: "includes/handlers/ajax_load_posts.php",
    type: "POST",
    data: "page=1&userLoggedIn=" + userLoggedIn,
    cache:false,

    success: function(data) {
      $('#loading').hide();
      $('.posts_area').html(data);
    }
  });

  $(window).scroll(function() {
  //$('#load_more').on("click", function() {
    var height = $('.posts_area').height();
    var scroll_top = $(this).scrollTop();
    var page = $('.posts_area').find('.nextPage').val();
    var noMorePosts = $('.posts_area').find('.noMorePosts').val();

    if (($(window).scrollTop() + $(window).height() > $(document).height()-2) && noMorePosts == 'false') {
    //if (noMorePosts == 'false') {
      $('#loading').show();

      var ajaxReq = $.ajax({
        url: "includes/handlers/ajax_load_posts.php",
        type: "POST",
        data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
        cache:false,

        success: function(response) {
          $('.posts_area').find('.nextPage').remove(); //Removes current .nextpage
          $('.posts_area').find('.noMorePosts').remove(); //Removes current .nextpage
          $('.posts_area').find('.noMorePostsText').remove(); //Removes current .nextpage

          $('#loading').hide();
          $('.posts_area').append(response);
        }
      });

    } //End if

    return false;

  }); //End (window).scroll(function())


});

</script>
<script>
  $(document).ready(function(){
    $(".main .hamburger").click(function(){
      $("#sidebar").toggleClass("toggled");
    });
  });
</script>
</body>
</html>
