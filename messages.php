<?php

include('includes/header.php');

$message_obj = new Message($con,$userLoggedIn);

if(isset($_GET['u']))
  $user_to = $_GET['u'];
else {
  $user_to= $message_obj->getMostRecentUser();
  if($user_to == false){
    $user_to = 'new';
  }
}
$user_to_obj = new User($con, $user_to);
if($user_to!='new'){
  $user_to_obj = new User($con, $user_to);
}
if(isset($_POST['post_message'])){
if(isset($_POST['message_body'])){
  $body = mysqli_real_escape_string($con,$_POST['message_body']);
  $date = date('Y-m-d H:i:s');
  $message_obj->sendMessage($user_to,$body,$date);
}
}
?>
<main class="content">
	<div class="container-fluid text-light">

		<h1 class="h3 mb-3">Chats</h1>

		<div class="card bg-dark text-light">
			<div class="row no-gutters">
				<div class="col-12 col-lg-5 col-xl-3 border-right">
          <div class="m-3 p-2">
            <a href="messages.php?u=new" class='text-light'><i class="fas fa-plus"></i> New Message</a>
          </div>
          <?php echo $message_obj->getConvos(); ?>
					<hr class="d-block d-lg-none mt-1 mb-0" />
				</div>
				<div class="col-12 col-lg-7 col-xl-9 mt-3">
              <div class="position-relative">
                <?php
                if($user_to!='new'){
                  echo "<img src='".$user_to_obj->getProfilePic()."' class='rounded-circle me-1' width='40' height='40'>";
                  echo "<a href='$user_to' class='ps-3 text-light'>".$user_to_obj->getFirstAndLastName()."</a><hr><br>";
                  echo "<div class='chat-messages p-4' id='scroll_messages'>";
                  echo $message_obj->getMessages($user_to);
                  echo "</div>";
                }
                else{
                  echo "<h4>New Message</h4>";
                }
                ?>
						</div>

            <div class="flex-grow-0 py-3 px-4 border-top">
              <form class="" action="" method="post">
                  <?php
                    if($user_to == 'new'){
                      echo "Select the friend you would like to message. <br><br>";
                      ?>
                      To: <input type='text' class="form-control mt-2" onkeyup='getUsers(this.value, "<?php echo $userLoggedIn; ?>")' name='q' placeholder='Name' autocomplete='off' id='search_text_input' >
                      <?php
                      echo "<div class='results'></div>";
                    }
                    else{
                      echo "<textarea name='message_body' class='form-control mt-3 bg-dark text-light' placeholder='Write your message ...'></textarea>";
                      echo "<input type='submit' name='post_message' class='btn btn-info mt-2' value='send'>";
                    }
                  ?>
              </form>
  					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<script>
let div = (document).getElementById('scroll_messages');
div.scrollTop = div.scrollHeight;
</script>
