<?php
include '../assets/config.php';
if (isset($_POST['startDate']) AND isset($_POST['endDate'])) {
  $startDate=$_POST['startDate'];
  $endDate=$_POST['endDate'];
  $sql_room_count="SELECT COUNT(roomID) AS availableRooms FROM rooms WHERE status!='Disabled' AND roomID NOT IN (SELECT roomID FROM dogs_reservations WHERE checkIn<='$endDate' AND checkOut>='$startDate')";
  $result_room_count=$conn->query($sql_room_count);
  $row_room_count=$result_room_count->fetch_assoc();
  $roomCount=$row_room_count['availableRooms'];
  echo "<div class='nav-room-count'><span class='nav-count'>$roomCount</span> room";
  if ($roomCount!=1) {
    echo "s";
  }
  echo " available</div>";
}
?>
