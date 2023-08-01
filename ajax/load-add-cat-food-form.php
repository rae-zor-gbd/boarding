<?php
include '../assets/config.php';
if (isset($_POST['status'])) {
  $status=$_POST['status'];
  echo "<input type='hidden' class='form-control' name='status' id='newStatus' value='$status' required>
  <div class='input-group'>
  <span class='input-group-addon room'>Condo</span>
  <select class='form-control' name='condo' id='newCondo' required=''>
  <option value='' selected disabled>Select Condo</option>";
  $sql_all_condos="SELECT condoID FROM condos ORDER BY condoID";
  $result_all_condos=$conn->query($sql_all_condos);
  while ($row_all_condos=$result_all_condos->fetch_assoc()) {
    $allCondosID=$row_all_condos['condoID'];
    echo "<option value='$allCondosID'>Condo $allCondosID</option>";
  }
  echo "</select>
  </div>
  <div class='input-group'>
  <span class='input-group-addon cat'>Cat Name (s)</span>
  <input type='text' class='form-control' name='cat-name' maxlength='255' id='newCatName' required>
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
  </div>";
}
?>
