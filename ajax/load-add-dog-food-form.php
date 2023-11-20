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
  <div class='tag-group'>";
  $sql_all_tags="SELECT tagID, tagName FROM tags WHERE forDogs='Yes' ORDER BY sortID";
  $result_all_tags=$conn->query($sql_all_tags);
  while ($row_all_tags=$result_all_tags->fetch_assoc()) {
    $allTagID=$row_all_tags['tagID'];
    $allTagName=htmlspecialchars($row_all_tags['tagName'], ENT_QUOTES);
    echo "<div class='input-group'>
    <input type='checkbox' id='newTag$allTagID' name='tag$allTagID' value='$allTagID'>
    <label for='newTag$allTagID'>$allTagName</label>
    </div>";
  }
  echo "</div>";
}
?>
