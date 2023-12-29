<?php
include '../assets/config.php';
if (isset($_POST['id'])) {
  $id=$_POST['id'];
  $sql_reservation_info="SELECT condoID, catName, checkIn, checkOut FROM cats_reservations WHERE catReservationID='$id'";
  $result_reservation_info=$conn->query($sql_reservation_info);
  $row_reservation_info=$result_reservation_info->fetch_assoc();
  $condoNo=$row_reservation_info['condoID'];
  $catName=htmlspecialchars($row_reservation_info['catName'], ENT_QUOTES);
  $checkIn=$row_reservation_info['checkIn'];
  $checkOut=$row_reservation_info['checkOut'];
  echo "<input type='hidden' class='form-control' name='id' id='deleteID' value='$id' required>
  <div class='input-group'>
  <span class='input-group-addon room'>Condo</span>
  <input type='text' class='form-control' name='condo' id='deleteCondo' value='$condoNo' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon cat'>Cat Name</span>
  <input type='text' class='form-control' name='cat-name' id='deleteCatName' value='$catName' disabled>
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
