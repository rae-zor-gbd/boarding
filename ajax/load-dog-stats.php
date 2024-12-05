<?php
include '../assets/config.php';
echo "<table class='table table-hover table-condensed'>
<thead>
<tr>
<th>Date</th>
<th>Dogs<br>Boarding</th>
<th>Rooms<br>Occupied</th>
<th>Total<br>Check-Ins</th>
<th>Total<br>Check-Outs</th>
</tr>
</thead>
<tbody>";
$sql_dog_stats="SELECT DATE_FORMAT(reservationDate, \"%a %c/%e\") AS date, (SELECT COUNT(dogName) FROM dogs_reservations WHERE checkIn<=reservationDate AND checkOut>=reservationDate) AS totalDogs, (SELECT COUNT(DISTINCT roomID) FROM dogs_reservations WHERE checkIn<=reservationDate AND checkOut>=reservationDate) AS totalRooms, (SELECT COUNT(dogName) FROM dogs_reservations WHERE checkIn=reservationDate) AS totalCheckIns, (SELECT COUNT(dogName) FROM dogs_reservations WHERE checkOut=reservationDate) AS totalCheckOuts
FROM (SELECT ADDDATE('1970-01-01',t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) reservationDate FROM (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4) v
WHERE reservationDate BETWEEN DATE(NOW()) AND (SELECT MAX(checkOut) FROM dogs_reservations)
ORDER BY reservationDate ASC;";
$result_dog_stats=$conn->query($sql_dog_stats);
while ($row_dog_stats=$result_dog_stats->fetch_assoc()) {
  $dogStatDate=$row_dog_stats['date'];
  $dogTotalDogs=$row_dog_stats['totalDogs'];
  $dogTotalRooms=$row_dog_stats['totalRooms'];
  $dogTotalCheckIns=$row_dog_stats['totalCheckIns'];
  $dogTotalCheckOuts=$row_dog_stats['totalCheckOuts'];
  echo "<tr>
  <td>$dogStatDate</td>
  <td>$dogTotalDogs</td>
  <td>$dogTotalRooms</td>
  <td>$dogTotalCheckIns</td>
  <td>$dogTotalCheckOuts</td>
  </tr>";
}
echo "</tbody>
</table>";
?>
