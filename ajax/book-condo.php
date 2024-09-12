<?php
include '../assets/config.php';
if (isset($_POST['condo']) AND isset($_POST['lastName']) AND isset($_POST['catName']) AND isset($_POST['checkIn']) AND isset($_POST['checkOut'])) {
  $condo=$_POST['condo'];
  $lastName=mysqli_real_escape_string($conn, trim($_POST['lastName']));
  $catName=mysqli_real_escape_string($conn, trim($_POST['catName']));
  $checkIn=$_POST['checkIn'];
  $checkOut=$_POST['checkOut'];
  $sql_book_condo="INSERT INTO cats_reservations (condoID, lastName, catName, checkIn, checkOut) VALUES ('$condo', '$lastName', '$catName', '$checkIn', '$checkOut')";
  $conn->query($sql_book_condo);
}
?>
