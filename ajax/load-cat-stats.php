<?php
include '../assets/config.php';
echo "<table class='table table-hover table-condensed'>
<thead>
<tr>
<th>Date</th>
<th>Cats<br>Boarding</th>
<th>Condos<br>Occupied</th>
<th>Total<br>Check-Ins</th>
<th>Total<br>Check-Outs</th>
</tr>
</thead>
<tbody>";
$sql_cat_stats="SELECT DATE_FORMAT(reservationDate, \"%a %c/%e\") AS date, (SELECT COUNT(catName) FROM cats_reservations WHERE checkIn<=reservationDate AND checkOut>=reservationDate) AS totalCats, (SELECT COUNT(DISTINCT condoID) FROM cats_reservations WHERE checkIn<=reservationDate AND checkOut>=reservationDate) AS totalCondos, (SELECT COUNT(catName) FROM cats_reservations WHERE checkIn=reservationDate) AS totalCheckIns, (SELECT COUNT(catName) FROM cats_reservations WHERE checkOut=reservationDate) AS totalCheckOuts
FROM (SELECT ADDDATE('1970-01-01',t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) reservationDate FROM (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3, (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4) v
WHERE reservationDate BETWEEN DATE(NOW()) AND (SELECT MAX(checkOut) FROM cats_reservations)
ORDER BY reservationDate ASC;";
$result_cat_stats=$conn->query($sql_cat_stats);
while ($row_cat_stats=$result_cat_stats->fetch_assoc()) {
  $catStatDate=$row_cat_stats['date'];
  $catTotalCats=$row_cat_stats['totalCats'];
  $catTotalCondos=$row_cat_stats['totalCondos'];
  $catTotalCheckIns=$row_cat_stats['totalCheckIns'];
  $catTotalCheckOuts=$row_cat_stats['totalCheckOuts'];
  echo "<tr>
  <td>$catStatDate</td>
  <td>$catTotalCats</td>
  <td>$catTotalCondos</td>
  <td>$catTotalCheckIns</td>
  <td>$catTotalCheckOuts</td>
  </tr>";
}
echo "</tbody>
</table>";
?>
