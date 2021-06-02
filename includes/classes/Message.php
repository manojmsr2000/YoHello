<?php
class Message{
  private $user_obj;
  private $con;

  public function __construct($con,$user){
    $this->con = $con;
    $this->user_obj = new User($con,$user);
  }

  public function getMostRecentUser(){
    $userLoggedIn = $this->user_obj->getUsername();

    $query = mysqli_query($this->con, "SELECT user_to, user_from from messages where user_to='$userLoggedIn' or user_from='$userLoggedIn' order by id
      desc limit 1");
    if(mysqli_num_rows($query)==0){
      return false;
    }

    $row = mysqli_fetch_array($query);
    $user_to = $row['user_to'];
    $user_from = $row['user_from'];

    if($user_to!=$userLoggedIn){
      return $user_to;
    }
    else{
      return $user_from;
    }
  }

  public function sendMessage($user_to, $body, $date){
    if($body != ""){
      $userLoggedIn = $this->user_obj->getUsername();
      $query = mysqli_query($this->con,"INSERT INTO messages values('','$user_to','$userLoggedIn','$body','$date','no','no','no')");
    }
  }

  public function getMessages($otherUser){
    $userLoggedIn = $this->user_obj->getUsername();
    $data = "";
    $query = mysqli_query($this->con,"UPDATE messages SET opened='yes' where user_to='$userLoggedIn' and user_from='$otherUser'");
    $get_messages_query = mysqli_query($this->con, "SELECT * FROM messages where (user_to='$userLoggedIn' and user_from='$otherUser') or (user_from='$userLoggedIn' and user_to='$otherUser')");
    while($row = mysqli_fetch_array($get_messages_query)){
      $user_to = $row['user_to'];
      $user_from = $row['user_from'];
      $body = $row['body'];

      $div_top = ($user_to == $userLoggedIn) ? "<div class='chat-message-left text-dark pb-1'>
        <div class='flex-shrink-1 bg-success rounded py-2 px-3'>" : "<div class='chat-message-right pb-1 text-dark'>
        <div class='flex-shrink-1 bg-primary rounded py-2 px-3'>";
      $data = $data.$div_top.$body."</div>
    </div>";
    }
    return $data;
  }

  public function getLatestMessage($userLoggedIn, $user2){
    $details_array = array();

    $query = mysqli_query($this->con, "SELECT body,user_to, date from messages where (user_to='$userLoggedIn' and user_from='$user2') or
     (user_to='$user2' and user_from='$userLoggedIn') ORDER by id desc limit 1");
    $row = mysqli_fetch_array($query);
    $sent_by = ($row['user_to'] == $userLoggedIn) ? "They said: ": "You said: ";

    $date_time_now = date("Y-m-d H:i:s");
    $start_date = new DateTime($row['date']);
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

      if($interval->m == 1){
        $time_message = $interval->m." month ".$days;
      }
      else{
        $time_message = $interval->m." months ".$days;
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

    array_push($details_array, $sent_by);
    array_push($details_array, $row['body']);
    array_push($details_array, $time_message);

    return $details_array;
  }

  public function getConvos(){
    $userLoggedIn = $this->user_obj->getUsername();
    $return_string = "";
    $convos = array();
    $latest_message_details = "";

    $query = mysqli_query($this->con,"SELECT user_to, user_from from messages where user_to='$userLoggedIn' or user_from='$userLoggedIn' order by id desc");
    while($row = mysqli_fetch_array($query)){
      $user_to_push = ($row['user_to'] != $userLoggedIn) ? $row['user_to'] : $row['user_from'];

      if(!in_array($user_to_push, $convos)){
        array_push($convos, $user_to_push);
      }
    }
    foreach($convos as $username){
      $user_found_obj = new user($this->con, $username);
      $latest_message_details = $this->getLatestMessage($userLoggedIn, $username);

      $dots = (strlen($latest_message_details[1]) >= 12) ? "..." : "";
      $split = str_split($latest_message_details[1],12);
      $split = $split[0].$dots;
      $return_string .= "<a href='messages.php?u=$username' class='list-group-item list-group-item-action border-0 bg-dark text-light'>
        <div>
          <img src='".$user_found_obj->getProfilePic()."' class='rounded-circle me-2' width='40' height='40'>".$user_found_obj->getFirstAndLastName()."
          <span class='text-muted text-nowrap mt-2 ms-5'>". $latest_message_details[2] ."</span>
          <p class='text-muted ms-5'>". $latest_message_details[0] .$split."</p>
        </div>
      </a>";
    }
    return $return_string;
  }

  public function getConvosDropDown($data, $limit){
    $page = $data['page'];
    $userLoggedIn = $this->user_obj->getUsername();
    $return_string = "";
    $convos = array();
    $latest_message_details = "";

    if($page == 1)
      $start = 0;
    else
      $start = ($page-1)*$limit;

      $set_viewed_query = mysqli_query($this->con, "UPDATE messages set viewed='yes' where user_to='$userLoggedIn'");

    $query = mysqli_query($this->con,"SELECT user_to, user_from from messages where user_to='$userLoggedIn' or user_from='$userLoggedIn' order by id desc");
    while($row = mysqli_fetch_array($query)){
      $user_to_push = ($row['user_to'] != $userLoggedIn) ? $row['user_to'] : $row['user_from'];

      if(!in_array($user_to_push, $convos)){
        array_push($convos, $user_to_push);
      }
    }

    $num_iteration = 0; //Number of messages checked
    $count = 1; //number of messages posted
    $style = "";
    foreach($convos as $username){

      if($num_iteration++ < $start)
        continue;

      if($count > $limit)
        break;
      else
        $count++;

      $is_unread_query = mysqli_query($this->con, "SELECT opened from messages where user_to='$userLoggedIn' and user_from='$username' order by id desc");
      $row = mysqli_fetch_array($is_unread_query);
      if(isset($row)){
        $style = ($row['opened'] == 'no')?"background-color: #DDEDFF": "";
      }
      $user_found_obj = new user($this->con, $username);
      $latest_message_details = $this->getLatestMessage($userLoggedIn, $username);

      $dots = (strlen($latest_message_details[1]) >= 12) ? "..." : "";
      $split = str_split($latest_message_details[1],12);
      $split = $split[0].$dots;
      $return_string .= "<a href='messages.php?u=$username' class='list-group-item list-group-item-action border-0 bg-dark'>
        <div style='". $style ."'>
          <img src='".$user_found_obj->getProfilePic()."' class='rounded-circle me-2' width='40' height='40'>".$user_found_obj->getFirstAndLastName()."
          <span class='text-muted text-nowrap mt-2 ms-5'>". $latest_message_details[2] ."</span>
          <p class='ms-5'>". $latest_message_details[0] .$split."</p>
        </div>
      </a>";
    }
    //if posts were loaded
    if($count > $limit){
      $return_string .= "<input type='hidden' class='nextPageDropDownData' value='". ($page+1) ."' ><input type='hidden' class='noMoreDropDownData' value='false'>";
    } else {
      $return_string .= "<input type='hidden' class='noMoreDropDownData' value='true'><p class='text-muted bg-dark' style='text-align: center'>No more messages to load!</p>";

    }
    return $return_string;
  }

  public function getUnreadNumber(){
    $userLoggedIn = $this->user_obj->getUsername();
    $query = mysqli_query($this->con, "SELECT * from messages where viewed='no' and user_to='$userLoggedIn'");
    return mysqli_num_rows($query);
  }
}

 ?>
