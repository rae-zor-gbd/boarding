<?php
include '../assets/config.php';
function debug_to_console($data) {
  $output = $data;
  if (is_array($output))
      $output = implode(',', $output);

  echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}
if (isset($_POST['status']) AND isset($_POST['medID']) AND isset($_POST['reservationID']) AND isset($_POST['checkIn']) AND isset($_POST['checkOut']) AND isset($_POST['jsonMedsLog'])) {
  $status=$_POST['status'];
  $medID=$_POST['medID'];
  $reservationID=$_POST['reservationID'];
  $checkIn=$_POST['checkIn'];
  $rangeStart=date_create($checkIn);
  $checkOut=$_POST['checkOut'];
  $rangeEnd=date_create($checkOut);
  $interval=new DateInterval('P1D');
  $dateRange=new DatePeriod($rangeStart, $interval, $rangeEnd);
  $jsonMedsLog=json_decode($_POST['jsonMedsLog']);

  debug_to_console($checkOut);

  $sql_delete_log="DELETE FROM cats_medications_log WHERE catReservationID='$reservationID' AND catMedID='$medID'";
  $conn->query($sql_delete_log);

  foreach ($dateRange as $loopDate) {
    $logDate=$loopDate->format('Y-m-d');
    $givenAM=$jsonMedsLog['givenAM'.$logDate];
    $givenNoon=$jsonMedsLog['givenNoon'.$logDate];
    $givenPM=$jsonMedsLog['givenPM'.$logDate];
    $notes=mysqli_real_escape_string($conn, $jsonMedsLog['logNotes'.$logDate]);
    $sql_add_log="INSERT INTO cats_medications_log (catReservationID, catMedID, logDate, givenAM, givenNoon, givenPM, notes) VALUES ('$reservationID', '$medID', '$logDate', 'No', 'No', 'No', '$notes')";
    $conn->query($sql_add_log);
  }
}
?>
