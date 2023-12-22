<?php
include '../assets/config.php';
if (isset($_POST['status']) AND isset($_POST['reservationID']) AND isset($_POST['foodType']) AND isset($_POST['feedingInstructions'])) {
  $status=$_POST['status'];
  $reservationID=$_POST['reservationID'];
  $foodType=mysqli_real_escape_string($conn, $_POST['foodType']);
  $feedingInstructions=mysqli_real_escape_string($conn, trim($_POST['feedingInstructions']));
  if (isset($_POST['specialNotes']) AND $_POST['specialNotes']!='') {
    $specialNotes=mysqli_real_escape_string($conn, trim($_POST['specialNotes']));
  } else {
    $specialNotes=NULL;
  }
  $sql_next_cat_food_id="SELECT AUTO_INCREMENT AS nextCatFoodID FROM information_schema.TABLES WHERE TABLE_SCHEMA='boarding' AND TABLE_NAME='cats_food'";
  $result_next_cat_food_id=$conn->query($sql_next_cat_food_id);
  $row_next_cat_food_id=$result_next_cat_food_id->fetch_assoc();
  $catFoodID=$row_next_cat_food_id['nextCatFoodID'];
  $sql_add_food="INSERT INTO cats_food (catFoodID, catReservationID, foodType, feedingInstructions, specialNotes, status) VALUES ('$catFoodID', '$reservationID', '$foodType', '$feedingInstructions', '$specialNotes', '$status')";
  $conn->query($sql_add_food);
  $sql_tags="SELECT tagID FROM tags WHERE forCats='Yes' ORDER BY tagID";
  $result_tags=$conn->query($sql_tags);
  while ($row_tags=$result_tags->fetch_assoc()) {
    $tagID=$row_tags['tagID'];
    if ($_POST['tag' . $tagID]=='Yes') {
      $sql_add_tag="INSERT INTO cats_tags (catReservationID, tagID) VALUES ('$reservationID', '$tagID')";
      $conn->query($sql_add_tag);
    }
  }
}
?>
