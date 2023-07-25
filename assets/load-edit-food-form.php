<?php
include 'config.php';
if (isset($_POST['id']) AND isset($_POST['status'])) {
  $id=$_POST['id'];
  $status=$_POST['status'];
  $sql_dog_info="SELECT roomID, dogName, foodType, feedingInstructions FROM dogs WHERE dogID='$id'";
  $result_dog_info=$conn->query($sql_dog_info);
  $row_dog_info=$result_dog_info->fetch_assoc();
  $room=$row_dog_info['roomID'];
  $dogName=htmlspecialchars($row_dog_info['dogName'], ENT_QUOTES);
  $foodType=$row_dog_info['foodType'];
  $feedingInstructions=htmlspecialchars($row_dog_info['feedingInstructions'], ENT_QUOTES);
  echo "<input type='hidden' class='form-control' name='status' id='editID' value='$id' required>
  <input type='hidden' class='form-control' name='status' id='editStatus' value='$status' required>
  <div class='input-group'>
  <span class='input-group-addon room'>Room</span>
  <select class='form-control' name='room' id='editRoom' required=''>
  <option value='' disabled>Select Room</option>";
  $sql_all_rooms="SELECT roomID FROM rooms ORDER BY roomID";
  $result_all_rooms=$conn->query($sql_all_rooms);
  while ($row_all_rooms=$result_all_rooms->fetch_assoc()) {
    $allRoomsID=$row_all_rooms['roomID'];
    echo "<option value='$allRoomsID'";
    if ($allRoomsID==$room) {
      echo " selected";
    }
    echo ">Room $allRoomsID</option>";
  }
  echo "</select>
  </div>
  <div class='input-group'>
  <span class='input-group-addon dog'>Name</span>
  <input type='text' class='form-control' name='dog-name' maxlength='255' id='editDogName' value='$dogName' required>
  </div>
  <div class='input-group'>
  <span class='input-group-addon food'>Food Type</span>
  <select class='form-control' name='foodType' id='editFoodType' required=''>
  <option value='' disabled>Select Food Type</option>
  <option value='Own'";
  if ($foodType=='Own') {
    echo " selected";
  }
  echo ">Own Food</option>
  <option value='Ours'";
  if ($foodType=='Ours') {
    echo " selected";
  }
  echo ">Our Food</option>
  </select>
  </div>
  <div class='input-group'>
  <span class='input-group-addon food'>Feeding Instructions</span>
  <textarea class='form-control' name='feeding-instructions' id='editFeedingInstructions' rows='5' required>$feedingInstructions</textarea>
  </div>";
}
?>
