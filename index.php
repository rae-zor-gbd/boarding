<?php
include 'assets/config.php';
?>
<!DOCTYPE html>
<html lang='en'>
<head>
  <title>Food & Meds</title>
  <?php include 'assets/header.php'; ?>
  <script type='text/javascript'>
  function loadAddFoodForm(){
    $.ajax({
      url:'/assets/load-add-food-form.php',
      type:'POST',
      cache:false,
      data:{},
      success:function(data){
        if (data) {
          $('#addFoodModalBody').append(data);
        }
      }
    });
  }
  function loadFoodMeds(){
    $.ajax({
      url:'/assets/load-food-meds.php',
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
    loadFoodMeds();
    $('#addFood').click(function (e) {
      e.preventDefault();
      var room=document.getElementById('newRoom').value;
      var name=document.getElementById('newDogName').value;
      var foodType=document.getElementById('newFoodType').value;
      var feedingInstructions=document.getElementById('newFeedingInstructions').value;
      $.ajax({
        url:'assets/add-food.php',
        type:'POST',
        cache:false,
        data:{room:room, name:name, foodType:foodType, feedingInstructions:feedingInstructions},
        success:function(response){
          $('#table-rooms').empty();
          loadFoodMeds();
          $('#addFoodModal').modal('hide');
          document.getElementById('addFoodForm').reset();
        }
      });
    });
    $('#addFoodButton').click(function (e) {
      loadAddFoodForm();
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
      $('#addFoodModalBody').empty();
      $('#deleteDogModalBody').empty();
    });
  });
  </script>
</head>
<body>
  <?php include 'assets/navbar.php'; ?>
  <form action='' method='post' spellcheck='false' id='addFoodForm'>
    <div class='modal fade' id='addFoodModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
      <div class='modal-dialog'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h4 class='modal-title'>Add Food</h4>
          </div>
          <div class='modal-body' id='addFoodModalBody'></div>
          <div class='modal-footer'>
            <button type='submit' class='btn btn-primary' id='addFood'>Submit</button>
            <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </form>
  <button type='button' class='btn btn-default nav-add-button' id='addFoodButton' data-toggle='modal' data-target='#addFoodModal' data-backdrop='static' title='Add Food'>Add Food</button>
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
            <h4 class='modal-title'>Delete Food & Meds</h4>
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
