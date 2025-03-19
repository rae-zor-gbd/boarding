<?php
include '../assets/config.php';
if (isset($_POST['id']) AND isset($_POST['status'])) {
  $id=$_POST['id'];
  $status=$_POST['status'];
  $sql_cat_info="SELECT r.catReservationID, condoID, lastName, catName, foodType, feedingInstructions, specialNotes FROM cats_reservations r JOIN cats_food f USING (catReservationID) WHERE catFoodID='$id'";
  $result_cat_info=$conn->query($sql_cat_info);
  $row_cat_info=$result_cat_info->fetch_assoc();
  $reservationID=$row_cat_info['catReservationID'];
  $condo=$row_cat_info['condoID'];
  $lastName=htmlspecialchars($row_cat_info['lastName'], ENT_QUOTES);
  $catName=htmlspecialchars($row_cat_info['catName'], ENT_QUOTES);
  $foodType=$row_cat_info['foodType'];
  $feedingInstructions=htmlspecialchars($row_cat_info['feedingInstructions'], ENT_QUOTES);
  $specialNotes=htmlspecialchars($row_cat_info['specialNotes'], ENT_QUOTES);
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
  <span class='input-group-addon room'>Condo</span>
  <input type='text' class='form-control' name='condo' maxlength='255' id='editCondo' value='$condo' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon cat'>Cat Name</span>
  <input type='text' class='form-control' name='cat-name' maxlength='255' id='editCatName' value='$catName' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon cat'>Last Name</span>
  <input type='text' class='form-control' name='last-name' maxlength='255' id='editLastName' value='$lastName' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon food'>Food Source</span>
  <select class='form-control' name='foodType' id='editFoodType' required>
  <option value='' disabled>Select Food Source</option>
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
  <option value='Own & Ours'";
  if ($foodType=='Own & Ours') {
    echo " selected";
  }
  echo ">Own & Our Food</option>
  </select>
  </div>
  <div class='input-group'>
  <span class='input-group-addon food'>Feeding Instructions</span>
  <textarea class='form-control' name='feeding-instructions' id='editFeedingInstructions' rows='5' required>$feedingInstructions</textarea>
  </div>
  <div class='input-group'>
  <span class='input-group-addon tags'>Labels</span>
  <div class='tag-group'>";
  $sql_tags_info="SELECT tagID FROM cats_tags WHERE catReservationID='$reservationID' ORDER BY tagID";
  $result_tags_info=$conn->query($sql_tags_info);
  $tags_info=array();
  while ($row_tags_info=$result_tags_info->fetch_assoc()) {
    $tagID=$row_tags_info['tagID'];
    array_push($tags_info, $tagID);
  }
  $sql_all_tags="SELECT tagID, tagName FROM tags WHERE forCats='Yes' ORDER BY sortID";
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
  echo "</div>
  </div>
  <div class='input-group'>
  <span class='input-group-addon notes'>Special Notes</span>
  <textarea class='form-control' name='special-notes' id='editSpecialNotes' rows='5'>$specialNotes</textarea>
  </div>";
}
?>
