<?php
include '../assets/config.php';
if (isset($_POST['id'])) {
  $id=$_POST['id'];
  $sql_reservation_info="SELECT roomID, dogName, checkIn, checkOut FROM dogs_reservations WHERE dogReservationID='$id'";
  $result_reservation_info=$conn->query($sql_reservation_info);
  $row_reservation_info=$result_reservation_info->fetch_assoc();
  $roomNo=$row_reservation_info['roomID'];
  $dogName=htmlspecialchars($row_reservation_info['dogName'], ENT_QUOTES);
  $checkIn=$row_reservation_info['checkIn'];
  $checkOut=$row_reservation_info['checkOut'];
  echo "<input type='hidden' class='form-control' name='id' id='deleteID' value='$id' required>
  <div class='input-group'>
  <span class='input-group-addon room'>Room</span>
  <input type='text' class='form-control' name='room' id='deleteRoom' value='$roomNo' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon dog'>Dog Name</span>
  <input type='text' class='form-control' name='dog-name' id='deleteDogName' value='$dogName' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon clock'>Check-In</span>
  <input type='date' class='form-control' name='check-in' id='deleteCheckIn' value='$checkIn' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon clock'>Check-Out</span>
  <input type='date' class='form-control' name='check-out' id='deleteCheckOut' value='$checkOut' disabled>
  </div>";
}
?>
