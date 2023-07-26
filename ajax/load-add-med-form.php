<?php
include '../assets/config.php';
if (isset($_POST['status']) AND isset($_POST['id'])) {
  $status=$_POST['status'];
  $id=$_POST['id'];
  echo "<input type='hidden' class='form-control' name='status' id='newStatus' value='$status' required>
  <input type='hidden' class='form-control' name='id' id='newID' value='$id' required>
  <div class='input-group'>
  <span class='input-group-addon meds'>Medication Name</span>
  <input type='text' class='form-control' name='med-name' maxlength='255' list='meds' id='newMedName' style='border-bottom-right-radius:4px; border-top-right-radius:4px;' required>
  <datalist id='meds'>";
  $sql_all_meds="SELECT medName FROM medications ORDER BY medName";
  $result_all_meds=$conn->query($sql_all_meds);
  while ($row_all_meds=$result_all_meds->fetch_assoc()) {
    $medName=htmlspecialchars($row_all_meds['medName'], ENT_QUOTES);
    echo "<option value='$medName'></option>";
  }
  echo "</datalist>
  </div>
  <div class='input-group'>
  <span class='input-group-addon chart'>Strength</span>
  <input type='text' class='form-control' name='strength' maxlength='255' id='newStrength'>
  </div>
  <div class='input-group'>
  <span class='input-group-addon list'>Dosage</span>
  <textarea class='form-control' name='dosage' id='newDosage' rows='5' required></textarea>
  </div>
  <div class='input-group'>
  <span class='input-group-addon clock'>Frequency</span>
  <select class='form-control' name='frequency' id='newFrequency' required>
  <option value='' selected disabled>Select Frequency</option>
  <option value='AM'>AM</option>
  <option value='PM'>PM</option>
  <option value='2X'>2X</option>
  <option value='3X'>3X</option>
  <option value='As Needed'>As Needed</option>
  <option value='Other'>Other</option>
  </select>
  </div>
  <div class='input-group'>
  <span class='input-group-addon notes'>Notes</span>
  <textarea class='form-control' name='notes' id='newNotes' rows='5'></textarea>
  </div>";
}
?>
