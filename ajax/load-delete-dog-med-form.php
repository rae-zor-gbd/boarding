<?php
include '../assets/config.php';
if (isset($_POST['id'])) {
  $id=$_POST['id'];
  $sql_med_info="SELECT dogName, medName, strength, dosage, frequency FROM dogs d JOIN dogs_medications m USING (dogID) WHERE dogMedID='$id'";
  $result_med_info=$conn->query($sql_med_info);
  $row_med_info=$result_med_info->fetch_assoc();
  $dogName=htmlspecialchars($row_med_info['dogName'], ENT_QUOTES);
  $medName=htmlspecialchars($row_med_info['medName'], ENT_QUOTES);
  $strength=htmlspecialchars($row_med_info['strength'], ENT_QUOTES);
  $dosage=htmlspecialchars($row_med_info['dosage'], ENT_QUOTES);
  $frequency=$row_med_info['frequency'];
  echo "<input type='hidden' class='form-control' name='id' id='deleteID' value='$id' required>
  <div class='input-group'>
  <span class='input-group-addon dog'>Dog Name(s)</span>
  <input type='text' class='form-control' name='dog-name' id='deleteDogName' value='$dogName' disabled>
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
