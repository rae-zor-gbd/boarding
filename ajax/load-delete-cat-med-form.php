<?php
include '../assets/config.php';
if (isset($_POST['id'])) {
  $id=$_POST['id'];
  $sql_med_info="SELECT lastName, catName, medName, strength, dosage, frequency FROM cats_reservations r JOIN cats_medications m USING (catReservationID) WHERE catMedID='$id'";
  $result_med_info=$conn->query($sql_med_info);
  $row_med_info=$result_med_info->fetch_assoc();
  $lastName=htmlspecialchars($row_med_info['lastName'], ENT_QUOTES);
  $catName=htmlspecialchars($row_med_info['catName'], ENT_QUOTES);
  $medName=htmlspecialchars($row_med_info['medName'], ENT_QUOTES);
  $strength=htmlspecialchars($row_med_info['strength'], ENT_QUOTES);
  $dosage=htmlspecialchars($row_med_info['dosage'], ENT_QUOTES);
  $frequency=$row_med_info['frequency'];
  echo "<input type='hidden' class='form-control' name='id' id='deleteID' value='$id' required>
  <div class='input-group'>
  <span class='input-group-addon cat'>Cat Name</span>
  <input type='text' class='form-control' name='cat-name' id='deleteCatName' value='$catName' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon cat'>Last Name</span>
  <input type='text' class='form-control' name='cat-name' id='deleteLastName' value='$lastName' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon meds'>Medication Name</span>
  <input type='text' class='form-control' name='med-name' id='deleteMedName' value='$medName' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon chart'>Strength</span>
  <input type='text' class='form-control' name='strength' id='deleteStrength' value='$strength' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon list'>Dosage</span>
  <input type='text' class='form-control' name='dosage' id='deleteDosage' value='$dosage' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon clock'>Frequency</span>
  <input type='text' class='form-control' name='frequency' id='deleteFrequency' value='$frequency' disabled>
  </div>";
}
?>
