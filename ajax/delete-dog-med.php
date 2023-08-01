<?php
include '../assets/config.php';
if (isset($_POST['id']) AND isset($_POST['status'])) {
  $status=$_POST['status'];
  $id=$_POST['id'];
  $sql_delete="DELETE FROM dogs_medications WHERE dogMedID='$id'";
  $conn->query($sql_delete);
}
?>
