<?php
include 'assets/config.php';
?>
<!DOCTYPE html>
<html lang='en'>
<head>
  <title>Rooms</title>
  <?php include 'assets/header.php'; ?>
  <script type='text/javascript'>
    function loadRooms(){
      $.ajax({
        url:'/assets/load-rooms.php',
        type:'POST',
        cache:false,
        data:{},
        success:function(data){
          if (data) {
            $('#table-rooms').append(data);
          }
        }
      });
    }
    $(document).ready(function(){
      $('#rooms').addClass('active');
      loadRooms();
      $('#bookRoom').click(function (e) {
        e.preventDefault();
        var room=document.getElementById('newRoom').value;
        var name=document.getElementById('newDogName').value;
        var foodType=document.getElementById('newFoodType').value;
        var feedingInstructions=document.getElementById('newFeedingInstructions').value;
        $.ajax({
          url:'assets/book-room.php',
          type:'POST',
          cache:false,
          data:{room:room, name:name, foodType:foodType, feedingInstructions:feedingInstructions},
          success:function(response){
            $('#table-rooms').empty();
            loadRooms();
            $('#bookRoomModal').modal('hide');
            document.getElementById('bookRoomForm').reset();
          }
        });
      });
    });
  </script>
</head>
<body>
  <?php include 'assets/navbar.php'; ?>
  <form action='' method='post' spellcheck='false' id='bookRoomForm'>
    <div class='modal fade' id='bookRoomModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
      <div class='modal-dialog'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h4 class='modal-title'>Book Room</h4>
          </div>
          <div class='modal-body'>
            <div class='input-group'>
              <span class='input-group-addon room'>Room</span>
              <select class='form-control' name='room' id='newRoom' required=''>
                <option value='' selected disabled>Select Room</option>
                <?php
                $sql_all_rooms="SELECT roomID FROM rooms ORDER BY roomID";
                $result_all_rooms=$conn->query($sql_all_rooms);
                while ($row_all_rooms=$result_all_rooms->fetch_assoc()) {
                  $allRoomsID=$row_all_rooms['roomID'];
                  echo "<option value='$allRoomsID'>Room $allRoomsID</option>";
                }
                ?>
              </select>
            </div>
            <div class='input-group'>
              <span class='input-group-addon dog'>Name</span>
              <input type='text' class='form-control' name='dog-name' maxlength='255' id='newDogName' required>
            </div>
            <div class='input-group'>
              <span class='input-group-addon food'>Food Type</span>
              <select class='form-control' name='foodType' id='newFoodType' required=''>
                <option value='' selected disabled>Select Food Type</option>
                <option value='Own'>Own Food</option>
                <option value='Ours'>Our Food</option>
              </select>
            </div>
            <div class='input-group'>
              <span class='input-group-addon notes'>Feeding Instructions</span>
              <textarea class='form-control' name='feeding-instructions' id='newFeedingInstructions' rows='5' required></textarea>
            </div>
          </div>
          <div class='modal-footer'>
            <button type='submit' class='btn btn-primary' id='bookRoom'>Submit</button>
            <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </form>
  <button type='button' class='btn btn-default book-room' data-toggle='modal' data-target='#bookRoomModal' data-backdrop='static'>Book Room</button>
  <div class='container-fluid'>
    <div class='table-container'>
      <table class='table table-hover table-condensed'>
        <thead>
          <tr>
            <th>Room</th>
            <th>Name</th>
            <th>Food Type</th>
            <th>Feeding Instructions</th>
            <th>Medications</th>
            <th></th>
          </tr>
        </thead>
        <tbody id='table-rooms'></tbody>
      </table>
    </div>
  </div>
</body>
</html>
