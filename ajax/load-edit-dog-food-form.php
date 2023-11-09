<?php
include '../assets/config.php';
if (isset($_POST['id']) AND isset($_POST['status'])) {
  $id=$_POST['id'];
  $status=$_POST['status'];
  $sql_dog_info="SELECT roomID, dogName, foodType, feedingInstructions, specialNotes, foodAllergies, noSlipBowl, plasticBowl, slowFeeder, elevatedFeeder, separateToFeed, grazer FROM dogs_reservations r JOIN dogs_food f USING (dogReservationID) WHERE dogFoodID='$id'";
  $result_dog_info=$conn->query($sql_dog_info);
  $row_dog_info=$result_dog_info->fetch_assoc();
  $room=$row_dog_info['roomID'];
  $dogName=htmlspecialchars($row_dog_info['dogName'], ENT_QUOTES);
  $foodType=$row_dog_info['foodType'];
  $feedingInstructions=htmlspecialchars($row_dog_info['feedingInstructions'], ENT_QUOTES);
  $specialNotes=htmlspecialchars($row_dog_info['specialNotes'], ENT_QUOTES);
  $foodAllergies=$row_dog_info['foodAllergies'];
  $noSlipBowl=$row_dog_info['noSlipBowl'];
  $plasticBowl=$row_dog_info['plasticBowl'];
  $slowFeeder=$row_dog_info['slowFeeder'];
  $elevatedFeeder=$row_dog_info['elevatedFeeder'];
  $separateToFeed=$row_dog_info['separateToFeed'];
  $grazer=$row_dog_info['grazer'];
  echo "<input type='hidden' class='form-control' name='status' id='editID' value='$id' required>
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
  <div class='row'>
  <div class='col-sm-4'>
  <div class='input-group'>
  <input type='checkbox' id='editFoodAllergies' name='foodAllergies' value='Yes'";
  if ($foodAllergies=='Yes') {
    echo " checked";
  }
  echo ">
  <label for='editFoodAllergies'>Food Allergies</label>
  </div>
  <div class='input-group'>
  <input type='checkbox' id='editSeparateToFeed' name='separateToFeed' value='Yes'";
  if ($separateToFeed=='Yes') {
    echo " checked";
  }
  echo ">
  <label for='editSeparateToFeed'>Separate To Feed</label>
  </div>
  <div class='input-group'>
  <input type='checkbox' id='editGrazer' name='grazer' value='Yes'";
  if ($grazer=='Yes') {
    echo " checked";
  }
  echo ">
  <label for='editGrazer'>Grazer</label>
  </div>
  </div>
  <div class='col-sm-4'>
  <div class='input-group'>
  <input type='checkbox' id='editNoSlipBowl' name='noSlipBowl' value='Yes'";
  if ($noSlipBowl=='Yes') {
    echo " checked";
  }
  echo ">
  <label for='editNoSlipBowl'>No-Slip Bowl</label>
  </div>
  <div class='input-group'>
  <input type='checkbox' id='editPlasticBowl' name='plasticBowl' value='Yes'";
  if ($plasticBowl=='Yes') {
    echo " checked";
  }
  echo ">
  <label for='editPlasticBowl'>Plastic Bowl</label>
  </div>
  </div>
  <div class='col-sm-4'>
  <div class='input-group'>
  <input type='checkbox' id='editSlowFeeder' name='slowFeeder' value='Yes'";
  if ($slowFeeder=='Yes') {
    echo " checked";
  }
  echo ">
  <label for='editSlowFeeder'>Slow Feeder</label>
  </div>
  <div class='input-group'>
  <input type='checkbox' id='editElevatedFeeder' name='elevatedFeeder' value='Yes'";
  if ($elevatedFeeder=='Yes') {
    echo " checked";
  }
  echo ">
  <label for='editElevatedFeeder'>Elevated Feeder</label>
  </div>
  </div>
  </div>";
}
?>
