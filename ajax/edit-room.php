<?php
include '../assets/config.php';
if (isset($_POST['id']) AND isset($_POST['room']) AND isset($_POST['dogName']) AND isset($_POST['checkIn']) AND isset($_POST['checkOut'])) {
  $id=$_POST['id'];
  $roomID=mysqli_real_escape_string($conn, $_POST['room']);
  $dogName=mysqli_real_escape_string($conn, $_POST['dogName']);
  $checkIn=$_POST['checkIn'];
  $checkOut=$_POST['checkOut'];
  $sql_update="UPDATE dogs_reservations SET roomID='$roomID', dogName='$dogName', checkIn='$checkIn', checkOut='$checkOut' WHERE dogReservationID='$id'";
  $conn->query($sql_update);
}
?>
