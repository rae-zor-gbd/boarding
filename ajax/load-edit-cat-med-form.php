<?php
include '../assets/config.php';
if (isset($_POST['status']) AND isset($_POST['id'])) {
  $status=$_POST['status'];
  $id=$_POST['id'];
  $sql_med_info="SELECT medName, strength, dosage, frequency, notes FROM cats_medications WHERE catMedID='$id'";
  $result_med_info=$conn->query($sql_med_info);
  $row_med_info=$result_med_info->fetch_assoc();
  $medName=htmlspecialchars($row_med_info['medName'], ENT_QUOTES);
  $strength=htmlspecialchars($row_med_info['strength'], ENT_QUOTES);
  $dosage=htmlspecialchars($row_med_info['dosage'], ENT_QUOTES);
  $frequency=$row_med_info['frequency'];
  $notes=htmlspecialchars($row_med_info['notes'], ENT_QUOTES);
  echo "<input type='hidden' class='form-control' name='status' id='editStatus' value='$status' required>
  <input type='hidden' class='form-control' name='id' id='editID' value='$id' required>
  <div class='input-group'>
  <span class='input-group-addon meds'>Medication Name</span>
  <input type='text' class='form-control' name='med-name' maxlength='255' list='meds' id='editMedName' style='border-bottom-right-radius:4px; border-top-right-radius:4px;' value='$medName' required>
  <datalist id='meds'>";
  $sql_all_meds="SELECT medName FROM medications ORDER BY medName";
  $result_all_meds=$conn->query($sql_all_meds);
  while ($row_all_meds=$result_all_meds->fetch_assoc()) {
    $medNameAll=strtoupper(htmlspecialchars($row_all_meds['medName'], ENT_QUOTES));
    echo "<option value='$medNameAll'></option>";
  }
  echo "</datalist>
  </div>
  <div class='input-group'>
  <span class='input-group-addon chart'>Strength</span>
  <input type='text' class='form-control' name='strength' maxlength='255' list='editStrengthsList' id='editStrength' style='border-bottom-right-radius:4px; border-top-right-radius:4px;' value='$strength'>
  <datalist id='editStrengthsList'>";
  $sql_edit_strengths="SELECT CONCAT(strength, IF(unit='%', '', ' '), unit) AS strength FROM medications m JOIN medications_strengths s USING (medID) WHERE medName='$medName' ORDER BY s.unit, s.strength";
  $result_edit_strengths=$conn->query($sql_edit_strengths);
  while ($row_edit_strengths=$result_edit_strengths->fetch_assoc()) {
    $editStrengthList=strtoupper(htmlspecialchars($row_edit_strengths['strength'], ENT_QUOTES));
    echo "<option value='$editStrengthList'></option>";
  }
  echo "</datalist>
  </div>
  <div class='input-group'>
  <span class='input-group-addon list'>Dosage</span>
  <textarea class='form-control' name='dosage' id='editDosage' rows='5' required>$dosage</textarea>
  </div>
  <div class='input-group'>
  <span class='input-group-addon clock'>Frequency</span>
  <select class='form-control' name='frequency' id='editFrequency' required>
  <option value='' selected disabled>Select Frequency</option>
  <option value='AM'";
  if ($frequency=='AM') {
    echo " selected";
  }
  echo ">AM</option>
  <option value='PM'";
  if ($frequency=='PM') {
    echo " selected";
  }
  echo ">PM</option>
  <option value='2X'";
  if ($frequency=='2X') {
    echo " selected";
  }
  echo ">2X</option>
  <option value='3X'";
  if ($frequency=='3X') {
    echo " selected";
  }
  echo ">3X</option>
  <option value='As Needed'";
  if ($frequency=='As Needed') {
    echo " selected";
  }
  echo ">As Needed</option>
  <option value='Other'";
  if ($frequency=='Other') {
    echo " selected";
  }
  echo ">Other</option>
  </select>
  </div>
  <div class='input-group'>
  <span class='input-group-addon notes'>Notes</span>
  <textarea class='form-control' name='notes' id='editNotes' rows='5'>$notes</textarea>
  </div>";
}
?>
