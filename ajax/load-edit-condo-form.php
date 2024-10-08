<?php
include '../assets/config.php';
$today=date('Y-m-d');
$weekAgo=date('Y-m-d', strtotime($today. ' - 7 days'));
if (isset($_POST['id'])) {
  $id=$_POST['id'];
  $sql_reservation_info="SELECT condoID, lastName, catName, checkIn, checkOut FROM cats_reservations WHERE catReservationID='$id'";
  $result_reservation_info=$conn->query($sql_reservation_info);
  $row_reservation_info=$result_reservation_info->fetch_assoc();
  $condo=$row_reservation_info['condoID'];
  $lastName=htmlspecialchars($row_reservation_info['lastName'], ENT_QUOTES);
  $catName=htmlspecialchars($row_reservation_info['catName'], ENT_QUOTES);
  $checkIn=$row_reservation_info['checkIn'];
  $checkOut=$row_reservation_info['checkOut'];
  echo "<input type='hidden' class='form-control' name='status' id='editID' value='$id' required>
  <div class='input-group'>
  <span class='input-group-addon room'>Condo</span>
  <select class='form-control' name='room' id='editCondo' required=''>
  <option value='' disabled>Select Condo</option>";
  $sql_all_condos="SELECT condoID, status, description FROM condos ORDER BY condoID";
  $result_all_condos=$conn->query($sql_all_condos);
  while ($row_all_condos=$result_all_condos->fetch_assoc()) {
    $allCondosID=$row_all_condos['condoID'];
    $allCondosStatus=$row_all_condos['status'];
    $allCondosDescription=htmlspecialchars($row_all_condos['description'], ENT_QUOTES);
    echo "<option value='$allCondosID'";
    if ($allCondosID==$condo) {
      echo " selected";
    }
    echo ">$allCondosID";
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
  <input type='text' class='form-control' name='cat-name' maxlength='255' id='editCatName' value='$catName' required>
  </div>
  <div class='input-group'>
  <span class='input-group-addon cat'>Last Name</span>
  <input type='text' class='form-control' name='last-name' maxlength='255' id='editLastName' value='$lastName' required>
  </div>
  <div class='input-group'>
  <span class='input-group-addon clock'>Check-In</span>
  <input type='date' class='form-control' name='check-in' min='$weekAgo' id='editCheckIn' value='$checkIn' required>
  </div>
  <div class='input-group'>
  <span class='input-group-addon clock'>Check-Out</span>
  <input type='date' class='form-control' name='check-out' min='$today' id='editCheckOut' value='$checkOut' required>
  </div>";
}
?>
