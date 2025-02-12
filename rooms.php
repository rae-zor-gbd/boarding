<?php
include 'assets/config.php';
if (isset($_GET['startDate']) AND $_GET['startDate']!='' AND isset($_GET['endDate']) AND $_GET['endDate']!='') {
  $startDate=date('Y-m-d', strtotime($_GET['startDate']));
  $endDate=date('Y-m-d', strtotime($_GET['endDate']));
} else {
  $startDate=date('Y-m-d');
  $endDate=date('Y-m-d', strtotime($startDate. ' + 14 days'));
}
$minStartDate=date('Y-m-d', strtotime(date('Y-m-d'). ' - 1 day'));
$titleStartDate=date('D n/j', strtotime($startDate));
$titleEndDate=date('D n/j', strtotime($endDate));
$today=date('Y-m-d');
$sql_max_end_date="SELECT MAX(checkOut) AS endDate FROM dogs_reservations";
$result_max_end_date=$conn->query($sql_max_end_date);
$row_max_end_date=$result_max_end_date->fetch_assoc();
$maxEndDate=$row_max_end_date['endDate'];
?>
<!DOCTYPE html>
<html lang='en'>
  <head>
    <title>Rooms | <?php echo "$titleStartDate – $titleEndDate"; ?></title>
    <?php include 'assets/header.php'; ?>
    <script type='text/javascript'>
      function loadBookRoomForm(){
        $.ajax({
          url:'/ajax/load-book-room-form.php',
          type:'POST',
          cache:false,
          data:{},
          success:function(data){
            if (data) {
              $('#bookRoomModalBody').append(data);
            }
          }
        });
      }
      function loadRooms(startDate, endDate){
        $.ajax({
          url:'/ajax/load-rooms.php',
          type:'POST',
          cache:false,
          data:{startDate:startDate, endDate:endDate},
          success:function(data){
            if (data) {
              $('#rooms-container').empty();
              $('#rooms-container').append(data);
            }
          }
        });
      }
      function loadStats(){
        $.ajax({
          url:'/ajax/load-dog-stats.php',
          type:'POST',
          cache:false,
          data:{},
          success:function(data){
            if (data) {
              $('#statsModalBody').append(data);
            }
          }
        });
      }
      function toggleDates(){
        var startDate=document.getElementById('startDate').value;
        var endDate=document.getElementById('endDate').value;
        window.open('/dogs/rooms/'+startDate+'/'+endDate, '_self');
      }
      $(document).ready(function() {
        $('#rooms').addClass('active');
        loadRooms('<?php echo "$startDate" ?>', '<?php echo "$endDate" ?>');
        $('#bookRoomButton').click(function (e) {
          loadBookRoomForm();
        });
        $('#bookRoom').click(function (e) {
          e.preventDefault();
          var room=document.getElementById('newRoom').value;
          var lastName=document.getElementById('newLastName').value.toUpperCase();
          var dogName=document.getElementById('newDogName').value.toUpperCase();
          var checkIn=document.getElementById('newCheckIn').value;
          var checkOut=document.getElementById('newCheckOut').value;
          if (room!='' && lastName!='' && dogName!='' && checkIn!='' && checkOut!='') {
            var validateCheckIn=Date.parse(document.getElementById('newCheckIn').value);
            var validateCheckOut=Date.parse(document.getElementById('newCheckOut').value);
            if (validateCheckOut>=validateCheckIn) {
              $.ajax({
                url:'/ajax/book-room.php',
                type:'POST',
                cache:false,
                data:{room:room, lastName:lastName, dogName:dogName, checkIn:checkIn, checkOut:checkOut},
                success:function(response){
                  loadRooms('<?php echo "$startDate" ?>', '<?php echo "$endDate" ?>');
                  $('#bookRoomModal').modal('hide');
                  document.getElementById('bookRoomForm').reset();
                }
              });
            } else {
              loadInvalidReservationDatesAlert('#bookRoomModalBody');
            }
          } else {
            loadIncompleteFormAlert('#bookRoomModalBody');
          }
        });
        $('#statsButton').click(function (e) {
          loadStats();
        });
        $(document).on('click', '#delete-room-button', function() {
          var id=$(this).data('id');
          $.ajax({
            url:'/ajax/load-delete-room-form.php',
            type:'POST',
            cache:false,
            data:{id:id},
            success:function(response){
              $('#deleteRoomModalBody').append(response);
            }
          });
        });
        $('#deleteRoom').click(function (e) {
          e.preventDefault();
          var id=document.getElementById('deleteID').value;
          $.ajax({
            url:'/ajax/delete-room.php',
            type:'POST',
            cache:false,
            data:{id:id},
            success:function(response){
              $('#room-occupant-'+id).remove();
              $('#deleteRoomModal').modal('hide');
              $('#deleteRoomModalBody').empty();
            }
          });
        });
        $(document).on('click', '#edit-room-button', function() {
          var id=$(this).data('id');
          $.ajax({
            url:'/ajax/load-edit-room-form.php',
            type:'POST',
            cache:false,
            data:{id:id},
            success:function(response){
              $('#editRoomModalBody').append(response);
            }
          });
        });
        $('#editRoom').click(function (e) {
          e.preventDefault();
          var id=document.getElementById('editID').value;
          var room=document.getElementById('editRoom').value;
          var lastName=document.getElementById('editLastName').value.toUpperCase();
          var dogName=document.getElementById('editDogName').value.toUpperCase();
          var checkIn=document.getElementById('editCheckIn').value;
          var checkOut=document.getElementById('editCheckOut').value;
          if (id!='' && lastName!='' && room!='' && dogName!='' && checkIn!='' && checkOut!='') {
            var validateCheckIn=Date.parse(document.getElementById('editCheckIn').value);
            var validateCheckOut=Date.parse(document.getElementById('editCheckOut').value);
            if (validateCheckOut>=validateCheckIn) {
              $.ajax({
                url:'/ajax/edit-room.php',
                type:'POST',
                cache:false,
                data:{id:id, room:room, lastName:lastName, dogName:dogName, checkIn:checkIn, checkOut:checkOut},
                success:function(response){
                  $('#editRoomModal').modal('hide');
                  $('#editRoomModalBody').empty();
                  loadRooms('<?php echo "$startDate" ?>', '<?php echo "$endDate" ?>');
                }
              });
            } else {
              loadInvalidReservationDatesAlert('#editRoomModalBody');
            }
          } else {
            loadIncompleteFormAlert('#editRoomModalBody');
          }
        });
        $('.modal').on('hidden.bs.modal', function(){
          $('#bookRoomModalBody').empty();
          $('#deleteRoomModalBody').empty();
          $('#editRoomModalBody').empty();
          $('#statsModalBody').empty();
        });
      });
    </script>
  </head>
  <body>
    <?php include 'assets/navbar.php'; ?>
    <form action='' method='post' spellcheck='false' autocomplete='off' id='bookRoomForm'>
      <div class='modal fade' id='bookRoomModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'></button>
              <h4 class='modal-title'>Book Room</h4>
            </div>
            <div class='modal-body' id='bookRoomModalBody'></div>
            <div class='modal-footer'>
              <button type='submit' class='btn btn-primary' id='bookRoom'>Submit</button>
              <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </form>
    <div class='modal fade' id='statsModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
      <div class='modal-dialog'>
        <div class='modal-content'>
          <div class='modal-header'>
            <button type='button' class='close' data-dismiss='modal'></button>
            <h4 class='modal-title'>Daily Statistics</h4>
          </div>
          <div class='modal-body' id='statsModalBody'></div>
        </div>
      </div>
    </div>
    <div class='nav-footer'>
      <button type='button' class='btn btn-default nav-button' id='statsButton' data-toggle='modal' data-target='#statsModal' data-backdrop='static' title='Daily Statistics'>Daily Statistics</button>
      <a href='/dogs/rooms/<?php echo $today; ?>/<?php echo $maxEndDate; ?>'>
        <button type='button' class='btn btn-default nav-button' id='showAllButton'>All Reservations</button>
      </a>
      <form action='' method='post' spellcheck='false' autocomplete='off' id='toggleDatesForm' onchange='toggleDates()'>
        <div class='input-group'>
          <span class='input-group-addon clock'>Start Date</span>
          <input type='date' class='form-control' name='start-date' id='startDate' value='<?php echo $startDate; ?>' min='<?php echo $minStartDate; ?>' required>
        </div>
        <div class='input-group'>
          <span class='input-group-addon clock'>End Date</span>
          <input type='date' class='form-control' name='end-date' id='endDate' value='<?php echo $endDate; ?>' min='<?php echo $startDate; ?>' required>
        </div>
      </form>
      <button type='button' class='btn btn-default nav-button' id='bookRoomButton' data-toggle='modal' data-target='#bookRoomModal' data-backdrop='static' title='Book Room'>Book Room</button>
    </div>
    <div class='container-fluid'>
      <div class='rooms-container' id='rooms-container'></div>
    </div>
    <form action='' method='post' spellcheck='false' autocomplete='off' id='editRoomForm'>
      <div class='modal fade' id='editRoomModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'></button>
              <h4 class='modal-title'>Edit Reservation</h4>
            </div>
            <div class='modal-body' id='editRoomModalBody'></div>
            <div class='modal-footer'>
              <button type='submit' class='btn btn-primary' id='editRoom'>Submit</button>
              <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </form>
    <form action='' method='post' spellcheck='false' autocomplete='off' id='deleteRoomForm'>
      <div class='modal fade' id='deleteRoomModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'></button>
              <h4 class='modal-title'>Delete Reservation</h4>
            </div>
            <div class='modal-body' id='deleteRoomModalBody'></div>
            <div class='modal-footer'>
              <button type='submit' class='btn btn-danger' id='deleteRoom'>Delete</button>
              <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </body>
</html>