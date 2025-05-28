<?php
include 'assets/config.php';
$id=$_GET['dog'];
$sql_long_term_info="SELECT roomID, lastName, dogName, checkIn, checkOut FROM dogs_reservations WHERE dogReservationID='$id'";
$result_long_term_info=$conn->query($sql_long_term_info);
$row_long_term_info=$result_long_term_info->fetch_assoc();
$room=$row_long_term_info['roomID'];
$lastName=htmlspecialchars($row_long_term_info['lastName'], ENT_QUOTES);
$dogName=htmlspecialchars($row_long_term_info['dogName'], ENT_QUOTES);
$checkIn=strtotime($row_long_term_info['checkIn']);
$checkOut=strtotime($row_long_term_info['checkOut']);
?>
<!DOCTYPE html>
<html lang='en'>
  <head>
    <title>Long-Term Cleaning Sheet</title>
    <?php include 'assets/header.php'; ?>
    <style>
    @page {
      margin-bottom:0.5in;
      margin-left:0.5in;
      margin-right:0.5in;
      margin-top:0.5in;
      size:portrait;
    }
    </style>
    <script>
      $(document).ready(function(){
        window.print();
        window.onafterprint=function() {
          window.close();
        }
      });
    </script>
  </head>
  <body>
    <?php
    echo "<div class='long-term-header'>
    <h2 class='long-term-header-name'>$dogName <span class='normal'>$lastName</span></h2>
    <h2 class='long-term-header-room'><span class='normal'></span>$room</h2>
    </div>
    <div class='row row-no-gutters long-term-row'>
    <div class='col-xs-2'>
    <div class='long-term-column-header'>
    <h4>Week</h4>
    </div>
    </div>
    <div class='col-xs-4'>
    <div class='long-term-column-header'>
    <h4>Date Range</h4>
    </div>
    </div>
    <div class='col-xs-4'>
    <div class='long-term-column-header'>
    <h4>Cleaned On</h4>
    </div>
    </div>
    <div class='col-xs-2'>
    <div class='long-term-column-header'>
    <h4>Initials</h4>
    </div>
    </div>
    </div>";
    $nights=($checkOut-$checkIn)/86400;
    $weeks=floor($nights/4);
    $remainder=$nights%4;
    if ($remainder>1 AND $remainder<4) {
      $weeks++;
    }
    $extraWeeks=18-$weeks;
    for ($week=1; $week<=$weeks; $week++) {
      if ($week>1) {
        $rangeStart=$rangeEnd+86400;
      } else {
        $rangeStart=$checkIn;
      }
      if ($week!=$weeks) {
        if ($week>1) {
          $rangeEnd=$rangeStart+(3*86400);
        } else {
          $rangeEnd=$rangeStart+(4*86400);
        }
      } else {
        $rangeEnd=$checkOut;
      }
      echo "<div class='row row-no-gutters long-term-row'>
      <div class='col-xs-2'>
      <div class='long-term-box'>Week $week</div>
      </div>
      <div class='col-xs-4'>
      <div class='long-term-box'>" . date('D n/j', $rangeStart) . " – " . date('D n/j', $rangeEnd) . "</div>
      </div>
      <div class='col-xs-4'>
      <div class='long-term-box'></div>
      </div>
      <div class='col-xs-2'>
      <div class='long-term-box'></div>
      </div>
      </div>";
    }
    for ($extraWeek=$weeks+1; $extraWeek<=18; $extraWeek++) {
      echo "<div class='row row-no-gutters long-term-row'>
      <div class='col-xs-2'>
      <div class='long-term-box'>Week $extraWeek</div>
      </div>
      <div class='col-xs-4'>
      <div class='long-term-box'></div>
      </div>
      <div class='col-xs-4'>
      <div class='long-term-box'></div>
      </div>
      <div class='col-xs-2'>
      <div class='long-term-box'></div>
      </div>
      </div>";
    }
    ?>
  </body>
</html>