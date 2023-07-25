<?php
include 'config.php';
if (isset($_POST['status'])) {
  $status=$_POST['status'];
  $sql_all_dogs="SELECT dogID, roomID, dogName, foodType, feedingInstructions FROM dogs WHERE status='$status' ORDER BY roomID, dogName";
  $result_all_dogs=$conn->query($sql_all_dogs);
  while ($row_all_dogs=$result_all_dogs->fetch_assoc()) {
    $boardingDogID=$row_all_dogs['dogID'];
    $boardingRoomID=$row_all_dogs['roomID'];
    $boardingName=htmlspecialchars($row_all_dogs['dogName'], ENT_QUOTES);
    $boardingFoodType=$row_all_dogs['foodType'];
    $boardingFeedingInstructions=htmlspecialchars($row_all_dogs['feedingInstructions'], ENT_QUOTES);
    echo "<tr id='row-dog-$boardingDogID'>
    <td>$boardingRoomID</td>
    <td>$boardingName</td>
    <td>
    <span class='label label-";
    if ($boardingFoodType=='Ours') {
      echo "success";
    } else {
      echo "default";
    }
    echo "'>$boardingFoodType<span>
    </td>
    <td>$boardingFeedingInstructions</td>
    <td>";
    $sql_dog_meds="SELECT dogMedID, dogID, medName, strength, dosage, frequency FROM dogs d JOIN dogs_medications m USING (dogID) WHERE dogID='$boardingDogID' ORDER BY FIELD(frequency,'AM','2X','3X','PM','As Needed'), medName, strength";
    $result_dog_meds=$conn->query($sql_dog_meds);
    if ($result_dog_meds->num_rows>0) {
      while ($row_dog_meds=$result_dog_meds->fetch_assoc()) {
        $medName=htmlspecialchars($row_dog_meds['medName'], ENT_QUOTES);
        $strength=$row_dog_meds['strength'];
        $dosage=htmlspecialchars($row_dog_meds['dosage'], ENT_QUOTES);
        $frequency=$row_dog_meds['frequency'];
        echo "<span class='label label-";
        if ($frequency=='As Needed') {
          echo "warning";
        } else {
          echo "danger";
        }
        echo "'>$medName, $strength ($dosage $frequency)</span><br>";
      }
    } else {
      echo "<em class='text-muted'>None</em>";
    }
    echo "</td>
    <td style='text-align:right;'>";
    if ($status=='Future') {
      echo "<button type='button' class='button-check' id='check-dog-button' data-toggle='modal' data-target='#checkDogModal' data-id='$boardingDogID' data-backdrop='static' title='Check In'></button>";
    }
    echo "<button type='button' class='button-edit' id='edit-dog-button' data-toggle='modal' data-target='#editDogModal' data-id='$boardingDogID' data-backdrop='static' title='Edit'></button>
    <button type='button' class='button-meds' id='add-meds-button' data-toggle='modal' data-target='#addMedsModal' data-status='$status' data-id='$boardingDogID' data-backdrop='static' title='Add Meds'></button>
    <button type='button' class='button-delete' id='delete-dog-button' data-toggle='modal' data-target='#deleteDogModal' data-id='$boardingDogID' data-backdrop='static' title='Delete'></button>
    </td>
    </tr>";
  }
}
?>
