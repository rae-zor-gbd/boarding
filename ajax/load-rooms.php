<?php
include '../assets/config.php';
function loadRoomReservation($loadRoomID, $loadRoomStatus, $loadStartDate, $loadEndDate) {
  include '../assets/config.php';
  echo "<div class='room-row";
  if ($loadRoomStatus=='Disabled') {
    echo " disabled";
  } elseif ($loadRoomStatus=='Enabled') {
    echo " enabled";
  }
  echo "'>
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
    <div class='room-name-dates'>
    <div class='room-name";
    if ($reservationCheckOut==$dateToday) {
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
  $sql_groups="SELECT DISTINCT groupID FROM rooms ORDER BY groupID";
  $result_groups=$conn->query($sql_groups);
  while ($row_groups=$result_groups->fetch_assoc()) {
    $groupID=$row_groups['groupID'];
    echo "<div class='rooms-container'>";
    $sql_multi_column="SELECT roomID, status FROM rooms WHERE groupID='$groupID' AND columnID IN (1,2,3) ORDER BY rowID, columnID";
    $result_multi_column=$conn->query($sql_multi_column);
    $multiColumn=array();
    while ($row_multi_column=$result_multi_column->fetch_assoc()) {
      $roomID=$row_multi_column['roomID'];
      $status=$row_multi_column['status'];
      $multiColumn[]=array('room'=>$roomID, 'status'=>$status);
    }
    echo "<div class='rooms-multi-column'>
    <div class='rooms-multi-row'>";
    foreach($multiColumn as $multiRoom=>$multiStatus) {
      loadRoomReservation($multiColumn[$multiRoom]['room'], $multiColumn[$multiRoom]['status'], $startDate, $endDate);
    }
    echo "</div>
    </div>";
    $sql_single_column="SELECT roomID, status FROM rooms WHERE groupID='$groupID' AND columnID=4 ORDER BY rowID, columnID";
    $result_single_column=$conn->query($sql_single_column);
    $singleColumn=array();
    while ($row_single_column=$result_single_column->fetch_assoc()) {
      $roomID=$row_single_column['roomID'];
      $status=$row_single_column['status'];
      $singleColumn[]=array('room'=>$roomID, 'status'=>$status);
    }
    echo "<div class='rooms-single-column'>
    <div class='rooms-multi-row'>";
    foreach($singleColumn as $singleRoom=>$singleStatus) {
      loadRoomReservation($singleColumn[$singleRoom]['room'], $singleColumn[$singleRoom]['status'], $startDate, $endDate);
    }
    echo "</div>
    </div>";
    echo "</div>";
  }
}
?>