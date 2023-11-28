<?php
include '../assets/config.php';
if (isset($_POST['status']) AND isset($_POST['id'])) {
  $status=$_POST['status'];
  $id=$_POST['id'];
  $sql_med_info="SELECT catReservationID, medName, strength, dosage, frequency, notes FROM cats_medications WHERE catMedID='$id'";
  $result_med_info=$conn->query($sql_med_info);
  $row_med_info=$result_med_info->fetch_assoc();
  $reservationID=$row_med_info['catReservationID'];
  $medName=htmlspecialchars($row_med_info['medName'], ENT_QUOTES);
  $strength=htmlspecialchars($row_med_info['strength'], ENT_QUOTES);
  $dosage=htmlspecialchars($row_med_info['dosage'], ENT_QUOTES);
  $frequency=$row_med_info['frequency'];
  $notes=htmlspecialchars($row_med_info['notes'], ENT_QUOTES);
  $sql_reservation_info="SELECT checkIn, DATE_ADD(checkOut, INTERVAL 1 DAY) AS checkOut FROM cats_reservations WHERE catReservationID='$reservationID'";
  $result_reservation_info=$conn->query($sql_reservation_info);
  $row_reservation_info=$result_reservation_info->fetch_assoc();
  $checkIn=date_create($row_reservation_info['checkIn']);
  $checkOut=date_create($row_reservation_info['checkOut']);
  $interval=new DateInterval('P1D');
  $dateRange=new DatePeriod($checkIn, $interval, $checkOut);
  echo "<input type='hidden' class='form-control' name='status' id='logStatus' value='$status' required>
  <input type='hidden' class='form-control' name='id' id='logID' value='$id' required>
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
  if (isset($notes) AND $notes!='') {
    echo " <em>$notes</em>";
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
    echo "<tr>
    <td>" . $logDate->format('D n/j') . "</td>
    <td><input type='checkbox'></td>
    <td><input type='checkbox'></td>
    <td><input type='checkbox'></td>
    <td><textarea class='form-control' name='log-notes' id='logNotes' rows='3'></textarea></td>
    </tr>";
  }
  echo "</tbody>
  </table>";
}
?>
