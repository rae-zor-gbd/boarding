<?php
include '../assets/config.php';
if (isset($_POST['status'])) {
  $status=$_POST['status'];
  echo "<input type='hidden' class='form-control' name='status' id='newStatus' value='$status' required>
  <div class='input-group'>
  <span class='input-group-addon cat'>Cat Name</span>
  <select class='form-control' name='cat' id='newCat' required=''>
  <option value='' selected disabled>Select Cat</option>";
  $sql_all_cats="SELECT catReservationID, lastName, catName, checkIn, checkOut FROM cats_reservations WHERE checkOut>=DATE(NOW()) AND catReservationID NOT IN (SELECT catReservationID FROM cats_food) ORDER BY checkIn, lastName, catName";
  $result_all_cats=$conn->query($sql_all_cats);
  while ($row_all_cats=$result_all_cats->fetch_assoc()) {
    $allReservationID=$row_all_cats['catReservationID'];
    $allReservationLastName=htmlspecialchars($row_all_cats['lastName'], ENT_QUOTES);
    $allReservationCatName=htmlspecialchars($row_all_cats['catName'], ENT_QUOTES);
    $allReservationCheckIn=strtotime($row_all_cats['checkIn']);
    $allReservationCheckOut=strtotime($row_all_cats['checkOut']);
    echo "<option value='$allReservationID'>$allReservationCatName $allReservationLastName (" . date('D n/j', $allReservationCheckIn) . " – " . date('D n/j', $allReservationCheckOut) . ")</option>";
  }
  echo "</select>
  </div>
  <div class='input-group'>
  <span class='input-group-addon food'>Food Source</span>
  <select class='form-control' name='foodType' id='newFoodType' required=''>
  <option value='' selected disabled>Select Food Source</option>
  <option value='Own'>Own Food</option>
  <option value='Ours'>Our Food</option>
  </select>
  </div>
  <div class='input-group'>
  <span class='input-group-addon food'>Feeding Instructions</span>
  <textarea class='form-control' name='feeding-instructions' id='newFeedingInstructions' rows='5' required></textarea>
  </div>
  <div class='input-group'>
  <span class='input-group-addon tags'>Labels</span>
  <div class='tag-group'>";
  $sql_all_tags="SELECT tagID, tagName FROM tags WHERE forCats='Yes' ORDER BY sortID";
  $result_all_tags=$conn->query($sql_all_tags);
  while ($row_all_tags=$result_all_tags->fetch_assoc()) {
    $allTagID=$row_all_tags['tagID'];
    $allTagName=htmlspecialchars($row_all_tags['tagName'], ENT_QUOTES);
    echo "<div class='input-group'>
    <input type='checkbox' id='newTag$allTagID' name='tag$allTagID' value='$allTagID'>
    <label for='newTag$allTagID'>$allTagName</label>
    </div>";
  }
  echo "</div>
  </div>
  <div class='input-group'>
  <span class='input-group-addon notes'>Special Notes</span>
  <textarea class='form-control' name='special-notes' id='newSpecialNotes' rows='5'></textarea>
  </div>";
}
?>
