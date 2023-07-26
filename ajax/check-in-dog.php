<?php
include '../assets/config.php';
if (isset($_POST['id'])) {
  $id=$_POST['id'];
  $sql_check_in_dog="UPDATE dogs SET status='Active' WHERE dogID='$id'";
  $conn->query($sql_check_in_dog);
}
?>