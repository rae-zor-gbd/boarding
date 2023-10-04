<?php
include '../assets/config.php';
if (isset($_POST['id'])) {
  $id=$_POST['id'];
  $sql_delete="DELETE FROM cats_food WHERE catFoodID='$id'";
  $conn->query($sql_delete);
}
?>
