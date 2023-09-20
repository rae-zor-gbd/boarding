<?php
include '../assets/config.php';
if (isset($_POST['id']) AND isset($_POST['status'])) {
  $id=$_POST['id'];
  $status=$_POST['status'];
  $sql_cat_info="SELECT condoID, catName, foodType, feedingInstructions, foodAllergies, noSlipBowl, plasticBowl, slowFeeder, elevatedFeeder, separateToFeed FROM cats WHERE catID='$id'";
  $result_cat_info=$conn->query($sql_cat_info);
  $row_cat_info=$result_cat_info->fetch_assoc();
  $condo=$row_cat_info['condoID'];
  $catName=htmlspecialchars($row_cat_info['catName'], ENT_QUOTES);
  $foodType=$row_cat_info['foodType'];
  $feedingInstructions=htmlspecialchars($row_cat_info['feedingInstructions'], ENT_QUOTES);
  $foodAllergies=$row_cat_info['foodAllergies'];
  $noSlipBowl=$row_cat_info['noSlipBowl'];
  $plasticBowl=$row_cat_info['plasticBowl'];
  $slowFeeder=$row_cat_info['slowFeeder'];
  $elevatedFeeder=$row_cat_info['elevatedFeeder'];
  $separateToFeed=$row_cat_info['separateToFeed'];
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
  <span class='input-group-addon room'>Condo</span>
  <select class='form-control' name='condo' id='editCondo' required>
  <option value='' disabled>Select Condo</option>";
  $sql_all_condos="SELECT condoID FROM condos ORDER BY condoID";
  $result_all_condos=$conn->query($sql_all_condos);
  while ($row_all_condos=$result_all_condos->fetch_assoc()) {
    $allCondosID=$row_all_condos['condoID'];
    echo "<option value='$allCondosID'";
    if ($allCondosID==$condo) {
      echo " selected";
    }
    echo ">Condo $allCondosID</option>";
  }
  echo "</select>
  </div>
  <div class='input-group'>
  <span class='input-group-addon cat'>Cat Name(s)</span>
  <input type='text' class='form-control' name='cat-name' maxlength='255' id='editCatName' value='$catName' required>
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
