<?php
class Notification{
  private $user_obj;
  private $con;

  public function __construct($con,$user){
    $this->con = $con;
    $this->user_obj = new User($con,$user);
  }

  public function getUnreadNumber(){
    $userLoggedIn = $this->user_obj->getUsername();
    $query = mysqli_query($this->con, "SELECT * from notifications where viewed='no' and user_to='$userLoggedIn'");
    return mysqli_num_rows($query);
  }

  public function insertNotification($post_id, $user_to, $type){
    $userLoggedIn = $this->user_obj->getUsername();
    $userLoggedInName = $this->user_obj->getFirstAndLastName();
    $date_time = date("Y-m-d H:i:s");

    switch($type){
      case "comment":
       $message = $userLoggedInName." commented on your post!";
       break;
     case "like":
      $message = $userLoggedInName." liked your post!";
      break;
     case "profile_post":
      $message = $userLoggedInName." posted on your profile!";
      break;
     case "comment_non_owner":
      $message = $userLoggedInName." commented on a post you commented on!";
      break;
    case "profile_comment":
     $message = $userLoggedInName." commented on your profile post!";
     break;
    }

    $link = "post.php?id=".$post_id;
    $insert_query = mysqli_query($this->con, "INSERT INTO notifications values('', '$user_to', '$userLoggedIn', '$message', '$link', '$date_time', 'no', 'no')");
  }

  public function getNotifications($data, $limit){
    $page = $data['page'];
    $userLoggedIn = $this->user_obj->getUsername();
    $return_string = "";

    if($page == 1)
      $start = 0;
    else
      $start = ($page-1)*$limit;

    $set_viewed_query = mysqli_query($this->con, "UPDATE notifications set viewed='yes' where user_to='$userLoggedIn'");

    $query = mysqli_query($this->con,"SELECT * from notifications where user_to='$userLoggedIn' order by id desc");

    if(mysqli_num_rows($query) == 0){
      echo "<p class='text-light text-center'>You have no notifications!</p>";
    } else {
      $num_iteration = 0; //Number of messages checked
      $count = 1; //number of messages posted
      $style = "";
      while($row = mysqli_fetch_array($query)){

        if($num_iteration++ < $start)
          continue;

        if($count > $limit)
          break;
        else
          $count++;

        $user_from = $row['user_from'];

        $user_data_query = mysqli_query($this->con, "SELECT * from users where username='$user_from'");
        $user_data = mysqli_fetch_array($user_data_query);

        // Time
        $date_time_now = date("Y-m-d H:i:s");
        $start_date = new DateTime($row['datetime']);
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

        $opened = $row['opened'];
        if(isset($row)){
          $style = ($row['opened'] == 'no')?"background-color: #DDEDFF": "";
        }

        $return_string .= "<a href='".$row['link']."' class='list-group-item list-group-item-action bg-dark border-0'>
        <div class='p-3 rounded-3' style='".$style."'>
          <div class='float-start'>
          <img src='".$user_data['profile_pic']."' class='rounded-circle me-2' width='40' height='40' />
          </div>
          <p class='fs-6' id='grey'>
          ".$time_message."
          </p>".$row['message']."
          </div>
        </a>";
      }
      //if notifications were loaded
      if($count > $limit){
        $return_string .= "<input type='hidden' class='nextPageDropDownData' value='". ($page+1) ."' ><input type='hidden' class='noMoreDropDownData' value='false'>";
      } else {
        $return_string .= "<input type='hidden' class='noMoreDropDownData' value='true'><p class='text-light text-center pb-2'>No more notifications to load!</p>";
      }
      return $return_string;
      }
    }

}
?>
