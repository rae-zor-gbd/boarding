<?php
include '../assets/config.php';
if (isset($_POST['startDate']) AND isset($_POST['endDate'])) {
  $startDate=$_POST['startDate'];
  $endDate=$_POST['endDate'];
  $sql_containers="SELECT DISTINCT groupID FROM rooms ORDER BY groupID DESC";
  $result_containers=$conn->query($sql_containers);
  while ($row_containers=$result_containers->fetch_assoc()) {
    $containerID=$row_containers['groupID'];
    echo "<div class='rooms-";
    if ($containerID==2) {
      echo "multi";
    } elseif ($containerID==1) {
      echo "single";
    }
    echo "-column'>";
    $columns=array();
    $sql_columns="SELECT columnID FROM rooms GROUP BY columnID HAVING COUNT(DISTINCT(groupID))=$containerID ORDER BY columnID";
    $result_columns=$conn->query($sql_columns);
    while ($row_columns=$result_columns->fetch_assoc()) {
      $columnID=$row_columns['columnID'];
      $columns[]=$columnID;
    }
    $columnList=implode(', ', $columns);
    $sql_groups="SELECT DISTINCT groupID FROM rooms WHERE columnID IN ($columnList) ORDER BY groupID";
    $result_groups=$conn->query($sql_groups);
    while ($row_groups=$result_groups->fetch_assoc()) {
      $groupID=$row_groups['groupID'];
        echo "<div class='rooms-multi-row'>";
      $sql_rooms="SELECT roomID, groupID FROM rooms WHERE columnID IN ($columnList) AND groupID='$groupID' ORDER BY rowID, columnID";
      $result_rooms=$conn->query($sql_rooms);
      while ($row_rooms=$result_rooms->fetch_assoc()) {
        $roomID=$row_rooms['roomID'];
        echo "<div class='room-row'>
        <div class='room-number'>$roomID</div>
        <div class='room-occupant-column'>";
        $sql_reservations="SELECT dogReservationID, dogName, checkIn, checkOut FROM dogs_reservations WHERE roomID='$roomID' AND checkIn<='$endDate' AND checkOut>='$startDate' ORDER BY checkIn, dogName";
        $result_reservations=$conn->query($sql_reservations);
        while ($row_reservations=$result_reservations->fetch_assoc()) {
          $reservationID=$row_reservations['dogReservationID'];
          $reservationName=htmlspecialchars($row_reservations['dogName'], ENT_QUOTES);
          $reservationCheckIn=strtotime($row_reservations['checkIn']);
          $reservationCheckOut=strtotime($row_reservations['checkOut']);
          $checkOutDayOfWeek=date('l', strtotime($row_reservations['checkOut']));
          echo "<div class='room-occupant' id='room-occupant-$reservationID'>
          <div class='room-name-dates'>
          <div class='room-name'>$reservationName</div>
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
        echo"</div>
        </div>";
      }
        echo "</div>";
    } 
    echo "</div>";
  }
}
?>
