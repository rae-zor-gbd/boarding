<?php
include '../assets/config.php';
$today=date('Y-m-d');
$monthAgo=date('Y-m-d', strtotime($today. ' - 30 days'));
echo "<div class='input-group'>
<span class='input-group-addon room'>Room</span>
<select class='form-control' name='room' id='newRoom' required=''>
<option value='' selected disabled>Select Room</option>";
$sql_all_rooms="SELECT roomID FROM rooms ORDER BY roomID";
$result_all_rooms=$conn->query($sql_all_rooms);
while ($row_all_rooms=$result_all_rooms->fetch_assoc()) {
  $allRoomsID=$row_all_rooms['roomID'];
  echo "<option value='$allRoomsID'>$allRoomsID</option>";
}
echo "</select>
</div>
<div class='input-group'>
<span class='input-group-addon dog'>Dog Name</span>
<input type='text' class='form-control' name='dog-name' maxlength='255' id='newDogName' required>
</div>
<div class='input-group'>
<span class='input-group-addon clock'>Check-In</span>
<input type='date' class='form-control' name='check-in' id='newCheckIn' min='$monthAgo' required>
</div>
<div class='input-group'>
<span class='input-group-addon clock'>Check-Out</span>
<input type='date' class='form-control' name='check-out' id='newCheckOut' min='$today' required>
</div>";
?>
