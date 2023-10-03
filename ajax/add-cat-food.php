<?php
include '../assets/config.php';
if (isset($_POST['status']) AND isset($_POST['condo']) AND isset($_POST['name']) AND isset($_POST['foodType']) AND isset($_POST['feedingInstructions']) AND isset($_POST['foodAllergies']) AND isset($_POST['noSlipBowl']) AND isset($_POST['plasticBowl']) AND isset($_POST['slowFeeder']) AND isset($_POST['elevatedFeeder']) AND isset($_POST['separateToFeed'])) {
  $status=$_POST['status'];
  $condo=$_POST['condo'];
  $name=mysqli_real_escape_string($conn, $_POST['name']);
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
  $sql_next_cat_id="SELECT AUTO_INCREMENT AS nextCatID FROM information_schema.TABLES WHERE TABLE_SCHEMA='boarding' AND TABLE_NAME='cats'";
  $result_next_cat_id=$conn->query($sql_next_cat_id);
  $row_next_cat_id=$result_next_cat_id->fetch_assoc();
  $catID=$row_next_cat_id['nextCatID'];
  $sql_add_food="INSERT INTO cats (catID, condoID, catName, foodType, feedingInstructions, specialNotes, foodAllergies, noSlipBowl, plasticBowl, slowFeeder, elevatedFeeder, separateToFeed, status) VALUES ('$catID', '$condo', '$name', '$foodType', '$feedingInstructions', '$specialNotes', '$foodAllergies', '$noSlipBowl', '$plasticBowl', '$slowFeeder', '$elevatedFeeder', '$separateToFeed', '$status')";
  $conn->query($sql_add_food);
}
?>
