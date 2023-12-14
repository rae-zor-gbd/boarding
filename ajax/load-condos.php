<?php
include '../assets/config.php';
if (isset($_POST['startDate']) AND isset($_POST['endDate'])) {
  $startDate=$_POST['startDate'];
  $endDate=$_POST['endDate'];
  $sql_containers="SELECT DISTINCT groupID FROM condos ORDER BY groupID";
  $result_containers=$conn->query($sql_containers);
  while ($row_containers=$result_containers->fetch_assoc()) {
    $containerID=$row_containers['groupID'];
    echo "<div class='condo-container'>";
    $sql_condos="SELECT condoID, status FROM condos WHERE groupID='$containerID' ORDER BY rowID, columnID";
    $result_condos=$conn->query($sql_condos);
    while ($row_condos=$result_condos->fetch_assoc()) {
      $condoID=$row_condos['condoID'];
      $status=strtolower($row_condos['status']);
      echo "<div class='condo-row $status'>
      <div class='condo-number'>$condoID</div>
      <div class='condo-occupant-column'>";
      $checkedIn=array();
      $sql_checkedIn="SELECT catReservationID FROM cats_food WHERE status='Active'";
      $result_checkedIn=$conn->query($sql_checkedIn);
      while ($row_checkedIn=$result_checkedIn->fetch_assoc()) {
        $checkedInID=$row_checkedIn['catReservationID'];
        $checkedIn[]=$checkedInID;
      }
      $sql_reservations="SELECT catReservationID, catName, checkIn, checkOut FROM cats_reservations WHERE condoID='$condoID' AND checkIn<='$endDate' AND checkOut>='$startDate' ORDER BY checkIn, catName";
      $result_reservations=$conn->query($sql_reservations);
      while ($row_reservations=$result_reservations->fetch_assoc()) {
        $reservationID=$row_reservations['catReservationID'];
        $reservationName=htmlspecialchars($row_reservations['catName'], ENT_QUOTES);
        $reservationCheckIn=strtotime($row_reservations['checkIn']);
        $reservationCheckOut=strtotime($row_reservations['checkOut']);
        $checkOutDayOfWeek=date('l', strtotime($row_reservations['checkOut']));
        $dateToday=strtotime(date('Y-m-d'));
        echo "<div class='condo-occupant' id='condo-occupant-$reservationID'>
        <div class='condo-name-dates'>
        <div class='condo-name";
        if ($reservationCheckOut<=$dateToday) {
          echo " checkOutToday";
        } elseif (in_array($reservationID, $checkedIn)) {
          echo " checkedIn";
        }
        echo "'>$reservationName</div>
        <div class='condo-dates'>
          <span class='condo-check-in'>" . date('D n/j', $reservationCheckIn) . "</span> – <span class='condo-check-out";
          if ($checkOutDayOfWeek=='Friday' OR $checkOutDayOfWeek=='Saturday' OR $checkOutDayOfWeek=='Sunday') {
            echo " weekend-check-out";
          }
          echo "'>" . date('D n/j', $reservationCheckOut) . "</span>
          </div>
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
    echo "</div>";
  }
}
?>
