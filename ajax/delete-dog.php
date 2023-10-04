<?php
include '../assets/config.php';
if (isset($_POST['id'])) {
  $id=$_POST['id'];
  $sql_delete="DELETE FROM dogs_food WHERE dogFoodID='$id'";
  $conn->query($sql_delete);
}
?>
