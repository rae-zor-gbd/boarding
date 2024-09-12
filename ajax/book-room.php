<?php
include '../assets/config.php';
if (isset($_POST['room']) AND isset($_POST['lastName']) AND isset($_POST['dogName']) AND isset($_POST['checkIn']) AND isset($_POST['checkOut'])) {
  $room=$_POST['room'];
  $lastName=mysqli_real_escape_string($conn, trim($_POST['lastName']));
  $dogName=mysqli_real_escape_string($conn, trim($_POST['dogName']));
  $checkIn=$_POST['checkIn'];
  $checkOut=$_POST['checkOut'];
  $sql_book_room="INSERT INTO dogs_reservations (roomID, lastName, dogName, checkIn, checkOut) VALUES ('$room', '$lastName', '$dogName', '$checkIn', '$checkOut')";
  $conn->query($sql_book_room);
}
?>
