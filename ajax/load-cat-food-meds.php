<?php
include '../assets/config.php';
if (isset($_POST['status']) AND isset($_POST['sortMeds'])) {
  $status=$_POST['status'];
  $sortMeds=$_POST['sortMeds'];
  $sql_all_cats="SELECT r.catReservationID, catFoodID, checkIn, checkOut, condoID, lastName, catName, foodType, feedingInstructions, specialNotes FROM cats_reservations r JOIN cats_food f USING (catReservationID)";
  if ($sortMeds=='all') {
    $sql_all_cats.=" WHERE status='$status' AND checkOut>=DATE(NOW())";
  } elseif ($sortMeds=='am') {
    $sql_all_cats.=" JOIN cats_medications m USING (catReservationID) WHERE status='$status' AND checkOut>=DATE(NOW()) AND frequency IN ('AM', '2X', '3X') GROUP BY r.catReservationID, catFoodID";
  } elseif ($sortMeds=='noon') {
    $sql_all_cats.=" JOIN cats_medications m USING (catReservationID) WHERE status='$status' AND checkOut>=DATE(NOW()) AND frequency IN ('Noon', '3X') GROUP BY r.catReservationID, catFoodID";
  } elseif ($sortMeds=='pm') {
    $sql_all_cats.=" JOIN cats_medications m USING (catReservationID) WHERE status='$status' AND checkOut>=DATE(NOW()) AND frequency IN ('PM', '2X', '3X') GROUP BY r.catReservationID, catFoodID";
  }
  if ($status=='Active') {
    $sql_all_cats.=" ORDER BY condoID, lastName, catName";
  } elseif ($status=='Future') {
    $sql_all_cats.=" ORDER BY checkIn, lastName, catName";
  }
  $result_all_cats=$conn->query($sql_all_cats);
  while ($row_all_cats=$result_all_cats->fetch_assoc()) {
    $boardingReservationID=$row_all_cats['catReservationID'];
    $boardingFoodID=$row_all_cats['catFoodID'];
    $boardingCheckIn=strtotime($row_all_cats['checkIn']);
    $boardingCheckOut=strtotime($row_all_cats['checkOut']);
    $dateToday=strtotime(date('Y-m-d'));
    $boardingCondoID=$row_all_cats['condoID'];
    $boardingLastName=htmlspecialchars($row_all_cats['lastName'], ENT_QUOTES);
    $boardingCatName=htmlspecialchars($row_all_cats['catName'], ENT_QUOTES);
    $boardingFoodType=$row_all_cats['foodType'];
    $boardingFeedingInstructions=nl2br(htmlspecialchars($row_all_cats['feedingInstructions'], ENT_QUOTES));
    $boardingSpecialNotes=htmlspecialchars($row_all_cats['specialNotes'], ENT_QUOTES);
    echo "<tr id='row-cat-$boardingFoodID'>";
    if ($status=='Future') {
      echo "<td>" . date('D n/j', $boardingCheckIn) . "</td>";
    }
    echo "<td>$boardingCondoID</td>
    <td";
    if ($boardingCheckOut==$dateToday) {
      echo " class='checkOutToday'";
    }
    echo ">$boardingCatName $boardingLastName</td>
    <td>
    <span class='label label-";
    if ($boardingFoodType=='Ours') {
      echo "success";
    } elseif ($boardingFoodType=='Own & Ours') {
      echo "info";
    } else {
      echo "default";
    }
    echo "'>$boardingFoodType</span>
    </td>
    <td>" . stripslashes($boardingFeedingInstructions) . "<br>";
    $sql_display_tags="SELECT tagName FROM cats_tags dt JOIN tags t USING (tagID) WHERE catReservationID='$boardingReservationID' ORDER BY sortID";
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
    $sql_cat_meds="SELECT catMedID, medName, strength, dosage, frequency, notes FROM cats_reservations r JOIN cats_medications m USING (catReservationID) WHERE catReservationID='$boardingReservationID'";
    if ($sortMeds=='am') {
      $sql_cat_meds.=" AND frequency IN ('AM', '2X', '3X')";
    } elseif ($sortMeds=='noon') {
      $sql_cat_meds.=" AND frequency IN ('Noon', '3X')";
    } elseif ($sortMeds=='pm') {
      $sql_cat_meds.=" AND frequency IN ('PM', '2X', '3X')";
    }
    $sql_cat_meds.=" ORDER BY FIELD(frequency,'AM','2X','Noon','3X','PM','Other','As Needed'), medName, strength";
    $result_cat_meds=$conn->query($sql_cat_meds);
    if ($result_cat_meds->num_rows>0) {
      while ($row_cat_meds=$result_cat_meds->fetch_assoc()) {
        $catMedID=$row_cat_meds['catMedID'];
        $medName=htmlspecialchars($row_cat_meds['medName'], ENT_QUOTES);
        $strength=$row_cat_meds['strength'];
        $dosage=htmlspecialchars($row_cat_meds['dosage'], ENT_QUOTES);
        $frequency=$row_cat_meds['frequency'];
        $notes=htmlspecialchars($row_cat_meds['notes'], ENT_QUOTES);
        echo "<div class='medication-label' id='med-label-$catMedID'>
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
        <button type='button' class='button-log' id='log-med-button' data-toggle='modal' data-target='#logMedModal' data-id='$catMedID' data-status='$status' data-backdrop='static' title='Log Medication'></button>
        <button type='button' class='button-edit' id='edit-med-button' data-toggle='modal' data-target='#editMedModal' data-id='$catMedID' data-status='$status' data-backdrop='static' title='Edit Medication'></button>
        <button type='button' class='button-delete' id='delete-med-button' data-toggle='modal' data-target='#deleteMedModal' data-id='$catMedID' data-status='$status' data-backdrop='static' title='Delete Medication'></button>
        </span>
        </span>
        </div>";
      }
    }
    echo "</td>
    <td style='text-align:right;'>";
    if ($status=='Future') {
      echo "<button type='button' class='button-check' id='check-cat-button' data-toggle='modal' data-target='#checkCatModal' data-id='$boardingFoodID' data-backdrop='static' title='Check In'></button>";
    } elseif ($boardingCheckOut==$dateToday) {
      echo "<button type='button' class='button-door' id='check-out-cat-button' data-toggle='modal' data-target='#checkOutCatModal' data-id='$boardingReservationID' data-row='$boardingFoodID' data-backdrop='static' title='Check Out'></button>";
    }
    echo "<button type='button' class='button-edit' id='edit-cat-button' data-toggle='modal' data-target='#editCatModal' data-id='$boardingFoodID' data-status='$status' data-backdrop='static' title='Edit Food'></button>
    <button type='button' class='button-meds' id='add-med-button' data-toggle='modal' data-target='#addMedModal' data-status='$status' data-id='$boardingReservationID' data-backdrop='static' title='Add Medication'></button>
    <button type='button' class='button-delete' id='delete-cat-button' data-toggle='modal' data-target='#deleteCatModal' data-status='$status' data-id='$boardingFoodID' data-backdrop='static' title='Delete Food'></button>
    </td>
    </tr>";
  }
}
?>
