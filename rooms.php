<?php
include 'assets/config.php';
?>
<!DOCTYPE html>
<html lang='en'>
<head>
  <title>Rooms</title>
  <?php include 'assets/header.php'; ?>
  <script type='text/javascript'>
    $(document).ready(function() {
      $('#rooms').addClass('active');
    });
  </script>
</head>
<body>
  <?php include 'assets/navbar.php'; ?>
  <button type='button' class='btn btn-default nav-add-button' title='Book Room'>Book Room</button>
  <div class='container-fluid'>
    <div class='rooms-container'>
      <?php
      $sql_containers="SELECT DISTINCT groupID FROM rooms ORDER BY groupID DESC";
      $result_containers=$conn->query($sql_containers);
      while ($row_containers=$result_containers->fetch_assoc()) {
        $containerID=$row_containers['groupID'];
        echo "<div class='rooms-";
        if ($containerID==2) {
          echo "multi";
        } elseif ($containerID==1) {
          echo "single";
        }
        echo "-column'>";
        $columns=array();
        $sql_columns="SELECT columnID FROM rooms GROUP BY columnID HAVING COUNT(DISTINCT(groupID))=$containerID ORDER BY columnID";
        $result_columns=$conn->query($sql_columns);
        while ($row_columns=$result_columns->fetch_assoc()) {
          $columnID=$row_columns['columnID'];
          $columns[]=$columnID;
        }
        $columnList=implode(', ', $columns);
        $sql_groups="SELECT DISTINCT groupID FROM rooms WHERE columnID IN ($columnList) ORDER BY groupID";
        $result_groups=$conn->query($sql_groups);
        while ($row_groups=$result_groups->fetch_assoc()) {
          $groupID=$row_groups['groupID'];
            echo "<div class='rooms-multi-row'>";
          $sql_rooms="SELECT roomID, groupID FROM rooms WHERE columnID IN ($columnList) AND groupID='$groupID' ORDER BY rowID, columnID";
          $result_rooms=$conn->query($sql_rooms);
          while ($row_rooms=$result_rooms->fetch_assoc()) {
            $roomID=$row_rooms['roomID'];
            echo "<div class='room-row'>
            <div class='room-number'>$roomID</div>
            <div class='room-occupant-column'>
            <div class='room-occupant'>
            <div class='room-name-dates'>
            <div class='room-name'>Isabella Rose & Kozmo</div>
            <div class='room-dates'>Tue 8/22 – Wed 8/23</div>
            </div>
            <div class='room-buttons'>
            <button type='button' class='button-edit' title='Edit'></button>
            <button type='button' class='button-delete' title='Delete'></button>
            </div>
            </div>
            </div>
            </div>";
          }
            echo "</div>";
        } 
        echo "</div>";
      }
      ?>
    </div>
  </div>
</body>
</html>