<?php
include '../assets/config.php';
$doubleBookedReservations=array();
$sql_doubleBookedReservations="SELECT DISTINCT dogReservationID FROM (SELECT a.dogReservationID FROM (SELECT dogReservationID, roomID, dogName, checkIn, checkOut FROM dogs_reservations WHERE checkOut>=DATE(NOW()) AND roomID IN (SELECT roomID FROM (SELECT roomID, COUNT(dogReservationID) FROM dogs_reservations WHERE checkOut>=DATE(NOW()) GROUP BY roomID HAVING COUNT(dogReservationID)>1) r)) a JOIN (SELECT dogReservationID, roomID, dogName, checkIn, checkOut FROM dogs_reservations WHERE checkOut>=DATE(NOW()) AND roomID IN (SELECT roomID FROM (SELECT roomID, COUNT(dogReservationID) FROM dogs_reservations WHERE checkOut>=DATE(NOW()) GROUP BY roomID HAVING COUNT(dogReservationID)>1) r)) b USING (roomID) WHERE a.dogReservationID<>b.dogReservationID AND a.checkIn<=b.checkOut AND a.checkOut>=b.checkIn GROUP BY a.roomID, a.dogReservationID, a.dogName, a.checkIn, a.checkOut ORDER BY a.roomID, a.checkIn, a.dogName) d";
$result_doubleBookedReservations=$conn->query($sql_doubleBookedReservations);
while ($row_doubleBookedReservations=$result_doubleBookedReservations->fetch_assoc()) {
  $doubleBookedReservationsID=$row_doubleBookedReservations['dogReservationID'];
  $doubleBookedReservations[]=$doubleBookedReservationsID;
}
function loadRoomReservation($loadRoomID, $loadColumnID, $loadRowID, $loadRoomStatus, $loadStartDate, $loadEndDate, $loadDescription) {
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
  if ($loadRoomID=='1' OR $loadRoomID=='9' OR $loadRoomID=='17' OR $loadRoomID=='25' OR $loadRoomID=='49') {
    echo " border-bottom-left-radius:6px;";
  }
  if ($loadRoomID=='1' OR $loadRoomID=='9' OR $loadRoomID=='40' OR $loadRoomID=='48' OR $loadRoomID=='49') {
    echo " border-bottom-right-radius:6px;";
  }
  if ($loadRoomID=='8' OR $loadRoomID=='16' OR $loadRoomID=='24' OR $loadRoomID=='32' OR $loadRoomID=='66') {
    echo " border-top-left-radius:6px;";
  }
  if ($loadRoomID=='8' OR $loadRoomID=='16' OR $loadRoomID=='33' OR $loadRoomID=='41' OR $loadRoomID=='66') {
    echo " border-top-right-radius:6px;";
  }
  echo "' title='$loadDescription'>
  <div class='room-number'>$loadRoomID</div>
  <div class='room-occupant-column'>";
  $checkedIn=array();
  $sql_checkedIn="SELECT dogReservationID FROM dogs_food WHERE status='Active'";
  $result_checkedIn=$conn->query($sql_checkedIn);
  while ($row_checkedIn=$result_checkedIn->fetch_assoc()) {
    $checkedInID=$row_checkedIn['dogReservationID'];
    $checkedIn[]=$checkedInID;
  }
  $sql_reservations="SELECT dogReservationID, dogName, checkIn, checkOut FROM dogs_reservations WHERE roomID='$loadRoomID' AND checkIn<='$loadEndDate' AND checkOut>='$loadStartDate' ORDER BY checkIn, dogName";
  $result_reservations=$conn->query($sql_reservations);
  while ($row_reservations=$result_reservations->fetch_assoc()) {
    $reservationID=$row_reservations['dogReservationID'];
    $reservationName=htmlspecialchars($row_reservations['dogName'], ENT_QUOTES);
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
    <div class='room-name";
    if ($reservationCheckOut<=$dateToday) {
      echo " checkOutToday";
    } elseif (in_array($reservationID, $checkedIn)) {
      echo " checkedIn";
    }
    echo "'>$reservationName</div>
    <div class='room-dates'>
    <span class='room-check-in'>" . date('D n/j', $reservationCheckIn) . "</span> – <span class='room-check-out";
    if ($checkOutDayOfWeek=='Friday' OR $checkOutDayOfWeek=='Saturday' OR $checkOutDayOfWeek=='Sunday') {
      echo " weekend-check-out";
    }
    echo "'>" . date('D n/j', $reservationCheckOut) . "</span>
    </div>
    </div>
    <div class='room-buttons'>
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
  $sql_rooms="SELECT roomID, columnID, rowID, status, description FROM rooms ORDER BY roomID";
  $result_rooms=$conn->query($sql_rooms);
  while ($row_rooms=$result_rooms->fetch_assoc()) {
    $roomID=$row_rooms['roomID'];
    $columnID=$row_rooms['columnID'];
    $rowID=$row_rooms['rowID'];
    $status=$row_rooms['status'];
    $description=htmlspecialchars($row_rooms['description'], ENT_QUOTES);
    loadRoomReservation($roomID, $columnID, $rowID, $status, $startDate, $endDate, $description);
  }
}
?>