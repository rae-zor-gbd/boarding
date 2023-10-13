<?php
include '../assets/config.php';
$sql_disabled_rooms="SELECT roomID FROM rooms WHERE status='Disabled'";
$result_disabled_rooms=$conn->query($sql_disabled_rooms);
$disabledRooms=array();
while ($row_disabled_rooms=$result_disabled_rooms->fetch_assoc()) {
  $disabledRoomID=$row_disabled_rooms['roomID'];
  $disabledRooms[]=$disabledRoomID;
}
function loadRoomReservation($getRoomID) {
  echo "<div class='room-row";
  /*if (in_array($getRoomID, $disabledRooms)) {
    echo " disabled";
  } else {
    echo " enabled";
  }*/
  echo "'>
  <div class='room-number'>$getRoomID</div>
  <div class='room-occupant-column'></div>
  </div>";
}
if (isset($_POST['startDate']) AND isset($_POST['endDate'])) {
  $startDate=$_POST['startDate'];
  $endDate=$_POST['endDate'];
  $sql_dividers="SELECT DISTINCT groupID FROM rooms ORDER BY groupID";
  $result_dividers=$conn->query($sql_dividers);
  while ($row_dividers=$result_dividers->fetch_assoc()) {
    $dividerID=$row_dividers['groupID'];
    echo "<div class='rooms-container'>";
    $sql_multi_column="SELECT roomID FROM rooms WHERE groupID='$dividerID' AND columnID IN (1,2,3) ORDER BY rowID, columnID";
    $result_multi_column=$conn->query($sql_multi_column);
    $multiColumn=array();
    while ($row_multi_column=$result_multi_column->fetch_assoc()) {
      $roomID=$row_multi_column['roomID'];
      $multiColumn[]=$roomID;
    }

    echo "<div class='rooms-multi-column'>
    <div class='rooms-multi-row'>";
    foreach ($multiColumn as $multiRoomNo) {
      echo "<div class='room-row";
      if (in_array($multiRoomNo, $disabledRooms)) {
        echo " disabled";
      } else {
        echo " enabled";
      }
      echo "'>
      <div class='room-number'>$multiRoomNo</div>
      <div class='room-occupant-column'></div>
      </div>";
    }
    echo "</div>
    </div>";

    $sql_single_column="SELECT roomID, status FROM rooms WHERE groupID='$dividerID' AND columnID=4 ORDER BY rowID, columnID";
    $result_single_column=$conn->query($sql_single_column);
    $singleColumn=array();
    while ($row_single_column=$result_single_column->fetch_assoc()) {
      $roomID=$row_single_column['roomID'];
      $status=$row_single_column['status'];
      $singleColumn[]=$roomID;
    }
    echo "<div class='rooms-single-column'>
    <div class='rooms-multi-row'>";
    foreach ($singleColumn as $singleRoom) {
      loadRoomReservation($singleRoom);
    }
    echo "</div>
    </div>";


    echo "</div>";
  }
}
?>
