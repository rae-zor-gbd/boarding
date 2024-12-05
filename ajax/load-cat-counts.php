<?php
include '../assets/config.php';
if (isset($_POST['startDate']) AND isset($_POST['endDate'])) {
  $startDate=$_POST['startDate'];
  $endDate=$_POST['endDate'];
  $sql_condo_count="SELECT COUNT(condoID) AS availableCondos FROM condos WHERE status!='Disabled' AND condoID NOT IN (SELECT condoID FROM cats_reservations WHERE checkIn<='$endDate' AND checkOut>='$startDate')";
  $result_condo_count=$conn->query($sql_condo_count);
  $row_condo_count=$result_condo_count->fetch_assoc();
  $condoCount=$row_condo_count['availableCondos'];
  echo "<div class='nav-condo-count'><span class='nav-count'>$condoCount</span> condo";
  if ($condoCount!=1) {
    echo "s";
  }
  echo " available</div>";
}
?>
