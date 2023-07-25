<?php
include 'config.php';
if (isset($_POST['status']) AND isset($_POST['id'])) {
  $status=$_POST['status'];
  $id=$_POST['id'];
  echo "<input type='hidden' class='form-control' name='status' id='newStatus' value='$status' required>
  <input type='hidden' class='form-control' name='id' id='newID' value='$id' required>
  <div class='input-group'>
  <span class='input-group-addon dog'>Medication Name</span>
  <input type='text' class='form-control' name='med-name' maxlength='255' id='newMedName' required>
  </div>
  <div class='input-group'>
  <span class='input-group-addon dog'>Strength</span>
  <input type='text' class='form-control' name='strength' maxlength='255' id='newStrength'>
  </div>
  <div class='input-group'>
  <span class='input-group-addon food'>Dosage</span>
  <textarea class='form-control' name='dosage' id='newDosage' rows='5' required></textarea>
  </div>
  <div class='input-group'>
  <span class='input-group-addon food'>Frequency</span>
  <select class='form-control' name='frequency' id='newFrequency' required>
  <option value='' selected disabled>Select Frequency</option>
  <option value='AM'>AM</option>
  <option value='PM'>PM</option>
  <option value='2X'>2X</option>
  <option value='3X'>3X</option>
  <option value='As Needed'>As Needed</option>
  </select>
  </div>";
}
?>
