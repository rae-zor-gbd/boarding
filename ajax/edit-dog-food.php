<?php
include '../assets/config.php';
if (isset($_POST['status']) AND isset($_POST['id']) AND isset($_POST['reservationID']) AND isset($_POST['foodType']) AND isset($_POST['feedingInstructions'])) {
  $status=$_POST['status'];
  $id=$_POST['id'];
  $reservationID=$_POST['reservationID'];
  $foodType=mysqli_real_escape_string($conn, $_POST['foodType']);
  $feedingInstructions=mysqli_real_escape_string($conn, $_POST['feedingInstructions']);
  if (isset($_POST['specialNotes']) AND $_POST['specialNotes']!='') {
    $specialNotes=mysqli_real_escape_string($conn, $_POST['specialNotes']);
  } else {
    $specialNotes=NULL;
  }
  $sql_update="UPDATE dogs_food SET foodType='$foodType', feedingInstructions='$feedingInstructions', specialNotes='$specialNotes', status='$status' WHERE dogFoodID='$id'";
  $conn->query($sql_update);
  $sql_delete_tags="DELETE FROM dogs_tags WHERE dogReservationID='$reservationID'";
  $conn->query($sql_delete_tags);
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
