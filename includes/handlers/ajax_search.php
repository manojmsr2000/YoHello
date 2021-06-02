<?php
include("../../config/config.php");
include("../../includes/classes/User.php");

$query = $_POST['query'];
$userLoggedIn = $_POST['userLoggedIn'];

$names = explode(" ", $query);

if(strpos($query, '_') !== false)
  $usersReturnedQuery = mysqli_query($con, "SELECT * from users where username like '$query%' and user_closed='no' limit 8");
else if(count($names) == 2)
  $usersReturnedQuery = mysqli_query($con, "SELECT * from users where (first_name like '$names[0]%' and last_name like '$names[1]%') and user_closed='no' limit 8");
else
  $usersReturnedQuery = mysqli_query($con, "SELECT * from users where (first_name like '$names[0]%' or last_name like '$names[0]%') and user_closed='no' limit 8");
if($query != ""){
  while($row = mysqli_fetch_array($usersReturnedQuery)){
    $user = new User($con, $userLoggedIn);
    if($row['username'] != $userLoggedIn)
      $mutual_friends = $user->getMutualFriends($row['username'])." friends in common";
    else
      $mutual_friends = "";

      echo "<div class='resultDisplay'>
      <a href='".$row['username']."' style='color: #1485BD'>
        <div class='liveSearchProfilePic'>
        <img src='".$row['profile_pic']."' />
        </div>
        <div class='liveSearchText'>
        ".$row['first_name']." ".$row['last_name']."
        <p>".$row['username']."</p>
        <p id='grey'>".$mutual_friends."</p>
        </div>
      </a>
      </div>";
  }
}

?>
