<?php
include '../assets/config.php';
$doubleBookedReservations=array();
$sql_doubleBookedReservations="SELECT DISTINCT dogReservationID FROM (SELECT a.dogReservationID FROM (SELECT dogReservationID, roomID, lastName, dogName, checkIn, checkOut FROM dogs_reservations WHERE checkOut>=DATE(NOW()) AND roomID IN (SELECT roomID FROM (SELECT roomID, COUNT(dogReservationID) FROM dogs_reservations WHERE checkOut>=DATE(NOW()) GROUP BY roomID HAVING COUNT(dogReservationID)>1) r)) a JOIN (SELECT dogReservationID, roomID, lastName, dogName, checkIn, checkOut FROM dogs_reservations WHERE checkOut>=DATE(NOW()) AND roomID IN (SELECT roomID FROM (SELECT roomID, COUNT(dogReservationID) FROM dogs_reservations WHERE checkOut>=DATE(NOW()) GROUP BY roomID HAVING COUNT(dogReservationID)>1) r)) b USING (roomID) WHERE a.dogReservationID<>b.dogReservationID  AND a.lastName<>b.lastName AND a.checkIn<=b.checkOut AND a.checkOut>=b.checkIn GROUP BY a.roomID, a.dogReservationID, a.lastName, a.dogName, a.checkIn, a.checkOut ORDER BY a.roomID, a.checkIn, a.lastName, a.dogName) d";
$result_doubleBookedReservations=$conn->query($sql_doubleBookedReservations);
while ($row_doubleBookedReservations=$result_doubleBookedReservations->fetch_assoc()) {
  $doubleBookedReservationsID=$row_doubleBookedReservations['dogReservationID'];
  $doubleBookedReservations[]=$doubleBookedReservationsID;
}
function loadRoomReservation($loadRoomID, $loadColumnID, $loadRowID, $loadHooks, $loadCovered, $loadRoomStatus, $loadStartDate, $loadEndDate, $loadDescription) {
  include '../assets/config.php';
  echo "<div class='room-row";
  if ($loadRoomStatus=='Disabled') {
    echo " disabled";
  } elseif ($loadRoomStatus=='Enabled') {
    echo " enabled";
  }
  echo "' style='grid-column-start:$loadColumnID; grid-row-start:$loadRowID;";
  if ($loadColumnID=='1' OR $loadColumnID=='3') {
    echo " margin-right:calc(var(--spacer)/2);";
    if ($loadColumnID=='3') {
      echo " margin-left:-1px;";
    }
  } elseif ($loadColumnID=='2' OR $loadColumnID=='4') {
    echo " margin-left:calc(var(--spacer)/2);";
  }
  if (in_array($loadRoomID, array('1', '9', '17', '25', '49'), TRUE)) {
    echo " border-bottom-left-radius:6px;";
  }
  if (in_array($loadRoomID, array('1', '9', '40', '48', '49'), TRUE)) {
    echo " border-bottom-right-radius:6px;";
  }
  if (in_array($loadRoomID, array('8', '16', '24', '32', '66'), TRUE)) {
    echo " border-top-left-radius:6px;";
  }
  if (in_array($loadRoomID, array('8', '16', '33', '41', '66'), TRUE)) {
    echo " border-top-right-radius:6px;";
  }
  echo "' title='$loadDescription'>
  <div class='room-number'>$loadRoomID";
  if ($loadCovered=='Yes') {
    echo "<div class='covered-room' title='Covered Room'></div>";
  }
  if ($loadHooks=='Yes') {
    echo "<div class='hooked-bucket' title='Hooked Water Bucket'></div>";
  }
  echo "</div>
  <div class='room-occupant-column'>";
  $checkedIn=array();
  $sql_checkedIn="SELECT dogReservationID FROM dogs_food WHERE status='Active'";
  $result_checkedIn=$conn->query($sql_checkedIn);
  while ($row_checkedIn=$result_checkedIn->fetch_assoc()) {
    $checkedInID=$row_checkedIn['dogReservationID'];
    $checkedIn[]=$checkedInID;
  }
  $sql_reservations="SELECT dogReservationID, lastName, dogName, checkIn, checkOut FROM dogs_reservations WHERE roomID='$loadRoomID' AND checkIn<='$loadEndDate' AND checkOut>='$loadStartDate' ORDER BY checkIn, lastName, dogName";
  $result_reservations=$conn->query($sql_reservations);
  while ($row_reservations=$result_reservations->fetch_assoc()) {
    $reservationID=$row_reservations['dogReservationID'];
    $reservationLastName=htmlspecialchars($row_reservations['lastName'], ENT_QUOTES);
    $reservationDogName=htmlspecialchars($row_reservations['dogName'], ENT_QUOTES);
    $reservationCheckIn=strtotime($row_reservations['checkIn']);
    $reservationCheckOut=strtotime($row_reservations['checkOut']);
    $checkOutDayOfWeek=date('l', strtotime($row_reservations['checkOut']));
    $dateToday=strtotime(date('Y-m-d'));
    echo "<div class='room-occupant' id='room-occupant-$reservationID'>
    <div class='room-name-dates";
    global $doubleBookedReservations;
    if (in_array($reservationID, $doubleBookedReservations)) {
      echo " double-booked-reservation";
    }
    echo "'>
    <div class='room-name'>$reservationDogName $reservationLastName</div>
    <div class='room-dates'>
    <span class='room-check-in'>" . date('D n/j', $reservationCheckIn) . "</span> – <span class='room-check-out";
    if ($checkOutDayOfWeek=='Friday' OR $checkOutDayOfWeek=='Saturday' OR $checkOutDayOfWeek=='Sunday') {
      echo " weekend-check-out";
    }
    echo "'>" . date('D n/j', $reservationCheckOut) . "</span>
    </div>
    </div>
    <div class='room-buttons";
    if ($reservationCheckOut<=$dateToday) {
      echo " checkOutTodayLeft";
    } elseif (in_array($reservationID, $checkedIn)) {
      echo " checkedIn";
    }
    echo "'>
    <a href='/dogs/rooms/" . date('Y-m-d', $reservationCheckIn) . "/" . date('Y-m-d', $reservationCheckOut) . "'>
    <button type='button' class='button-availability' id='check-availability-button' title='Check Availability'></button>
    </a>
    <button type='button' class='button-edit' id='edit-room-button' data-toggle='modal' data-target='#editRoomModal' data-id='$reservationID' data-backdrop='static' title='Edit Reservation'></button>
    <button type='button' class='button-delete' id='delete-room-button' data-toggle='modal' data-target='#deleteRoomModal' data-id='$reservationID' data-backdrop='static' title='Delete Reservation'></button>
    </div>
    </div>";
  }
  echo "</div>
  </div>";
}
if (isset($_POST['startDate']) AND isset($_POST['endDate'])) {
  $startDate=$_POST['startDate'];
  $endDate=$_POST['endDate'];
  $sql_rooms="SELECT roomID, columnID, rowID, hooks, covered, status, description FROM rooms ORDER BY roomID";
  $result_rooms=$conn->query($sql_rooms);
  while ($row_rooms=$result_rooms->fetch_assoc()) {
    $roomID=$row_rooms['roomID'];
    $columnID=$row_rooms['columnID'];
    $rowID=$row_rooms['rowID'];
    $hooks=$row_rooms['hooks'];
    $covered=$row_rooms['covered'];
    $status=$row_rooms['status'];
    $description=htmlspecialchars($row_rooms['description'], ENT_QUOTES);
    loadRoomReservation($roomID, $columnID, $rowID, $hooks, $covered, $status, $startDate, $endDate, $description);
  }
}
?>