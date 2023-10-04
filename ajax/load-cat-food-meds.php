<?php
include '../assets/config.php';
if (isset($_POST['status']) AND isset($_POST['sortMeds'])) {
  $status=$_POST['status'];
  $sortMeds=$_POST['sortMeds'];
  $sql_all_cats="SELECT r.catReservationID, catFoodID, condoID, catName, foodType, feedingInstructions, specialNotes, foodAllergies, noSlipBowl, plasticBowl, slowFeeder, elevatedFeeder, separateToFeed FROM cats_reservations r JOIN cats_food f USING (catReservationID)";
  if ($sortMeds=='all') {
    $sql_all_cats.=" WHERE status='$status'";
  } elseif ($sortMeds=='am') {
    $sql_all_cats.=" JOIN cats_medications m USING (catReservationID) WHERE status='$status' AND frequency IN ('AM', '2X', '3X') GROUP BY r.catReservationID, catFoodID";
  } elseif ($sortMeds=='noon') {
    $sql_all_cats.=" JOIN cats_medications m USING (catReservationID) WHERE status='$status' AND frequency IN ('3X') GROUP BY r.catReservationID, catFoodID";
  } elseif ($sortMeds=='pm') {
    $sql_all_cats.=" JOIN cats_medications m USING (catReservationID) WHERE status='$status' AND frequency IN ('PM', '2X', '3X') GROUP BY r.catReservationID, catFoodID";
  }
  $sql_all_cats.=" ORDER BY condoID, catName";
  $result_all_cats=$conn->query($sql_all_cats);
  while ($row_all_cats=$result_all_cats->fetch_assoc()) {
    $boardingReservationID=$row_all_cats['catReservationID'];
    $boardingFoodID=$row_all_cats['catFoodID'];
    $boardingCondoID=$row_all_cats['condoID'];
    $boardingName=htmlspecialchars($row_all_cats['catName'], ENT_QUOTES);
    $boardingFoodType=$row_all_cats['foodType'];
    $boardingFeedingInstructions=nl2br(htmlspecialchars($row_all_cats['feedingInstructions'], ENT_QUOTES));
    $boardingSpecialNotes=htmlspecialchars($row_all_cats['specialNotes'], ENT_QUOTES);
    $boardingFoodAllergies=$row_all_cats['foodAllergies'];
    $boardingNoSlipBowl=$row_all_cats['noSlipBowl'];
    $boardingPlasticBowl=$row_all_cats['plasticBowl'];
    $boardingSlowFeeder=$row_all_cats['slowFeeder'];
    $boardingElevatedFeeder=$row_all_cats['elevatedFeeder'];
    $boardingSeparateToFeed=$row_all_cats['separateToFeed'];
    echo "<tr id='row-cat-$boardingFoodID'>
    <td>$boardingCondoID</td>
    <td>$boardingName</td>
    <td>
    <span class='label label-";
    if ($boardingFoodType=='Ours') {
      echo "success";
    } else {
      echo "default";
    }
    echo "'>$boardingFoodType</span>
    </td>
    <td>" . stripslashes($boardingFeedingInstructions);
    if (isset($boardingSpecialNotes) AND $boardingSpecialNotes!='') {
      echo "<div class='special-notes-label'>
      <span class='label label-info'>$boardingSpecialNotes</span>
      </div>";
    } else {
      echo "<br>";
    }
    if ($boardingFoodAllergies=='Yes') {
      echo "<span class='food-label label label-danger'>Food Allergies</span>";
    }
    if ($boardingSeparateToFeed=='Yes') {
      echo "<span class='food-label label label-primary'>Separate To Feed</span>";
    }
    if ($boardingNoSlipBowl=='Yes') {
      echo "<span class='food-label label label-primary'>No-Slip Bowl</span>";
    }
    if ($boardingPlasticBowl=='Yes') {
      echo "<span class='food-label label label-primary'>Plastic Bowl</span>";
    }
    if ($boardingSlowFeeder=='Yes') {
      echo "<span class='food-label label label-primary'>Slow Feeder</span>";
    }
    if ($boardingElevatedFeeder=='Yes') {
      echo "<span class='food-label label label-primary'>Elevated Feeder</span>";
    }
    echo "</td>
    <td>";
    $sql_cat_meds="SELECT catMedID, medName, strength, dosage, frequency, notes FROM cats_reservations r JOIN cats_medications m USING (catReservationID) WHERE catReservationID='$boardingReservationID'";
    if ($sortMeds=='am') {
      $sql_cat_meds.=" AND frequency IN ('AM', '2X', '3X')";
    } elseif ($sortMeds=='noon') {
      $sql_cat_meds.=" AND frequency IN ('3X')";
    } elseif ($sortMeds=='pm') {
      $sql_cat_meds.=" AND frequency IN ('PM', '2X', '3X')";
    }
    $sql_cat_meds.=" ORDER BY FIELD(frequency,'AM','2X','3X','PM','Other','As Needed'), medName, strength";
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
          echo "info";
        } else {
          echo "danger";
        }
        echo "'>$medName";
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
        <button type='button' class='button-edit' id='edit-med-button' data-toggle='modal' data-target='#editMedModal' data-id='$catMedID' data-status='$status' data-backdrop='static' title='Edit Medication'></button>
        <button type='button' class='button-delete' id='delete-med-button' data-toggle='modal' data-target='#deleteMedModal' data-id='$catMedID' data-status='$status' data-backdrop='static' title='Delete Medication'></button>
        </div>";
      }
    }
    echo "</td>
    <td style='text-align:right;'>";
    if ($status=='Future') {
      echo "<button type='button' class='button-check' id='check-cat-button' data-toggle='modal' data-target='#checkCatModal' data-id='$boardingFoodID' data-backdrop='static' title='Check In'></button>";
    }
    echo "<button type='button' class='button-edit' id='edit-cat-button' data-toggle='modal' data-target='#editCatModal' data-id='$boardingFoodID' data-status='$status' data-backdrop='static' title='Edit Food'></button>
    <button type='button' class='button-meds' id='add-med-button' data-toggle='modal' data-target='#addMedModal' data-status='$status' data-id='$boardingReservationID' data-backdrop='static' title='Add Medication'></button>
    <button type='button' class='button-delete' id='delete-cat-button' data-toggle='modal' data-target='#deleteCatModal' data-status='$status' data-id='$boardingFoodID' data-backdrop='static' title='Delete Cat'></button>
    </td>
    </tr>";
  }
}
?>
