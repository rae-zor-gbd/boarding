<?php
include '../assets/config.php';
if (isset($_POST['room']) AND isset($_POST['name']) AND isset($_POST['checkIn']) AND isset($_POST['checkOut'])) {
  $room=$_POST['room'];
  $name=mysqli_real_escape_string($conn, $_POST['name']);
  $checkIn=$_POST['checkIn'];
  $checkOut=$_POST['checkOut'];
  $sql_book_room="INSERT INTO dog_reservations (roomID, dogName, checkIn, checkOut) VALUES ('$room', '$name', '$checkIn', '$checkOut')";
  $conn->query($sql_book_room);
}
?>
