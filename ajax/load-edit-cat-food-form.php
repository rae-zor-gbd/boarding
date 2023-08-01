<?php
include '../assets/config.php';
if (isset($_POST['id']) AND isset($_POST['status'])) {
  $id=$_POST['id'];
  $status=$_POST['status'];
  $sql_cat_info="SELECT condoID, catName, foodType, feedingInstructions FROM cats WHERE catID='$id'";
  $result_cat_info=$conn->query($sql_cat_info);
  $row_cat_info=$result_cat_info->fetch_assoc();
  $condo=$row_cat_info['condoID'];
  $catName=htmlspecialchars($row_cat_info['catName'], ENT_QUOTES);
  $foodType=$row_cat_info['foodType'];
  $feedingInstructions=htmlspecialchars($row_cat_info['feedingInstructions'], ENT_QUOTES);
  echo "<input type='hidden' class='form-control' name='status' id='editID' value='$id' required>
  <input type='hidden' class='form-control' name='status' id='editStatus' value='$status' required>
  <div class='input-group'>
  <span class='input-group-addon room'>Condo</span>
  <select class='form-control' name='condo' id='editCondo' required=''>
  <option value='' disabled>Select Condo</option>";
  $sql__all_condos="SELECT condoID FROM condos ORDER BY condoID";
  $result__all_condos=$conn->query($sql__all_condos);
  while ($row__all_condos=$result__all_condos->fetch_assoc()) {
    $allCondosID=$row__all_condos['condoID'];
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
