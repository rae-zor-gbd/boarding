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
  $jsonMedsLog=json_decode($_POST['jsonMedsLog'], true);
  $sql_delete_log="DELETE FROM dogs_medications_log WHERE dogReservationID='$reservationID' AND dogMedID='$medID'";
  $conn->query($sql_delete_log);
  foreach ($dateRange as $loopDate) {
    $logDate=$loopDate->format('Y-m-d');
    $givenAM=$jsonMedsLog['givenAM'.$logDate];
    $givenNoon=$jsonMedsLog['givenNoon'.$logDate];
    $givenPM=$jsonMedsLog['givenPM'.$logDate];
    $notes=mysqli_real_escape_string($conn, trim($jsonMedsLog['logNotes'.$logDate]));
    $sql_add_log="INSERT INTO dogs_medications_log (dogReservationID, dogMedID, logDate, givenAM, givenNoon, givenPM, notes) VALUES ('$reservationID', '$medID', '$logDate', '$givenAM', '$givenNoon', '$givenPM', '$notes')";
    $conn->query($sql_add_log);
  }
}
?>
