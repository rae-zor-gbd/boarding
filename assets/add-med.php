<?php
include 'config.php';
if (isset($_POST['status']) AND isset($_POST['id']) AND isset($_POST['medName']) AND isset($_POST['dosage']) AND isset($_POST['frequency'])) {
  $status=$_POST['status'];
  $id=$_POST['id'];
  $medName=mysqli_real_escape_string($conn, $_POST['medName']);
  $dosage=mysqli_real_escape_string($conn, $_POST['dosage']);
  $frequency=$_POST['frequency'];
  if (isset($_POST['strength']) AND $_POST['strength']!='') {
    $strength=mysqli_real_escape_string($conn, $_POST['strength']);
    $sql_add_med="INSERT INTO dogs_medications (dogID, medName, strength, dosage, frequency) VALUES ('$id', '$medName', '$strength', '$dosage', '$frequency')";
  } else {
    $sql_add_med="INSERT INTO dogs_medications (dogID, medName, dosage, frequency) VALUES ('$id', '$medName', '$dosage', '$frequency')";
  }
  $conn->query($sql_add_med);
}
?>
