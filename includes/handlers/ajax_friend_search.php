<?php
include("../../config/config.php");
include("../classes/User.php");

$query = $_POST['query'];
$userLoggedIn = $_POST['userLoggedIn'];

$names = explode(" ", $query);

if(strpos($query, "_")!== false){
  $userReturned = mysqli_query($con, "SELECT * from users where username like '$query%' and user_closed='no' LIMIT 8");
}
else if(count($names)==2){
  $usersReturned = mysqli_query($con, "SELECT * from users where (first_name LIKE '%$names[0]%' and last_name like '%$names[1]%') and user_closed='no' limit 8");
}
else{
  $usersReturned = mysqli_query($con, "SELECT * from users where (first_name LIKE '%$names[0]%' or last_name like '%$names[0]%') and user_closed='no' limit 8");
}
if($query!=""){
  while($row = mysqli_fetch_array($usersReturned)){
    $user = new User($con, $userLoggedIn);
    if($row['username']!= $userLoggedIn){
      $mutual_friends = $user->getMutualFriends($row['username'])." friend(s) in common";
    }
    else{
      $mutual_friends = "";
    }
    if($user->isFriend($row['username'])){
      echo "<br><div class='resultDisplay text-light'>
      <a href='messages.php?u=".$row['username']."' class='text-light'>
        <div class='liveSearchProfilePic'>
          <img src='".$row['profile_pic']."' class='rounded-circle float-start me-3'>
        </div>
        <div class='liveSearchText'>
          ".$row['first_name']." ".$row['last_name']."
          <p>
          ".$row['username']."
          </p>
          <p class='text-muted'>".$mutual_friends."
          </p>
        </div>
        </a>
      </div>";    }
  }
}
?>
