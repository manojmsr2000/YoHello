<?php
  include("../../config/config.php");
  include("../classes/User.php");
  include("../classes/Notification.php");

  $limit = 7; //Number of Messages

  $notification = new Notification($con, $_REQUEST['userLoggedIn']);
  echo $notification->getNotifications($_REQUEST, $limit);

?>
