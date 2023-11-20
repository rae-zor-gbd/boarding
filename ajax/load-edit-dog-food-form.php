<?php
include '../assets/config.php';
if (isset($_POST['id']) AND isset($_POST['status'])) {
  $id=$_POST['id'];
  $status=$_POST['status'];
  $sql_dog_info="SELECT r.dogReservationID, roomID, dogName, foodType, feedingInstructions, specialNotes FROM dogs_reservations r JOIN dogs_food f USING (dogReservationID) WHERE dogFoodID='$id'";
  $result_dog_info=$conn->query($sql_dog_info);
  $row_dog_info=$result_dog_info->fetch_assoc();
  $reservationID=$row_dog_info['dogReservationID'];
  $room=$row_dog_info['roomID'];
  $dogName=htmlspecialchars($row_dog_info['dogName'], ENT_QUOTES);
  $foodType=$row_dog_info['foodType'];
  $feedingInstructions=htmlspecialchars($row_dog_info['feedingInstructions'], ENT_QUOTES);
  $specialNotes=htmlspecialchars($row_dog_info['specialNotes'], ENT_QUOTES);
  echo "<input type='hidden' class='form-control' name='id' id='editID' value='$id' required>
  <input type='hidden' class='form-control' name='reservationID' id='editReservationID' value='$reservationID' required>
  <div class='input-group'>
  <span class='input-group-addon status'>Status</span>
  <select class='form-control' name='status' id='editStatus' required>
  <option value='' disabled>Select Status</option>
  <option value='Active'";
  if ($status=='Active') {
    echo " selected";
  }
  echo ">Currently Boarding</option>
  <option value='Future'";
  if ($status=='Future') {
    echo " selected";
  }
  echo ">Future Arrival</option>
  </select>
  </div>
  <div class='input-group'>
  <span class='input-group-addon room'>Room</span>
  <input type='text' class='form-control' name='room' maxlength='255' id='editRoom' value='$room' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon dog'>Dog Name</span>
  <input type='text' class='form-control' name='dog-name' maxlength='255' id='editDogName' value='$dogName' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon food'>Food Type</span>
  <select class='form-control' name='foodType' id='editFoodType' required>
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
  </div>
  <div class='input-group'>
  <span class='input-group-addon notes'>Special Notes</span>
  <textarea class='form-control' name='special-notes' id='editSpecialNotes' rows='5'>$specialNotes</textarea>
  </div>
  <div class='tag-group'>";
  $sql_tags_info="SELECT tagID FROM dogs_tags WHERE dogReservationID='$reservationID' ORDER BY tagID";
  $result_tags_info=$conn->query($sql_tags_info);
  $tags_info=array();
  while ($row_tags_info=$result_tags_info->fetch_assoc()) {
    $tagID=$row_tags_info['tagID'];
    array_push($tags_info, $tagID);
  }
  $sql_all_tags="SELECT tagID, tagName FROM tags WHERE forDogs='Yes' ORDER BY sortID";
  $result_all_tags=$conn->query($sql_all_tags);
  while ($row_all_tags=$result_all_tags->fetch_assoc()) {
    $allTagID=$row_all_tags['tagID'];
    $allTagName=htmlspecialchars($row_all_tags['tagName'], ENT_QUOTES);
    echo "<div class='input-group'>
    <input type='checkbox' id='editTag$allTagID' name='tag$allTagID' value='$allTagID'";
    if (in_array($allTagID, $tags_info)) {
      echo " checked";
    }
    echo ">
    <label for='editTag$allTagID'>$allTagName</label>
    </div>";
  }
  echo "</div>";
}
?>
