<?php
include '../assets/config.php';
if (isset($_POST['status']) AND isset($_POST['id']) AND isset($_POST['medName']) AND isset($_POST['dosage']) AND isset($_POST['frequency'])) {
  $status=$_POST['status'];
  $id=$_POST['id'];
  $medName=mysqli_real_escape_string($conn, trim($_POST['medName']));
  $dosage=mysqli_real_escape_string($conn, trim($_POST['dosage']));
  $frequency=$_POST['frequency'];
  if (isset($_POST['strength']) AND $_POST['strength']!='') {
    $strength=mysqli_real_escape_string($conn, trim($_POST['strength']));
  } else {
    $strength=NULL;
  }
  if (isset($_POST['notes']) AND $_POST['notes']!='') {
    $notes=mysqli_real_escape_string($conn, trim($_POST['notes']));
  } else {
    $notes=NULL;
  }
  $sql_add_med="INSERT INTO cats_medications (catReservationID, medName, strength, dosage, frequency, notes) VALUES ('$id', '$medName', '$strength', '$dosage', '$frequency', '$notes')";
  $conn->query($sql_add_med);
}
?>
