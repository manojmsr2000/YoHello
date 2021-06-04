<?php
include("includes/header.php");
if(isset($_GET['q'])){
  $query = $_GET['q'];
} else {
  $query = "";
}

if(isset($_GET['type'])){
  $type = $_GET['type'];
} else {
  $type = "name";
}

?>

<main class = "content">
  <div class="container-fluid text-light">
     <?php
      if($query == "")
        echo "Please enter something in the search-box!";
      else {
        if($type == 'username')
          $usersReturnedQuery = mysqli_query($con, "SELECT * from users where username like '$query%' and user_closed='no'");
        else {
          $names = explode(" ", $query);

          if(count($names) == 3)
            $usersReturnedQuery = mysqli_query($con, "SELECT * from users where (first_name like '$names[0]%' and last_name like '$names[2]%') and user_closed='no'");
          else if(count($names) == 2)
          $usersReturnedQuery = mysqli_query($con, "SELECT * from users where (first_name like '$names[0]%' and last_name like '$names[1]%') and user_closed='no'");
          else
            $usersReturnedQuery = mysqli_query($con, "SELECT * from users where (first_name like '$names[0]%' or last_name like '$names[0]%') and user_closed='no'");
        }
        if(mysqli_num_rows($usersReturnedQuery) == 0){
          echo "We can't find anyone with a ".$type." like: ".$query."";
        } else {
          echo mysqli_num_rows($usersReturnedQuery). " result(s) found: <br /> <br />";
        }
        echo "<p id='grey'>Try searching for: </p>";
        echo "<a href='search.php?q=".$query."&type=name'>Names</a>, <a href='search.php?q=".$query."&type=username'>Usernames</a><hr />";

        while($row = mysqli_fetch_array($usersReturnedQuery)){
          $user_obj = new User($con, $user['username']);

          $button = "";
          $mutual_friends = "";
          if($user['username'] != $row['username']){
            //Show buttons depending on friendship status
            if($user_obj->isFriend($row['username']))
              $button = "<input type='submit' name='".$row['username']."' class='btn btn-danger' value='Remove Friend' />";
            else if($user_obj->didReceiveRequest($row['username']))
             $button = "<input type='submit' name='".$row['username']."' class='btn btn-info' value='Respond to request' />";
            else if($user_obj->didSendRequest($row['username']))
              $button = "<input name='".$row['username']."' class='btn btn-light' value='Request sent' />";
            else
              $button = "<input type='submit' name='".$row['username']."' class='btn btn-success' value='Add Friend' />";
            $mutual_friends = $user_obj->getMutualFriends($row['username'])." friend(s) in common<br />";

            //Button forms
            if(isset($_POST[$row['username']])){
              if($user_obj->isFriend($row['username'])){
                $user_obj->removeFriend($row['username']);
                header("Location: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
              }
              else if($user_obj->didReceiveRequest($row['username'])){
                header("Location: requests.php");
              }
              else if($user_obj->didSendRequest($row['username'])){

              }
              else{
                $user_obj->sendRequest($row['username']);
                header("Location: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
              }
            }

          }
          echo "<div class='search_result'>
          <div class='float-start me-3'>
            <a href='".$row['username']."'><img src='".$row['profile_pic']."' class='rounded-3' height='80px' /></a>
          </div>
          <a href='".$row['username']."'>".$row['first_name']." ".$row['last_name']."
            <p>".$row['username']."</p>
          </a>
          ".$mutual_friends."<br />
          <div>
            <form action='' method='POST'>
            ".$button."
            <br />
            </form>
          </div>
          </div><hr />";
        } //End-while
      }
      ?>
  </div>
</main
