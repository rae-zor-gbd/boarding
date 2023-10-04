<?php
include '../assets/config.php';
if (isset($_POST['id'])) {
  $id=$_POST['id'];
  $sql_check_in_cat="UPDATE cats_food SET status='Active' WHERE catFoodID='$id'";
  $conn->query($sql_check_in_cat);
}
?>