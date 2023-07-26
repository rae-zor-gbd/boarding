<?php
include '../assets/config.php';
if (isset($_POST['id']) AND isset($_POST['status']) AND isset($_POST['medName']) AND isset($_POST['dosage']) AND isset($_POST['frequency'])) {
  $id=$_POST['id'];
  $status=$_POST['status'];
  $medName=mysqli_real_escape_string($conn, $_POST['medName']);
  $dosage=mysqli_real_escape_string($conn, $_POST['dosage']);
  $frequency=$_POST['frequency'];
  if (isset($_POST['strength']) AND $_POST['strength']!='') {
    $strength=mysqli_real_escape_string($conn, $_POST['strength']);
  } else {
    $strength=NULL;
  }
  if (isset($_POST['notes']) AND $_POST['notes']!='') {
    $notes=mysqli_real_escape_string($conn, $_POST['notes']);
  } else {
    $notes=NULL;
  }
  $sql_update="UPDATE dogs_medications SET medName='$medName', strength='$strength', dosage='$dosage', frequency='$frequency', notes='$notes' WHERE dogMedID='$id'";
  $conn->query($sql_update);
}
?>
