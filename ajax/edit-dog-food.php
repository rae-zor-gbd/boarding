<?php
include '../assets/config.php';
if (isset($_POST['status']) AND isset($_POST['id']) AND isset($_POST['room']) AND isset($_POST['dogName']) AND isset($_POST['foodType']) AND isset($_POST['feedingInstructions']) AND isset($_POST['foodAllergies']) AND isset($_POST['noSlipBowl']) AND isset($_POST['plasticBowl']) AND isset($_POST['slowFeeder']) AND isset($_POST['elevatedFeeder']) AND isset($_POST['separateToFeed'])) {
  $status=$_POST['status'];
  $id=$_POST['id'];
  $roomID=mysqli_real_escape_string($conn, $_POST['room']);
  $dogName=mysqli_real_escape_string($conn, $_POST['dogName']);
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
  $sql_update="UPDATE dogs SET roomID='$roomID', dogName='$dogName', foodType='$foodType', feedingInstructions='$feedingInstructions', specialNotes='$specialNotes', foodAllergies='$foodAllergies', noSlipBowl='$noSlipBowl', plasticBowl='$plasticBowl', slowFeeder='$slowFeeder', elevatedFeeder='$elevatedFeeder', separateToFeed='$separateToFeed', status='$status' WHERE dogID='$id'";
  $conn->query($sql_update);
}
?>
