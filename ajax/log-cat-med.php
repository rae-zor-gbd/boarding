<?php
include '../assets/config.php';
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
  $jsonMedsLog=json_decode(stripslashes($_POST['jsonMedsLog']));

  $sql_delete_log="DELETE FROM cats_log WHERE catReservationID='$reservationID' AND catMedID='$medID'";
  $conn->query($sql_delete_log);

  $sql_add_log="INSERT INTO cats_log (catReservationID, catMedID, logDate, givenAM, givenNoon, givenPM, notes) VALUES ";
  foreach ($dateRange as $loopDate) {
    $logDate=$loopDate->format('Y-m-d');
    $sql_add_log.="('$reservationID', '$medID', '$logDate', 'Yes', 'Yes', 'Yes', 'TEST')";
  }
  $conn->query($sql_add_log);
}
?>
