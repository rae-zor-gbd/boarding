<?php
include 'assets/config.php';
?>
<!DOCTYPE html>
<html lang='en'>
<head>
  <title>Rooms</title>
  <?php include 'assets/header.php'; ?>
  <style>
  .panel {
    display:flex;
    flex-direction:column;
    width:calc((100%/4) - 12px);
  }
  .panel:not(:nth-of-type(4n-3)) {
    margin-left:calc(var(--spacer)/2);
  }
  .panel-body {
    flex:1 1 auto;
    text-align:right;
  }
  .panel-container {
    display:flex;
    flex-wrap:wrap;
    justify-content:left;
  }
  .room-dates {
    font-size:70%;
    margin-left:calc(var(--spacer)/4);
    text-transform:uppercase;
  }
  .room-number {
    float:left;
    font-weight:var(--font-weight-bold);
  }
  .room-occupant:not(:nth-of-type(2)) {
    margin-top:10px;
  }
  </style>
  <script type='text/javascript'>
  $(document).ready(function(){
    $('#rooms').addClass('active');
  });
  </script>
</head>
<body>
  <?php include 'assets/navbar.php'; ?>
  <div class='container-fluid'>
    <div class='panel-container'>
      <?php
      $sql_columns="SELECT DISTINCT columnID FROM rooms ORDER BY columnID";
      $result_columns=$conn->query($sql_columns);
      while ($row_columns=$result_columns->fetch_assoc()) {
        $columnID=$row_columns['columnID'];
        echo "<div class='panel panel-default'>";
        $sql_groups="SELECT DISTINCT groupID FROM rooms WHERE columnID='$columnID' ORDER BY groupID";
        $result_groups=$conn->query($sql_groups);
        while ($row_groups=$result_groups->fetch_assoc()) {
          $groupID=$row_groups['groupID'];
          /*echo "<ul class='list-group'>";*/
          $sql_rows="SELECT roomID FROM rooms WHERE columnID='$columnID' AND groupID='$groupID' ORDER BY rowID";
          $result_rows=$conn->query($sql_rows);
          while ($row_rows=$result_rows->fetch_assoc()) {
            $roomID=$row_rows['roomID'];
            echo "<div class='panel-body'>
            <div class='room-number'>$roomID</div>
            <div class='room-occupant'>
            <div class='room-name'>Indiana Jones (Indy)</div>
            <div class='room-dates'>Wed 10/22 – Wed 10/23</div>
            </div>
            <div class='room-occupant'>
            <div class='room-name'>Isabella Rose Gustafson</div>
            <div class='room-dates'>Wed 10/22 – Wed 10/23</div>
            </div>
            </div>";
          }
          /*echo "</ul>";*/
        }
        echo "</div>";
      }
      ?>
    </div>
  </div>
</body>
</html>
