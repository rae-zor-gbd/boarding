<?php
include '../assets/config.php';
if (isset($_POST['id'])) {
  $id=$_POST['id'];
  $sql_check_in_dog="UPDATE dogs_food SET status='Active' WHERE dogFoodID='$id'";
  $conn->query($sql_check_in_dog);
}
?>