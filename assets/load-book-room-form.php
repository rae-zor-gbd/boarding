<?php
include 'config.php';
echo "<div class='input-group'>
<span class='input-group-addon room'>Room</span>
<select class='form-control' name='room' id='newRoom' required=''>
<option value='' selected disabled>Select Room</option>";
$sql_all_rooms="SELECT roomID FROM rooms ORDER BY roomID";
$result_all_rooms=$conn->query($sql_all_rooms);
while ($row_all_rooms=$result_all_rooms->fetch_assoc()) {
  $allRoomsID=$row_all_rooms['roomID'];
  echo "<option value='$allRoomsID'>Room $allRoomsID</option>";
}
echo "</select>
</div>
<div class='input-group'>
<span class='input-group-addon dog'>Name</span>
<input type='text' class='form-control' name='dog-name' maxlength='255' id='newDogName' required>
</div>
<div class='input-group'>
<span class='input-group-addon food'>Food Type</span>
<select class='form-control' name='foodType' id='newFoodType' required=''>
<option value='' selected disabled>Select Food Type</option>
<option value='Own'>Own Food</option>
<option value='Ours'>Our Food</option>
</select>
</div>
<div class='input-group'>
<span class='input-group-addon notes'>Feeding Instructions</span>
<textarea class='form-control' name='feeding-instructions' id='newFeedingInstructions' rows='5' required></textarea>
</div>";
?>
