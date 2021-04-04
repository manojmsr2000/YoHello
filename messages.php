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
<main class = "content text-light m-5">
  <div class="container-fluid">
    <div class="row">
      <div class="col col-lg-4">
        <div class="user_details column" id='conversations'>
          <h4>Conversations</h4>
          <div class="loaded_conversations">
            <?php echo $message_obj->getConvos(); ?>
          </div>
          <br>
          <a href="messages.php?u=new">New Message</a>
        </div>
      </div>
      <div class="col col-lg-8">
        <?php
        if($user_to!='new'){
          echo "<h4>You and <a href='$user_to'>".$user_to_obj->getFirstAndLastName()."</a></h4><hr><br>";
          echo "<div class='chat-messages' id='scroll_messages'>";
          echo $message_obj->getMessages($user_to);
          echo "</div>";
        }
        else{
          echo "<h4>New Message</h4>";
        }
        ?>
        <form class="" action="" method="post">
          <?php
            if($user_to == 'new'){
              echo "Select the friend you would like to message. <br><br>";
              echo "To: <input type='text'>";
              echo "<div class='results'></div>";
            }
            else{
              echo "<textarea name='message_body' class='form-control mt-3' placeholder='Write your message ...'></textarea>";
              echo "<input type='submit' name='post_message' class='btn btn-info mt-2' value='send'>";
            }
          ?>
        </form>
    </div>
  </div>
  </div>
</main>
<script>
let div = $('#scroll_messages')[0];
div.scrollTop = div.scrollHeight;
</script>
