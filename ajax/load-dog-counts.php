<?php
include '../assets/config.php';
if (isset($_POST['startDate']) AND isset($_POST['endDate'])) {
  $startDate=$_POST['startDate'];
  $endDate=$_POST['endDate'];
  $sql_room_count="SELECT COUNT(roomID) AS availableRooms FROM rooms WHERE status!='Disabled' AND roomID NOT IN (SELECT roomID FROM dogs_reservations WHERE checkIn<='$endDate' AND checkOut>='$startDate')";
  $result_room_count=$conn->query($sql_room_count);
  $row_room_count=$result_room_count->fetch_assoc();
  $roomCount=$row_room_count['availableRooms'];
  $sql_weekend_count="SELECT COUNT(dogReservationID) AS weekendCheckOuts FROM dogs_reservations WHERE checkIn<='$endDate' AND checkOut>='$startDate' AND checkOut<='$endDate' AND WEEKDAY(checkOut) IN (4, 5, 6)";
  $result_weekend_count=$conn->query($sql_weekend_count);
  $row_weekend_count=$result_weekend_count->fetch_assoc();
  $weekendCount=$row_weekend_count['weekendCheckOuts'];
  echo "<div class='nav-room-count'><span class='nav-count'>$roomCount</span> room";
  if ($roomCount!=1) {
    echo "s";
  }
  echo " available</div>
  <div class='nav-weekend-count'><span class='nav-count'>$weekendCount</span> weekend check-out";
  if ($weekendCount!=1) {
    echo "s";
  }
  echo "</div>";
}
?>
