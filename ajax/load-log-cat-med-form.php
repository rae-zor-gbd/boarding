<?php
include '../assets/config.php';
if (isset($_POST['status']) AND isset($_POST['id'])) {
  $status=$_POST['status'];
  $medID=$_POST['id'];
  $sql_med_info="SELECT catReservationID, medName, strength, dosage, frequency, notes FROM cats_medications WHERE catMedID='$medID'";
  $result_med_info=$conn->query($sql_med_info);
  $row_med_info=$result_med_info->fetch_assoc();
  $reservationID=$row_med_info['catReservationID'];
  $medName=htmlspecialchars($row_med_info['medName'], ENT_QUOTES);
  $strength=htmlspecialchars($row_med_info['strength'], ENT_QUOTES);
  $dosage=htmlspecialchars($row_med_info['dosage'], ENT_QUOTES);
  $frequency=$row_med_info['frequency'];
  $medNotes=htmlspecialchars($row_med_info['notes'], ENT_QUOTES);
  $sql_reservation_info="SELECT checkIn, DATE_ADD(checkOut, INTERVAL 1 DAY) AS checkOut FROM cats_reservations WHERE catReservationID='$reservationID'";
  $result_reservation_info=$conn->query($sql_reservation_info);
  $row_reservation_info=$result_reservation_info->fetch_assoc();
  $checkIn=$row_reservation_info['checkIn'];
  $rangeStart=date_create($checkIn);
  $checkOut=$row_reservation_info['checkOut'];
  $rangeEnd=date_create($checkOut);
  $interval=new DateInterval('P1D');
  $dateRange=new DatePeriod($rangeStart, $interval, $rangeEnd);
  echo "<input type='hidden' class='form-control' name='status' id='logStatus' value='$status' required>
  <input type='hidden' class='form-control' name='med-id' id='logMedID' value='$medID' required>
  <input type='hidden' class='form-control' name='reservation-id' id='logReservationID' value='$reservationID' required>
  <input type='hidden' class='form-control' name='check-in' id='logCheckIn' value='$checkIn' required>
  <input type='hidden' class='form-control' name='check-out' id='logCheckOut' value='$checkOut' required>
  <div class='log-med-header'>$medName";
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
  if (isset($medNotes) AND $medNotes!='') {
    echo " <em>$medNotes</em>";
  }
  echo ")
  </div>
  <table class='table table-hover table-condensed log-med-table'>
  <thead>
  <tr>
  <th>Date</th>
  <th>AM</th>
  <th>Noon</th>
  <th>PM</th>
  <th>Notes</th>
  </tr>
  </thead>
  <tbody>";
  foreach ($dateRange as $logDate) {
    $logDateV1=$logDate->format('Y-m-d');
    $logDateV2=$logDate->format('D n/j');
    $sql_log_info="SELECT givenAM, givenNoon, givenPM, l.notes FROM cats_medications_log l JOIN cats_medications m USING (catMedID) WHERE l.catReservationID='$reservationID' AND l.catMedID='$medID' AND logDate='$logDateV1'";
    $result_log_info=$conn->query($sql_log_info);
    if ($result_log_info->num_rows>0) {
      $row_log_info=$result_log_info->fetch_assoc();
      $givenAM=$row_log_info['givenAM'];
      $givenNoon=$row_log_info['givenNoon'];
      $givenPM=$row_log_info['givenPM'];
      $logNotes=htmlspecialchars($row_log_info['notes'], ENT_QUOTES);
    } else {
      $givenAM='No';
      $givenNoon='No';
      $givenPM='No';
      $logNotes=NULL;
    }
    echo "<tr>
    <td>" . $logDateV2 . "</td>
    <td><input type='checkbox' id='givenAM$logDateV1' name='given-am-$logDateV1' value='$logDateV1'";
    if ($frequency=='PM') {
      echo " disabled";
    }
    if ($givenAM=='Yes') {
      echo " checked";
    }
    echo "></td>
    <td><input type='checkbox' id='givenNoon$logDateV1' name='given-noon-$logDateV1' value='$logDateV1'";
    if ($frequency=='AM' OR $frequency=='PM' OR $frequency=='2X') {
      echo " disabled";
    }
    if ($givenNoon=='Yes') {
      echo " checked";
    }
    echo "></td>
    <td><input type='checkbox' id='givenPM$logDateV1' name='given-pm-$logDateV1' value='$logDateV1'";
    if ($frequency=='AM') {
      echo " disabled";
    }
    if ($givenPM=='Yes') {
      echo " checked";
    }
    echo "></td>
    <td><textarea class='form-control' name='log-notes-$logDateV1' id='logNotes$logDateV1' rows='3'>$logNotes</textarea></td>
    </tr>";
  }
  echo "</tbody>
  </table>";
}
?>
