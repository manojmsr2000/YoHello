<?php
ob_start();
session_start();

$servername = "fdb32.awardspace.net"; 
$username = "3862591_yohello"; 
$password = "Hello@123Man!"; 
$dbname = "3862591_yohello";

$timezone = date_default_timezone_set("Asia/Kolkata");
$con = mysqli_connect($servername, $username, $password, $dbname);
if(mysqli_connect_errno()){
	echo("Failed to connect:".mysqli_connect_errno());
}
?>
