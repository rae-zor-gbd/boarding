<?php
include '../assets/config.php';
$today=date('Y-m-d');
$monthAgo=date('Y-m-d', strtotime($today. ' - 30 days'));
echo "<div class='input-group'>
<span class='input-group-addon room'>Condo</span>
<select class='form-control' name='condo' id='newCondo' required=''>
<option value='' selected disabled>Select Condo</option>";
$sql_all_condos="SELECT condoID, status, description FROM condos ORDER BY condoID";
$result_all_condos=$conn->query($sql_all_condos);
while ($row_all_condos=$result_all_condos->fetch_assoc()) {
  $allCondosID=$row_all_condos['condoID'];
  $allCondosStatus=$row_all_condos['status'];
  $allCondosDescription=htmlspecialchars($row_all_condos['description'], ENT_QUOTES);
  echo "<option value='$allCondosID'>$allCondosID";
  if (isset($allCondosDescription) AND $allCondosDescription!='') {
    echo " - $allCondosDescription";
  } elseif ($allCondosStatus=='Disabled') {
    echo " - Disabled Condo";
  }
  echo "</option>";
}
echo "</select>
</div>
<div class='input-group'>
<span class='input-group-addon cat'>Cat Name</span>
<input type='text' class='form-control' name='cat-name' maxlength='255' id='newCatName' required>
</div>
<div class='input-group'>
<span class='input-group-addon clock'>Check-In</span>
<input type='date' class='form-control' name='check-in' id='newCheckIn' min='$monthAgo' required>
</div>
<div class='input-group'>
<span class='input-group-addon clock'>Check-Out</span>
<input type='date' class='form-control' name='check-out' id='newCheckOut' min='$today' required>
</div>";
?>
