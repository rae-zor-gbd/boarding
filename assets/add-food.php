<?php
include 'config.php';
if (isset($_POST['status']) AND isset($_POST['room']) AND isset($_POST['name']) AND isset($_POST['foodType']) AND isset($_POST['feedingInstructions'])) {
  $status=$_POST['status'];
  $room=$_POST['room'];
  $name=mysqli_real_escape_string($conn, $_POST['name']);
  $foodType=mysqli_real_escape_string($conn, $_POST['foodType']);
  $feedingInstructions=mysqli_real_escape_string($conn, $_POST['feedingInstructions']);
  $sql_next_dog_id="SELECT AUTO_INCREMENT AS nextDogID FROM information_schema.TABLES WHERE TABLE_SCHEMA='kennel' AND TABLE_NAME='dogs'";
  $result_next_dog_id=$conn->query($sql_next_dog_id);
  $row_next_dog_id=$result_next_dog_id->fetch_assoc();
  $dogID=$row_next_dog_id['nextDogID'];
  $sql_book_room="INSERT INTO dogs (dogID, roomID, dogName, foodType, feedingInstructions, status) VALUES ('$dogID', '$room', '$name', '$foodType', '$feedingInstructions', '$status')";
  $conn->query($sql_book_room);
}
?>
