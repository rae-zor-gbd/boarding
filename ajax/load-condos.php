<?php
include '../assets/config.php';
if (isset($_POST['startDate']) AND isset($_POST['endDate'])) {
  $doubleBookedReservations=array();
  $sql_doubleBookedReservations="SELECT DISTINCT catReservationID FROM (SELECT a.catReservationID FROM (SELECT catReservationID, condoID, lastName, catName, checkIn, checkOut FROM cats_reservations WHERE checkOut>=DATE(NOW()) AND condoID IN (SELECT condoID FROM (SELECT condoID, COUNT(catReservationID) FROM cats_reservations WHERE checkOut>=DATE(NOW()) GROUP BY condoID HAVING COUNT(catReservationID)>1) r)) a JOIN (SELECT catReservationID, condoID, lastName, catName, checkIn, checkOut FROM cats_reservations WHERE checkOut>=DATE(NOW()) AND condoID IN (SELECT condoID FROM (SELECT condoID, COUNT(catReservationID) FROM cats_reservations WHERE checkOut>=DATE(NOW()) GROUP BY condoID HAVING COUNT(catReservationID)>1) r)) b USING (condoID) WHERE a.catReservationID<>b.catReservationID AND a.lastName<>b.lastName AND a.checkIn<=b.checkOut AND a.checkOut>=b.checkIn GROUP BY a.condoID, a.catReservationID, a.lastName, a.catName, a.checkIn, a.checkOut ORDER BY a.condoID, a.checkIn, a.lastName, a.catName) d";
  $result_doubleBookedReservations=$conn->query($sql_doubleBookedReservations);
  while ($row_doubleBookedReservations=$result_doubleBookedReservations->fetch_assoc()) {
    $doubleBookedReservationsID=$row_doubleBookedReservations['catReservationID'];
    $doubleBookedReservations[]=$doubleBookedReservationsID;
  }
  $startDate=$_POST['startDate'];
  $endDate=$_POST['endDate'];
  $sql_containers="SELECT DISTINCT groupID FROM condos ORDER BY groupID";
  $result_containers=$conn->query($sql_containers);
  while ($row_containers=$result_containers->fetch_assoc()) {
    $containerID=$row_containers['groupID'];
    echo "<div class='condo-container'>";
    $sql_condos="SELECT condoID, status, description FROM condos WHERE groupID='$containerID' ORDER BY rowID, columnID";
    $result_condos=$conn->query($sql_condos);
    while ($row_condos=$result_condos->fetch_assoc()) {
      $condoID=$row_condos['condoID'];
      $status=strtolower($row_condos['status']);
      $description=htmlspecialchars($row_condos['description'], ENT_QUOTES);
      echo "<div class='condo-row $status' title='$description'>
      <div class='condo-number'>$condoID</div>
      <div class='condo-occupant-column'>";
      $checkedIn=array();
      $sql_checkedIn="SELECT catReservationID FROM cats_food WHERE status='Active'";
      $result_checkedIn=$conn->query($sql_checkedIn);
      while ($row_checkedIn=$result_checkedIn->fetch_assoc()) {
        $checkedInID=$row_checkedIn['catReservationID'];
        $checkedIn[]=$checkedInID;
      }
      $sql_reservations="SELECT catReservationID, lastName, catName, checkIn, checkOut FROM cats_reservations WHERE condoID='$condoID' AND checkIn<='$endDate' AND checkOut>='$startDate' ORDER BY checkIn, lastName, catName";
      $result_reservations=$conn->query($sql_reservations);
      while ($row_reservations=$result_reservations->fetch_assoc()) {
        $reservationID=$row_reservations['catReservationID'];
        $reservationLastName=htmlspecialchars($row_reservations['lastName'], ENT_QUOTES);
        $reservationCatName=htmlspecialchars($row_reservations['catName'], ENT_QUOTES);
        $reservationCheckIn=strtotime($row_reservations['checkIn']);
        $reservationCheckOut=strtotime($row_reservations['checkOut']);
        $checkOutDayOfWeek=date('l', strtotime($row_reservations['checkOut']));
        $dateToday=strtotime(date('Y-m-d'));
        echo "<div class='condo-occupant' id='condo-occupant-$reservationID'>
        <div class='condo-name-dates";
        if (in_array($reservationID, $doubleBookedReservations)) {
          echo " double-booked-reservation";
        }
        echo "'>
        <div class='condo-name";
        if ($reservationCheckOut<=$dateToday) {
          echo " checkOutToday";
        } elseif (in_array($reservationID, $checkedIn)) {
          echo " checkedIn";
        }
        echo "'>$reservationCatName $reservationLastName</div>
        <div class='condo-dates'>
          <span class='condo-check-in'>" . date('D n/j', $reservationCheckIn) . "</span> – <span class='condo-check-out";
          if ($checkOutDayOfWeek=='Friday' OR $checkOutDayOfWeek=='Saturday' OR $checkOutDayOfWeek=='Sunday') {
            echo " weekend-check-out";
          }
          echo "'>" . date('D n/j', $reservationCheckOut) . "</span>
          </div>
        </div>
        <div class='condo-buttons'>
        <a href='/cats/condos/" . date('Y-m-d', $reservationCheckIn) . "/" . date('Y-m-d', $reservationCheckOut) . "'>
        <button type='button' class='button-availability' id='check-availability-button' title='Check Availability'></button>
        </a>
        <button type='button' class='button-edit' id='edit-condo-button' data-toggle='modal' data-target='#editCondoModal' data-id='$reservationID' data-backdrop='static' title='Edit Reservation'></button>
        <button type='button' class='button-delete' id='delete-condo-button' data-toggle='modal' data-target='#deleteCondoModal' data-id='$reservationID' data-backdrop='static' title='Delete Reservation'></button>
        </div>
        </div>";
      }
      echo"</div>
      </div>";
    }
    echo "</div>";
  }
}
?>
