<?php
include '../assets/config.php';
if (isset($_POST['status']) AND isset($_POST['id']) AND isset($_POST['room']) AND isset($_POST['dogName']) AND isset($_POST['foodType']) AND isset($_POST['feedingInstructions'])) {
  $status=$_POST['status'];
  $id=$_POST['id'];
  $roomID=mysqli_real_escape_string($conn, $_POST['room']);
  $dogName=mysqli_real_escape_string($conn, $_POST['dogName']);
  $foodType=mysqli_real_escape_string($conn, $_POST['foodType']);
  $feedingInstructions=mysqli_real_escape_string($conn, $_POST['feedingInstructions']);
  $sql_update="UPDATE dogs SET roomID='$roomID', dogName='$dogName', foodType='$foodType', feedingInstructions='$feedingInstructions' WHERE dogID='$id'";
  $conn->query($sql_update);
}
?>
