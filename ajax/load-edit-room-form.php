<?php
include '../assets/config.php';
$today=date('Y-m-d');
$monthAgo=date('Y-m-d', strtotime($today. ' - 30 days'));
if (isset($_POST['id'])) {
  $id=$_POST['id'];
  $sql_reservation_info="SELECT roomID, dogName, checkIn, checkOut FROM dogs_reservations WHERE dogReservationID='$id'";
  $result_reservation_info=$conn->query($sql_reservation_info);
  $row_reservation_info=$result_reservation_info->fetch_assoc();
  $room=$row_reservation_info['roomID'];
  $dogName=htmlspecialchars($row_reservation_info['dogName'], ENT_QUOTES);
  $checkIn=$row_reservation_info['checkIn'];
  $checkOut=$row_reservation_info['checkOut'];
  echo "<input type='hidden' class='form-control' name='status' id='editID' value='$id' required>
  <div class='input-group'>
  <span class='input-group-addon room'>Room</span>
  <select class='form-control' name='room' id='editRoom' required=''>
  <option value='' disabled>Select Room</option>";
  $sql_all_rooms="SELECT roomID, status, description FROM rooms ORDER BY roomID";
  $result_all_rooms=$conn->query($sql_all_rooms);
  while ($row_all_rooms=$result_all_rooms->fetch_assoc()) {
    $allRoomsID=$row_all_rooms['roomID'];
    $allRoomsStatus=$row_all_rooms['status'];
    $allRoomsDescription=htmlspecialchars($row_all_rooms['description'], ENT_QUOTES);
    echo "<option value='$allRoomsID'";
    if ($allRoomsID==$room) {
      echo " selected";
    }
    echo ">$allRoomsID";
    if (isset($allRoomsDescription) AND $allRoomsDescription!='') {
      echo " - $allRoomsDescription";
    } elseif ($allRoomsStatus=='Disabled') {
      echo " - Disabled Room";
    }
    echo "</option>";
  }
  echo "</select>
  </div>
  <div class='input-group'>
  <span class='input-group-addon dog'>Dog Name</span>
  <input type='text' class='form-control' name='dog-name' maxlength='255' id='editDogName' value='$dogName' required>
  </div>
  <div class='input-group'>
  <span class='input-group-addon clock'>Check In</span>
  <input type='date' class='form-control' name='check-in' min='$monthAgo' id='editCheckIn' value='$checkIn' required>
  </div>
  <div class='input-group'>
  <span class='input-group-addon clock'>Check Out</span>
  <input type='date' class='form-control' name='check-out' min='$today' id='editCheckOut' value='$checkOut' required>
  </div>";
}
?>
