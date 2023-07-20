<?php
include 'assets/config.php';
?>
<!DOCTYPE html>
<html lang='en'>
<head>
  <title>Rooms</title>
  <?php include 'assets/header.php'; ?>
  <style>
  .grid-column {
    display:flex;
    flex-direction:column;
    justify-content:space-between;
  }
  .grid-container {
    display:grid;
    grid-template-columns:auto auto auto auto;
    gap:10px;
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
    <div class='grid-container'>
      <?php
      $sql_columns="SELECT DISTINCT columnID FROM rooms ORDER BY columnID";
      $result_columns=$conn->query($sql_columns);
      while ($row_columns=$result_columns->fetch_assoc()) {
        $columnID=$row_columns['columnID'];
        echo "<div class='grid-column'>";
        $sql_groups="SELECT DISTINCT groupID FROM rooms WHERE columnID='$columnID' ORDER BY groupID";
        $result_groups=$conn->query($sql_groups);
        while ($row_groups=$result_groups->fetch_assoc()) {
          $groupID=$row_groups['groupID'];
          echo "<ul class='list-group'>";
          $sql_rows="SELECT roomID FROM rooms WHERE columnID='$columnID' AND groupID='$groupID' ORDER BY rowID";
          $result_rows=$conn->query($sql_rows);
          while ($row_rows=$result_rows->fetch_assoc()) {
            $roomID=$row_rows['roomID'];
            echo "<li class='list-group-item'><strong>Room $roomID</strong><br>Name</li>";
          }
          echo "</ul>";
        }
        echo "</div>";
      }
      ?>
    </div>
  </div>
</body>
</html>
