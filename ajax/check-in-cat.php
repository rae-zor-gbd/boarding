<?php
include '../assets/config.php';
if (isset($_POST['id'])) {
  $id=$_POST['id'];
  $sql_check_in_cat="UPDATE cats SET status='Active' WHERE catID='$id'";
  $conn->query($sql_check_in_cat);
}
?>