<?php
include 'assets/config.php';
?>
<!DOCTYPE html>
<html lang='en'>
<head>
  <title>Food & Meds</title>
  <?php include 'assets/header.php'; ?>
  <script type='text/javascript'>
  function loadBookRoomForm(){
    $.ajax({
      url:'/assets/load-book-room-form.php',
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
    $('#food-meds').addClass('active');
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
    $('#bookRoomFormButton').click(function (e) {
      loadBookRoomForm();
    });
    $(document).on('click', '#delete-dog-button', function() {
      var id=$(this).data('id');
      $.ajax({
        url:'assets/load-delete-dog-form.php',
        type:'POST',
        cache:false,
        data:{id:id},
        success:function(response){
          $('#deleteDogModalBody').append(response);
        }
      });
    });
    $('#deleteDog').click(function (e) {
      e.preventDefault();
      var id=document.getElementById('deleteID').value;
      $.ajax({
        url:'assets/delete-dog.php',
        type:'POST',
        cache:false,
        data:{id:id},
        success:function(response){
          $('#row-dog-'+id).remove();
          $('#deleteDogModal').modal('hide');
          $('#deleteDogModalBody').empty();
        }
      });
    });
    $('.modal').on('hidden.bs.modal', function(){
      $('#bookRoomModalBody').empty();
      $('#deleteDogModalBody').empty();
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
          <div class='modal-body' id='bookRoomModalBody'></div>
          <div class='modal-footer'>
            <button type='submit' class='btn btn-primary' id='bookRoom'>Submit</button>
            <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </form>
  <button type='button' class='btn btn-default book-room' id='bookRoomFormButton' data-toggle='modal' data-target='#bookRoomModal' data-backdrop='static' title='Book Room'>Book Room</button>
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
  <form action='' method='post' id='deleteDogForm'>
    <div class='modal fade' id='deleteDogModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
      <div class='modal-dialog'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h4 class='modal-title'>Delete Dog</h4>
          </div>
          <div class='modal-body' id='deleteDogModalBody'></div>
          <div class='modal-footer'>
            <button type='submit' class='btn btn-danger' id='deleteDog'>Delete</button>
            <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </form>
</body>
</html>
