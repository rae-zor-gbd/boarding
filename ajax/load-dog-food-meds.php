<?php
include '../assets/config.php';
if (isset($_POST['status']) AND isset($_POST['sortMeds'])) {
  $status=$_POST['status'];
  $sortMeds=$_POST['sortMeds'];
  if ($sortMeds=='all') {
    $sql_all_dogs="SELECT dogID, roomID, dogName, foodType, feedingInstructions FROM dogs WHERE status='$status' ORDER BY roomID, dogName";
  } elseif ($sortMeds=='am') {
    $sql_all_dogs="SELECT dogID, roomID, dogName, foodType, feedingInstructions FROM dogs JOIN dogs_medications m USING (dogID) WHERE status='$status' AND frequency IN ('AM', '2X', '3X') GROUP BY dogID ORDER BY roomID, dogName";
  } elseif ($sortMeds=='noon') {
    $sql_all_dogs="SELECT dogID, roomID, dogName, foodType, feedingInstructions FROM dogs JOIN dogs_medications m USING (dogID) WHERE status='$status' AND frequency IN ('3X') GROUP BY dogID ORDER BY roomID, dogName";
  } elseif ($sortMeds=='pm') {
    $sql_all_dogs="SELECT dogID, roomID, dogName, foodType, feedingInstructions FROM dogs JOIN dogs_medications m USING (dogID) WHERE status='$status' AND frequency IN ('PM', '2X', '3X') GROUP BY dogID ORDER BY roomID, dogName";
  }
  $result_all_dogs=$conn->query($sql_all_dogs);
  while ($row_all_dogs=$result_all_dogs->fetch_assoc()) {
    $boardingDogID=$row_all_dogs['dogID'];
    $boardingRoomID=$row_all_dogs['roomID'];
    $boardingName=htmlspecialchars($row_all_dogs['dogName'], ENT_QUOTES);
    $boardingFoodType=$row_all_dogs['foodType'];
    $boardingFeedingInstructions=nl2br(htmlspecialchars($row_all_dogs['feedingInstructions'], ENT_QUOTES));
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
    if ($sortMeds=='all') {
      $sql_dog_meds="SELECT dogMedID, medName, strength, dosage, frequency, notes FROM dogs d JOIN dogs_medications m USING (dogID) WHERE dogID='$boardingDogID' ORDER BY FIELD(frequency,'AM','2X','3X','PM','Other','As Needed'), medName, strength";
    } elseif ($sortMeds=='am') {
      $sql_dog_meds="SELECT dogMedID, medName, strength, dosage, frequency, notes FROM dogs d JOIN dogs_medications m USING (dogID) WHERE dogID='$boardingDogID' AND frequency IN ('AM', '2X', '3X') ORDER BY FIELD(frequency,'AM','2X','3X','PM','Other','As Needed'), medName, strength";
    } elseif ($sortMeds=='noon') {
      $sql_dog_meds="SELECT dogMedID, medName, strength, dosage, frequency, notes FROM dogs d JOIN dogs_medications m USING (dogID) WHERE dogID='$boardingDogID' AND frequency IN ('3X') ORDER BY FIELD(frequency,'AM','2X','3X','PM','Other','As Needed'), medName, strength";
    } elseif ($sortMeds=='pm') {
      $sql_dog_meds="SELECT dogMedID, medName, strength, dosage, frequency, notes FROM dogs d JOIN dogs_medications m USING (dogID) WHERE dogID='$boardingDogID' AND frequency IN ('PM', '2X', '3X') ORDER BY FIELD(frequency,'AM','2X','3X','PM','Other','As Needed'), medName, strength";
    }
    $result_dog_meds=$conn->query($sql_dog_meds);
    if ($result_dog_meds->num_rows>0) {
      while ($row_dog_meds=$result_dog_meds->fetch_assoc()) {
        $dogMedID=$row_dog_meds['dogMedID'];
        $medName=htmlspecialchars($row_dog_meds['medName'], ENT_QUOTES);
        $strength=$row_dog_meds['strength'];
        $dosage=htmlspecialchars($row_dog_meds['dosage'], ENT_QUOTES);
        $frequency=$row_dog_meds['frequency'];
        $notes=htmlspecialchars($row_dog_meds['notes'], ENT_QUOTES);
        echo "<div class='medication-label' id='med-label-$dogMedID'>
        <span class='label label-";
        if ($frequency=='As Needed') {
          echo "warning";
        } elseif ($frequency=='Other') {
          echo "info";
        } else {
          echo "danger";
        }
        echo "'>$medName";
        if (isset($strength) AND $strength!='') {
          echo ", $strength";
        }
        echo " ($dosage";
        if ($frequency!='Other') {
          echo " $frequency";
        }
        if (isset($notes) AND $notes!='') {
          echo ", $notes";
        }
        echo ")</span>
        <button type='button' class='button-edit' id='edit-med-button' data-toggle='modal' data-target='#editMedModal' data-id='$dogMedID' data-status='$status' data-backdrop='static' title='Edit Medication'></button>
        <button type='button' class='button-delete' id='delete-med-button' data-toggle='modal' data-target='#deleteMedModal' data-id='$dogMedID' data-backdrop='static' title='Delete Medication'></button>
        </div>";
      }
    }
    echo "</td>
    <td style='text-align:right;'>";
    if ($status=='Future') {
      echo "<button type='button' class='button-check' id='check-dog-button' data-toggle='modal' data-target='#checkDogModal' data-id='$boardingDogID' data-backdrop='static' title='Check In'></button>";
    }
    echo "<button type='button' class='button-edit' id='edit-dog-button' data-toggle='modal' data-target='#editDogModal' data-id='$boardingDogID' data-status='$status' data-backdrop='static' title='Edit Food'></button>
    <button type='button' class='button-meds' id='add-med-button' data-toggle='modal' data-target='#addMedModal' data-status='$status' data-id='$boardingDogID' data-backdrop='static' title='Add Medication'></button>
    <button type='button' class='button-delete' id='delete-dog-button' data-toggle='modal' data-target='#deleteDogModal' data-id='$boardingDogID' data-backdrop='static' title='Delete Dog'></button>
    </td>
    </tr>";
  }
}
?>
