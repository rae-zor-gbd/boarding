<?php
include '../assets/config.php';
if (isset($_POST['status'])) {
  $status=$_POST['status'];
  echo "<input type='hidden' class='form-control' name='status' id='newStatus' value='$status' required>
  <div class='input-group'>
  <span class='input-group-addon dog'>Dog Name</span>
  <select class='form-control' name='dog' id='newDog' required=''>
  <option value='' selected disabled>Select Dog</option>";
  $sql_all_dogs="SELECT dogReservationID, dogName, checkIn, checkOut FROM dogs_reservations WHERE checkOut>=DATE(NOW()) AND dogReservationID NOT IN (SELECT dogReservationID FROM dogs_food) ORDER BY checkIn, dogName";
  $result_all_dogs=$conn->query($sql_all_dogs);
  while ($row_all_dogs=$result_all_dogs->fetch_assoc()) {
    $allReservationID=$row_all_dogs['dogReservationID'];
    $allReservationName=htmlspecialchars($row_all_dogs['dogName'], ENT_QUOTES);
    $allReservationCheckIn=strtotime($row_all_dogs['checkIn']);
    $allReservationCheckOut=strtotime($row_all_dogs['checkOut']);
    echo "<option value='$allReservationID'>$allReservationName (" . date('D n/j', $allReservationCheckIn) . " – " . date('D n/j', $allReservationCheckOut) . ")</option>";
  }
  echo "</select>
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
