<?php
include '../assets/config.php';
if (isset($_POST['status'])) {
  $status=$_POST['status'];
  echo "<input type='hidden' class='form-control' name='status' id='newStatus' value='$status' required>
  <div class='input-group'>
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
  <span class='input-group-addon dog'>Dog Name</span>
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
  <span class='input-group-addon food'>Feeding Instructions</span>
  <textarea class='form-control' name='feeding-instructions' id='newFeedingInstructions' rows='5' required></textarea>
  </div>
  <div class='input-group'>
  <span class='input-group-addon notes'>Special Notes</span>
  <textarea class='form-control' name='special-notes' id='newSpecialNotes' rows='5'></textarea>
  </div>
  <div class='row'>
  <div class='col-sm-4'>
  <div class='input-group'>
  <input type='checkbox' id='newFoodAllergies' name='foodAllergies' value='Yes'>
  <label for='newFoodAllergies'>Food Allergies</label>
  </div>
  <div class='input-group'>
  <input type='checkbox' id='newSeparateToFeed' name='separateToFeed' value='Yes'>
  <label for='newSeparateToFeed'>Separate To Feed</label>
  </div>
  </div>
  <div class='col-sm-4'>
  <div class='input-group'>
  <input type='checkbox' id='newNoSlipBowl' name='noSlipBowl' value='Yes'>
  <label for='newNoSlipBowl'>No-Slip Bowl</label>
  </div>
  <div class='input-group'>
  <input type='checkbox' id='newPlasticBowl' name='plasticBowl' value='Yes'>
  <label for='newPlasticBowl'>Plastic Bowl</label>
  </div>
  </div>
  <div class='col-sm-4'>
  <div class='input-group'>
  <input type='checkbox' id='newSlowFeeder' name='slowFeeder' value='Yes'>
  <label for='newSlowFeeder'>Slow Feeder</label>
  </div>
  <div class='input-group'>
  <input type='checkbox' id='newElevatedFeeder' name='elevatedFeeder' value='Yes'>
  <label for='newElevatedFeeder'>Elevated Feeder</label>
  </div>
  </div>
  </div>";
}
?>
