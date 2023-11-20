<?php
include '../assets/config.php';
if (isset($_POST['room']) AND isset($_POST['name']) AND isset($_POST['checkIn']) AND isset($_POST['checkOut'])) {
  $room=$_POST['room'];
  $name=mysqli_real_escape_string($conn, trim($_POST['name']));
  $secondToLastCharacter=substr($name, -2, 1);
  $lastCharacter=substr($name, -1, 1);
  if (isset($lastCharacter) AND $lastCharacter!='' AND $secondToLastCharacter==' ') {
    $name.=".";
  }
  $checkIn=$_POST['checkIn'];
  $checkOut=$_POST['checkOut'];
  $sql_book_room="INSERT INTO dogs_reservations (roomID, dogName, checkIn, checkOut) VALUES ('$room', '$name', '$checkIn', '$checkOut')";
  $conn->query($sql_book_room);
}
?>
