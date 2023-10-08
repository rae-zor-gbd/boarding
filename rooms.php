<?php
include 'assets/config.php';
if (isset($_GET['startDate']) AND $_GET['startDate']!='' AND isset($_GET['endDate']) AND $_GET['endDate']!='') {
  $startDate=date('Y-m-d', strtotime($_GET['startDate']));
  $endDate=date('Y-m-d', strtotime($_GET['endDate']));
} else {
  $startDate=date('Y-m-d');
  $endDate=date('Y-m-d', strtotime($startDate. ' + 30 days'));
}
$titleStartDate=date('D n/j', strtotime($startDate));
$titleEndDate=date('D n/j', strtotime($endDate));
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
      function loadCounts(startDate, endDate){
        $.ajax({
          url:'/ajax/load-dog-counts.php',
          type:'POST',
          cache:false,
          data:{startDate:startDate, endDate:endDate},
          success:function(data){
            if (data) {
              $('#navCounts').empty();
              $('#navCounts').append(data);
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
      function toggleDates(){
        var startDate=document.getElementById('startDate').value;
        var endDate=document.getElementById('endDate').value;
        window.open('/dogs/rooms/'+startDate+'/'+endDate, '_self');
      }
      $(document).ready(function() {
        $('#rooms').addClass('active');
        loadRooms('<?php echo "$startDate" ?>', '<?php echo "$endDate" ?>');
        loadCounts('<?php echo "$startDate" ?>', '<?php echo "$endDate" ?>');
        $('#bookRoomButton').click(function (e) {
          loadBookRoomForm();
        });
        $('#bookRoom').click(function (e) {
          e.preventDefault();
          var room=document.getElementById('newRoom').value;
          var name=document.getElementById('newDogName').value.toUpperCase();
          var checkIn=document.getElementById('newCheckIn').value;
          var checkOut=document.getElementById('newCheckOut').value;
          if (room!='' && name!='' && checkIn!='' && checkOut!='') {
            $.ajax({
              url:'/ajax/book-room.php',
              type:'POST',
              cache:false,
              data:{room:room, name:name, checkIn:checkIn, checkOut:checkOut},
              success:function(response){
                loadRooms('<?php echo "$startDate" ?>', '<?php echo "$endDate" ?>');
                loadCounts('<?php echo "$startDate" ?>', '<?php echo "$endDate" ?>');
                $('#bookRoomModal').modal('hide');
                document.getElementById('bookRoomForm').reset();
              }
            });
          } else {
            loadIncompleteFormAlert('#bookRoomModalBody');
          }
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
              loadCounts('<?php echo "$startDate" ?>', '<?php echo "$endDate" ?>');
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
          var dogName=document.getElementById('editDogName').value.toUpperCase();
          var checkIn=document.getElementById('editCheckIn').value;
          var checkOut=document.getElementById('editCheckOut').value;
          if (id!='' && room!='' && dogName!='' && checkIn!='' && checkOut!='') {
            $.ajax({
              url:'/ajax/edit-room.php',
              type:'POST',
              cache:false,
              data:{id:id, room:room, dogName:dogName, checkIn:checkIn, checkOut:checkOut},
              success:function(response){
                $('#editRoomModal').modal('hide');
                $('#editRoomModalBody').empty();
                loadRooms('<?php echo "$startDate" ?>', '<?php echo "$endDate" ?>');
                loadCounts('<?php echo "$startDate" ?>', '<?php echo "$endDate" ?>');
              }
            });
          } else {
            loadIncompleteFormAlert('#editRoomModalBody');
          }
        });
        $('.modal').on('hidden.bs.modal', function(){
          $('#bookRoomModalBody').empty();
          $('#deleteRoomModalBody').empty();
          $('#editRoomModalBody').empty();
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
    <div class='nav-footer'>
      <div id='navCounts'></div>
      <form action='' method='post' spellcheck='false' autocomplete='off' id='toggleDatesForm' onchange='toggleDates()'>
        <div class='input-group'>
          <span class='input-group-addon clock'>Start Date</span>
          <input type='date' class='form-control' name='start-date' id='startDate' value='<?php echo $startDate; ?>' required>
        </div>
        <div class='input-group'>
          <span class='input-group-addon clock'>End Date</span>
          <input type='date' class='form-control' name='end-date' id='endDate' value='<?php echo $endDate; ?>' required>
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