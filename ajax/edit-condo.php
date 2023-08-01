<?php
include '../assets/config.php';
if (isset($_POST['id']) AND isset($_POST['condo']) AND isset($_POST['catName']) AND isset($_POST['checkIn']) AND isset($_POST['checkOut'])) {
  $id=$_POST['id'];
  $condoID=mysqli_real_escape_string($conn, $_POST['condo']);
  $catName=mysqli_real_escape_string($conn, $_POST['catName']);
  $checkIn=$_POST['checkIn'];
  $checkOut=$_POST['checkOut'];
  $sql_update="UPDATE cats_reservations SET condoID='$condoID', catName='$catName', checkIn='$checkIn', checkOut='$checkOut' WHERE catReservationID='$id'";
  $conn->query($sql_update);
}
?>
