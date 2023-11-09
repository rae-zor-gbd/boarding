<?php
include '../assets/config.php';
if (isset($_POST['status']) AND isset($_POST['reservationID']) AND isset($_POST['foodType']) AND isset($_POST['feedingInstructions']) AND isset($_POST['foodAllergies']) AND isset($_POST['noSlipBowl']) AND isset($_POST['plasticBowl']) AND isset($_POST['slowFeeder']) AND isset($_POST['elevatedFeeder']) AND isset($_POST['separateToFeed']) AND isset($_POST['grazer'])) {
  $status=$_POST['status'];
  $reservationID=$_POST['reservationID'];
  $foodType=mysqli_real_escape_string($conn, $_POST['foodType']);
  $feedingInstructions=mysqli_real_escape_string($conn, $_POST['feedingInstructions']);
  if (isset($_POST['specialNotes']) AND $_POST['specialNotes']!='') {
    $specialNotes=mysqli_real_escape_string($conn, $_POST['specialNotes']);
  } else {
    $specialNotes=NULL;
  }
  $foodAllergies=$_POST['foodAllergies'];
  $noSlipBowl=$_POST['noSlipBowl'];
  $plasticBowl=$_POST['plasticBowl'];
  $slowFeeder=$_POST['slowFeeder'];
  $elevatedFeeder=$_POST['elevatedFeeder'];
  $separateToFeed=$_POST['separateToFeed'];
  $grazer=$_POST['grazer'];
  $sql_next_cat_food_id="SELECT AUTO_INCREMENT AS nextCatFoodID FROM information_schema.TABLES WHERE TABLE_SCHEMA='boarding' AND TABLE_NAME='cats_food'";
  $result_next_cat_food_id=$conn->query($sql_next_cat_food_id);
  $row_next_cat_food_id=$result_next_cat_food_id->fetch_assoc();
  $catFoodID=$row_next_cat_food_id['nextCatFoodID'];
  $sql_add_food="INSERT INTO cats_food (catFoodID, catReservationID, foodType, feedingInstructions, specialNotes, foodAllergies, noSlipBowl, plasticBowl, slowFeeder, elevatedFeeder, separateToFeed, grazer, status) VALUES ('$catFoodID', '$reservationID', '$foodType', '$feedingInstructions', '$specialNotes', '$foodAllergies', '$noSlipBowl', '$plasticBowl', '$slowFeeder', '$elevatedFeeder', '$separateToFeed', '$grazer', '$status')";
  $conn->query($sql_add_food);
}
?>
