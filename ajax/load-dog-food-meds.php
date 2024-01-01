<?php
include '../assets/config.php';
if (isset($_POST['status']) AND isset($_POST['sortMeds'])) {
  $status=$_POST['status'];
  $sortMeds=$_POST['sortMeds'];
  $sql_all_dogs="SELECT r.dogReservationID, dogFoodID, checkIn, checkOut, roomID, dogName, foodType, feedingInstructions, specialNotes FROM dogs_reservations r JOIN dogs_food f USING (dogReservationID)";
  if ($sortMeds=='all') {
    $sql_all_dogs.=" WHERE status='$status' AND checkOut>=DATE(NOW())";
  } elseif ($sortMeds=='am') {
    $sql_all_dogs.=" JOIN dogs_medications m USING (dogReservationID) WHERE status='$status' AND checkOut>=DATE(NOW()) AND frequency IN ('AM', '2X', '3X') GROUP BY r.dogReservationID, dogFoodID";
  } elseif ($sortMeds=='noon') {
    $sql_all_dogs.=" JOIN dogs_medications m USING (dogReservationID) WHERE status='$status' AND checkOut>=DATE(NOW()) AND frequency IN ('3X') GROUP BY r.dogReservationID, dogFoodID";
  } elseif ($sortMeds=='pm') {
    $sql_all_dogs.=" JOIN dogs_medications m USING (dogReservationID) WHERE status='$status' AND checkOut>=DATE(NOW()) AND frequency IN ('PM', '2X', '3X') GROUP BY r.dogReservationID, dogFoodID";
  }
  if ($status=='Active') {
    $sql_all_dogs.=" ORDER BY roomID, dogName";
  } elseif ($status=='Future') {
    $sql_all_dogs.=" ORDER BY checkIn, dogName";
  }
  $result_all_dogs=$conn->query($sql_all_dogs);
  while ($row_all_dogs=$result_all_dogs->fetch_assoc()) {
    $boardingReservationID=$row_all_dogs['dogReservationID'];
    $boardingFoodID=$row_all_dogs['dogFoodID'];
    $boardingCheckIn=strtotime($row_all_dogs['checkIn']);
    $boardingCheckOut=strtotime($row_all_dogs['checkOut']);
    $dateToday=strtotime(date('Y-m-d'));
    $boardingRoomID=$row_all_dogs['roomID'];
    $boardingName=htmlspecialchars($row_all_dogs['dogName'], ENT_QUOTES);
    $boardingFoodType=$row_all_dogs['foodType'];
    $boardingFeedingInstructions=nl2br(htmlspecialchars($row_all_dogs['feedingInstructions'], ENT_QUOTES));
    $boardingSpecialNotes=htmlspecialchars($row_all_dogs['specialNotes'], ENT_QUOTES);
    echo "<tr id='row-dog-$boardingFoodID'>";
    if ($status=='Future') {
      echo "<td>" . date('D n/j', $boardingCheckIn) . "</td>";
    }
    echo "<td>$boardingRoomID</td>
    <td";
    if ($boardingCheckOut==$dateToday) {
      echo " class='checkOutToday'";
    }
    echo ">$boardingName</td>
    <td>
    <span class='label label-";
    if ($boardingFoodType=='Ours') {
      echo "success";
    } else {
      echo "default";
    }
    echo "'>$boardingFoodType</span>
    </td>
    <td>" . stripslashes($boardingFeedingInstructions) . "<br>";
    $sql_display_tags="SELECT tagName FROM dogs_tags dt JOIN tags t USING (tagID) WHERE dogReservationID='$boardingReservationID' ORDER BY sortID";
    $result_display_tags=$conn->query($sql_display_tags);
    if ($result_display_tags->num_rows>0 OR $boardingSpecialNotes!='') {
      echo "<div class='special-notes-label'>
      <span class='label label-info'>";
      while ($row_display_tags=$result_display_tags->fetch_assoc()) {
        $tagName=htmlspecialchars($row_display_tags['tagName'], ENT_QUOTES);
        echo "<span class='special-notes-tag'>$tagName</span>";
      }
      if (isset($boardingSpecialNotes) AND $boardingSpecialNotes!='') {
        echo "<span class='special-notes-tag'>$boardingSpecialNotes</span>";
      }
      echo "</span>
      </div>";
    }
    echo "</td>
    <td>";
    $sql_dog_meds="SELECT dogMedID, medName, strength, dosage, frequency, notes FROM dogs_reservations r JOIN dogs_medications m USING (dogReservationID) WHERE dogReservationID='$boardingReservationID'";
    if ($sortMeds=='am') {
      $sql_dog_meds.=" AND frequency IN ('AM', '2X', '3X')";
    } elseif ($sortMeds=='noon') {
      $sql_dog_meds.=" AND frequency IN ('3X')";
    } elseif ($sortMeds=='pm') {
      $sql_dog_meds.=" AND frequency IN ('PM', '2X', '3X')";
    }
    $sql_dog_meds.=" ORDER BY FIELD(frequency,'AM','2X','3X','PM','Other','As Needed'), medName, strength";
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
          echo "primary";
        } else {
          echo "danger";
        }
        echo "'><span class='medication-label-instructions'><strong>$medName</strong>";
        if (isset($strength) AND $strength!='') {
          echo ", $strength";
        }
        echo " (";
        if (isset($dosage) AND $dosage!='') {
          echo "$dosage ";
        }
        if ($frequency!='Other') {
          echo "$frequency";
        }
        if (isset($notes) AND $notes!='') {
          echo " <span class='medication-notes'>$notes</span>";
        }
        echo ")</span>
        <span class='medication-label-buttons'>
        <button type='button' class='button-log' id='log-med-button' data-toggle='modal' data-target='#logMedModal' data-id='$dogMedID' data-status='$status' data-backdrop='static' title='Log Medication'></button>
        <button type='button' class='button-edit' id='edit-med-button' data-toggle='modal' data-target='#editMedModal' data-id='$dogMedID' data-status='$status' data-backdrop='static' title='Edit Medication'></button>
        <button type='button' class='button-delete' id='delete-med-button' data-toggle='modal' data-target='#deleteMedModal' data-id='$dogMedID' data-status='$status' data-backdrop='static' title='Delete Medication'></button>
        </span>
        </span>
        </div>";
      }
    }
    echo "</td>
    <td style='text-align:right;'>";
    if ($status=='Future') {
      echo "<button type='button' class='button-check' id='check-dog-button' data-toggle='modal' data-target='#checkDogModal' data-id='$boardingFoodID' data-backdrop='static' title='Check In'></button>";
    } elseif ($boardingCheckOut==$dateToday) {
      echo "<button type='button' class='button-door' id='check-out-dog-button' data-toggle='modal' data-target='#checkOutDogModal' data-id='$boardingReservationID' data-row='$boardingFoodID' data-backdrop='static' title='Check Out'></button>";
    }
    echo "<button type='button' class='button-edit' id='edit-dog-button' data-toggle='modal' data-target='#editDogModal' data-id='$boardingFoodID' data-status='$status' data-backdrop='static' title='Edit Food'></button>
    <button type='button' class='button-meds' id='add-med-button' data-toggle='modal' data-target='#addMedModal' data-status='$status' data-id='$boardingReservationID' data-backdrop='static' title='Add Medication'></button>
    <button type='button' class='button-delete' id='delete-dog-button' data-toggle='modal' data-target='#deleteDogModal' data-status='$status' data-id='$boardingFoodID' data-backdrop='static' title='Delete Food'></button>
    </td>
    </tr>";
  }
}
?>
