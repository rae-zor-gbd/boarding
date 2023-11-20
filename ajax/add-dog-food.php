<?php
include '../assets/config.php';
if (isset($_POST['status']) AND isset($_POST['reservationID']) AND isset($_POST['foodType']) AND isset($_POST['feedingInstructions'])) {
  $status=$_POST['status'];
  $reservationID=$_POST['reservationID'];
  $foodType=mysqli_real_escape_string($conn, $_POST['foodType']);
  $feedingInstructions=mysqli_real_escape_string($conn, $_POST['feedingInstructions']);
  if (isset($_POST['specialNotes']) AND $_POST['specialNotes']!='') {
    $specialNotes=mysqli_real_escape_string($conn, $_POST['specialNotes']);
  } else {
    $specialNotes=NULL;
  }
  $sql_next_dog_food_id="SELECT AUTO_INCREMENT AS nextDogFoodID FROM information_schema.TABLES WHERE TABLE_SCHEMA='boarding' AND TABLE_NAME='dogs_food'";
  $result_next_dog_food_id=$conn->query($sql_next_dog_food_id);
  $row_next_dog_food_id=$result_next_dog_food_id->fetch_assoc();
  $dogFoodID=$row_next_dog_food_id['nextDogFoodID'];
  $sql_add_food="INSERT INTO dogs_food (dogFoodID, dogReservationID, foodType, feedingInstructions, specialNotes, status) VALUES ('$dogFoodID', '$reservationID', '$foodType', '$feedingInstructions', '$specialNotes', '$status')";
  $conn->query($sql_add_food);
  $sql_tags="SELECT tagID FROM tags WHERE forDogs='Yes' ORDER BY tagID";
  $result_tags=$conn->query($sql_tags);
  while ($row_tags=$result_tags->fetch_assoc()) {
    $tagID=$row_tags['tagID'];
    if ($_POST['tag' . $tagID]=='Yes') {
      $sql_add_tag="INSERT INTO dogs_tags (dogReservationID, tagID) VALUES ('$reservationID', '$tagID')";
      $conn->query($sql_add_tag);
    }
  }
}
?>
