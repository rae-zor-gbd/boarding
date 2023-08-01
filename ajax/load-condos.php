<?php
include '../assets/config.php';
if (isset($_POST['startDate']) AND isset($_POST['endDate'])) {
  $startDate=$_POST['startDate'];
  $endDate=$_POST['endDate'];
  $sql_condos="SELECT condoID FROM condos ORDER BY condoID";
  $result_condos=$conn->query($sql_condos);
  while ($row_condos=$result_condos->fetch_assoc()) {
    $condoID=$row_condos['condoID'];
    echo "<div class='condo-row'>
    <div class='condo-number'>$condoID</div>
    <div class='condo-occupant-column'>";
    $sql_reservations="SELECT catReservationID, catName, checkIn, checkOut FROM cats_reservations WHERE condoID='$condoID' AND checkIn<='$endDate' AND checkOut>='$startDate' ORDER BY checkIn, catName";
    $result_reservations=$conn->query($sql_reservations);
    while ($row_reservations=$result_reservations->fetch_assoc()) {
      $reservationID=$row_reservations['catReservationID'];
      $reservationName=htmlspecialchars($row_reservations['catName'], ENT_QUOTES);
      $reservationCheckIn=strtotime($row_reservations['checkIn']);
      $reservationCheckOut=strtotime($row_reservations['checkOut']);
      echo "<div class='condo-occupant' id='condo-occupant-$reservationID'>
      <div class='condo-name-dates'>
      <div class='condo-name'>$reservationName</div>
      <div class='condo-dates'>" . date('D n/j', $reservationCheckIn) . " – " . date('D n/j', $reservationCheckOut) . "</div>
      </div>
      <div class='condo-buttons'>
      <button type='button' class='button-edit' id='edit-condo-button' data-toggle='modal' data-target='#editCondoModal' data-id='$reservationID' data-backdrop='static' title='Edit Reservation'></button>
      <button type='button' class='button-delete' id='delete-condo-button' data-toggle='modal' data-target='#deleteCondoModal' data-id='$reservationID' data-backdrop='static' title='Delete Reservation'></button>
      </div>
      </div>";
    }
    echo"</div>
    </div>";
  }
}
?>
