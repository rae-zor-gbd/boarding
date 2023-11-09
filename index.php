<?php
include 'assets/config.php';
if (isset($_GET['meds']) AND $_GET['meds']!='') {
  $sortMeds=$_GET['meds'];
} else {
  $sortMeds='all';
}
?>
<!DOCTYPE html>
<html lang='en'>
  <head>
    <title>
      Dog Food & Meds
      <?php
      if ($sortMeds=='am') {
        echo " | AM Meds";
      } elseif ($sortMeds=='noon') {
        echo " | Noon Meds";
      } elseif ($sortMeds=='pm') {
        echo " | PM Meds";
      }
      ?>
    </title>
    <?php include 'assets/header.php'; ?>
    <script type='text/javascript'>
      function loadAddFoodForm(status){
        $.ajax({
          url:'/ajax/load-add-dog-food-form.php',
          type:'POST',
          cache:false,
          data:{status:status},
          success:function(data){
            if (data) {
              $('#addFoodModalBody').append(data);
              loadTableCounts();
            }
          }
        });
      }
      function loadaddMedForm(status, id){
        $.ajax({
          url:'/ajax/load-add-dog-med-form.php',
          type:'POST',
          cache:false,
          data:{status:status, id:id},
          success:function(data){
            if (data) {
              $('#addMedModalBody').append(data);
            }
          }
        });
      }
      function loadFoodMeds(status, sortMeds){
        $.ajax({
          url:'/ajax/load-dog-food-meds.php',
          type:'POST',
          cache:false,
          data:{status:status, sortMeds:sortMeds},
          success:function(data){
            if (data) {
              if (status=='Active') {
                $('#table-currently-boarding').empty();
                $('#table-currently-boarding').append(data);
              } if (status=='Future') {
                $('#table-future-arrivals').empty();
                $('#table-future-arrivals').append(data);
              }
              loadTableCounts();
            }
          }
        });
      }
      function loadTableCounts() {
        $('#table-currently-boarding-count').empty();
        var currentBoardingCount=$('#table-currently-boarding').find('tr').length;
        $('#table-currently-boarding-count').append(currentBoardingCount);
        $('#table-future-arrivals-count').empty();
        var futureArrivalsCount=$('#table-future-arrivals').find('tr').length;
        $('#table-future-arrivals-count').append(futureArrivalsCount);
      }
      $(document).ready(function(){
        $('#dog-food-meds').addClass('active');
        <?php
        if ($sortMeds=='all') {
          echo "$('#allFoodMedsButton').addClass('active');";
        } elseif ($sortMeds=='am') {
          echo "$('#amMedsButton').addClass('active');";
        } elseif ($sortMeds=='noon') {
          echo "$('#noonMedsButton').addClass('active');";
        } elseif ($sortMeds=='pm') {
          echo "$('#pmMedsButton').addClass('active');";
        }
        ?>
        loadFoodMeds('Active', <?php echo "'$sortMeds'"; ?>);
        loadFoodMeds('Future', <?php echo "'$sortMeds'"; ?>);
        $('#addFood').click(function (e) {
          e.preventDefault();
          var status=document.getElementById('newStatus').value;
          var reservationID=document.getElementById('newDog').value;
          var foodType=document.getElementById('newFoodType').value;
          var feedingInstructions=document.getElementById('newFeedingInstructions').value.toUpperCase();
          var specialNotes=document.getElementById('newSpecialNotes').value.toUpperCase();
          if (document.getElementById('newFoodAllergies').checked==true) {
            var foodAllergies='Yes';
          } else {
            var foodAllergies='No';
          }
          if (document.getElementById('newNoSlipBowl').checked==true) {
            var noSlipBowl='Yes';
          } else {
            var noSlipBowl='No';
          }
          if (document.getElementById('newPlasticBowl').checked==true) {
            var plasticBowl='Yes';
          } else {
            var plasticBowl='No';
          }
          if (document.getElementById('newSlowFeeder').checked==true) {
            var slowFeeder='Yes';
          } else {
            var slowFeeder='No';
          }
          if (document.getElementById('newElevatedFeeder').checked==true) {
            var elevatedFeeder='Yes';
          } else {
            var elevatedFeeder='No';
          }
          if (document.getElementById('newSeparateToFeed').checked==true) {
            var separateToFeed='Yes';
          } else {
            var separateToFeed='No';
          }
          if (document.getElementById('newGrazer').checked==true) {
            var grazer='Yes';
          } else {
            var grazer='No';
          }
          if (status!='' && reservationID!='' && foodType!='' && feedingInstructions!='') {
            $.ajax({
              url:'/ajax/add-dog-food.php',
              type:'POST',
              cache:false,
              data:{status:status, reservationID:reservationID, foodType:foodType, feedingInstructions:feedingInstructions, specialNotes:specialNotes, foodAllergies:foodAllergies, noSlipBowl:noSlipBowl, plasticBowl:plasticBowl, slowFeeder:slowFeeder, elevatedFeeder:elevatedFeeder, separateToFeed:separateToFeed, grazer:grazer},
              success:function(response){
                loadFoodMeds(status, <?php echo "'$sortMeds'"; ?>);
                $('#addFoodModal').modal('hide');
                document.getElementById('addFoodForm').reset();
              }
            });
          } else {
            loadIncompleteFormAlert('#addFoodModalBody');
          }
        });
        $('#addMed').click(function (e) {
          e.preventDefault();
          var status=document.getElementById('newStatus').value;
          var id=document.getElementById('newID').value;
          var medName=document.getElementById('newMedName').value.toUpperCase();
          var strength=document.getElementById('newStrength').value.toUpperCase();
          var dosage=document.getElementById('newDosage').value.toUpperCase();
          var frequency=document.getElementById('newFrequency').value;
          var notes=document.getElementById('newNotes').value.toUpperCase();
          if (status!='' && id!='' && medName!='' && dosage!='' && frequency!='') {
            $.ajax({
              url:'/ajax/add-dog-med.php',
              type:'POST',
              cache:false,
              data:{status:status, id:id, medName:medName, strength:strength, dosage:dosage, frequency:frequency, notes:notes},
              success:function(response){
                loadFoodMeds(status, <?php echo "'$sortMeds'"; ?>);
                $('#addMedModal').modal('hide');
                document.getElementById('addMedForm').reset();
              }
            });
          } else {
            loadIncompleteFormAlert('#addMedModalBody');
          }
        });
        $('#addCurrentlyBoardingButton').click(function (e) {
          loadAddFoodForm('Active');
        });
        $('#addFutureArrivalsButton').click(function (e) {
          loadAddFoodForm('Future');
        });
        $(document).on('click', '#add-med-button', function() {
          var status=$(this).data('status');
          var id=$(this).data('id');
          $.ajax({
            url:'/ajax/load-add-dog-med-form.php',
            type:'POST',
            cache:false,
            data:{status:status, id:id},
            success:function(response){
              $('#addMedModalBody').append(response);
            }
          });
        });
        $(document).on('click', '.button-check', function() {
          var id=$(this).data('id');
          $.ajax({
            url:'/ajax/check-in-dog.php',
            type:'POST',
            cache:false,
            data:{id:id},
            success:function(response){
              $('#row-dog-'+id).remove();
              loadFoodMeds('Active', <?php echo "'$sortMeds'"; ?>);
              loadFoodMeds('Future', <?php echo "'$sortMeds'"; ?>);
            }
          });
        });
        $(document).on('click', '.button-door', function() {
          var id=$(this).data('id');
          var row=$(this).data('row');
          $.ajax({
            url:'/ajax/delete-room.php',
            type:'POST',
            cache:false,
            data:{id:id},
            success:function(response){
              $('#row-dog-'+row).remove();
              loadFoodMeds('Active', <?php echo "'$sortMeds'"; ?>);
              loadFoodMeds('Future', <?php echo "'$sortMeds'"; ?>);
            }
          });
        });
        $(document).on('click', '#delete-dog-button', function() {
          var id=$(this).data('id');
          $.ajax({
            url:'/ajax/load-delete-dog-form.php',
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
            url:'/ajax/delete-dog.php',
            type:'POST',
            cache:false,
            data:{id:id},
            success:function(response){
              $('#row-dog-'+id).remove();
              $('#deleteDogModal').modal('hide');
              $('#deleteDogModalBody').empty();
              loadTableCounts();
            }
          });
        });
        $(document).on('click', '#delete-med-button', function() {
          var id=$(this).data('id');
          $.ajax({
            url:'/ajax/load-delete-dog-med-form.php',
            type:'POST',
            cache:false,
            data:{id:id, status:status},
            success:function(response){
              $('#deleteMedModalBody').append(response);
            }
          });
        });
        $('#deleteMed').click(function (e) {
          e.preventDefault();
          var id=document.getElementById('deleteID').value;
          var status=document.getElementById('deleteStatus').value;
          $.ajax({
            url:'/ajax/delete-dog-med.php',
            type:'POST',
            cache:false,
            data:{id:id, status:status},
            success:function(response){
              $('#med-label-'+id).remove();
              $('#deleteMedModal').modal('hide');
              $('#deleteMedModalBody').empty();
              $('#table-currently-boarding').empty();
              $('#table-future-arrivals').empty();
              loadFoodMeds('Active', <?php echo "'$sortMeds'"; ?>);
              loadFoodMeds('Future', <?php echo "'$sortMeds'"; ?>);
              loadTableCounts();
            }
          });
        });
        $(document).on('click', '#edit-dog-button', function() {
          var id=$(this).data('id');
          var status=$(this).data('status');
          $.ajax({
            url:'/ajax/load-edit-dog-food-form.php',
            type:'POST',
            cache:false,
            data:{id:id, status:status},
            success:function(response){
              $('#editDogModalBody').append(response);
            }
          });
        });
        $('#editDog').click(function (e) {
          e.preventDefault();
          var id=document.getElementById('editID').value;
          var status=document.getElementById('editStatus').value;
          var foodType=document.getElementById('editFoodType').value;
          var feedingInstructions=document.getElementById('editFeedingInstructions').value.toUpperCase();
          var specialNotes=document.getElementById('editSpecialNotes').value.toUpperCase();
          if (document.getElementById('editFoodAllergies').checked==true) {
            var foodAllergies='Yes';
          } else {
            var foodAllergies='No';
          }
          if (document.getElementById('editNoSlipBowl').checked==true) {
            var noSlipBowl='Yes';
          } else {
            var noSlipBowl='No';
          }
          if (document.getElementById('editPlasticBowl').checked==true) {
            var plasticBowl='Yes';
          } else {
            var plasticBowl='No';
          }
          if (document.getElementById('editSlowFeeder').checked==true) {
            var slowFeeder='Yes';
          } else {
            var slowFeeder='No';
          }
          if (document.getElementById('editElevatedFeeder').checked==true) {
            var elevatedFeeder='Yes';
          } else {
            var elevatedFeeder='No';
          }
          if (document.getElementById('editSeparateToFeed').checked==true) {
            var separateToFeed='Yes';
          } else {
            var separateToFeed='No';
          }
          if (document.getElementById('editGrazer').checked==true) {
            var grazer='Yes';
          } else {
            var grazer='No';
          }
          if (id!='' && status!='' && foodType!='' && feedingInstructions!='') {
            $.ajax({
              url:'/ajax/edit-dog-food.php',
              type:'POST',
              cache:false,
              data:{id:id, status:status, foodType:foodType, feedingInstructions:feedingInstructions, specialNotes:specialNotes, foodAllergies:foodAllergies, noSlipBowl:noSlipBowl, plasticBowl:plasticBowl, slowFeeder:slowFeeder, elevatedFeeder:elevatedFeeder, separateToFeed:separateToFeed, grazer:grazer},
              success:function(response){
                $('#editDogModal').modal('hide');
                $('#editDogModalBody').empty();
                $('#table-currently-boarding').empty();
                $('#table-future-arrivals').empty();
                loadFoodMeds('Active', <?php echo "'$sortMeds'"; ?>);
                loadFoodMeds('Future', <?php echo "'$sortMeds'"; ?>);
                loadTableCounts();
              }
            });
          } else {
            loadIncompleteFormAlert('#editDogModalBody');
          }  
        });
        $(document).on('click', '#edit-med-button', function() {
          var id=$(this).data('id');
          var status=$(this).data('status');
          $.ajax({
            url:'/ajax/load-edit-dog-med-form.php',
            type:'POST',
            cache:false,
            data:{id:id, status:status},
            success:function(response){
              $('#editMedModalBody').append(response);
            }
          });
        });
        $('#editMed').click(function (e) {
          e.preventDefault();
          var id=document.getElementById('editID').value;
          var status=document.getElementById('editStatus').value;
          var medName=document.getElementById('editMedName').value.toUpperCase();
          var strength=document.getElementById('editStrength').value.toUpperCase();
          var dosage=document.getElementById('editDosage').value.toUpperCase();
          var frequency=document.getElementById('editFrequency').value;
          var notes=document.getElementById('editNotes').value.toUpperCase();
          if (id!='' && status!='' && medName!='' && dosage!='' && frequency!='') {
            $.ajax({
              url:'/ajax/edit-dog-med.php',
              type:'POST',
              cache:false,
              data:{id:id, status:status, medName:medName, strength:strength, dosage:dosage, frequency:frequency, notes:notes},
              success:function(response){
                $('#editMedModal').modal('hide');
                $('#editMedModalBody').empty();
                $('#table-currently-boarding').empty();
                $('#table-future-arrivals').empty();
                loadFoodMeds('Active', <?php echo "'$sortMeds'"; ?>);
                loadFoodMeds('Future', <?php echo "'$sortMeds'"; ?>);
                loadTableCounts();
              }
            });
          } else {
            loadIncompleteFormAlert('#editMedModalBody');
          } 
        });
        $('.modal').on('hidden.bs.modal', function(){
          $('#addFoodModalBody').empty();
          $('#addMedModalBody').empty();
          $('#deleteDogModalBody').empty();
          $('#deleteMedModalBody').empty();
          $('#editDogModalBody').empty();
          $('#editMedModalBody').empty();
        });
      });
    </script>
  </head>
  <body>
    <?php include 'assets/navbar.php'; ?>
    <form action='' method='post' spellcheck='false' autocomplete='off' id='addFoodForm'>
      <div class='modal fade' id='addFoodModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'></button>
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
    <div class='nav-footer'>
      <a href='/dogs/food-meds'>
        <button type='button' class='btn btn-default nav-button' id='allFoodMedsButton' title='All Food & Medications'>All Food & Medications</button>
      </a>
      <a href='/dogs/medications/am'>
        <button type='button' class='btn btn-default nav-button' id='amMedsButton' title='AM Medications'>AM Medications</button>
      </a>
      <a href='/dogs/medications/noon'>
        <button type='button' class='btn btn-default nav-button' id='noonMedsButton' title='Noon Medications'>Noon Medications</button>
      </a>
      <a href='/dogs/medications/pm'>
        <button type='button' class='btn btn-default nav-button' id='pmMedsButton' title='PM Medications'>PM Medications</button>
      </a>
    </div>
    <div class='container-fluid'>
      <div class='table-outer'>
        <div class='table-header'>
          <span class='table-heading'>Currently Boarding</span>
          <span class='table-count' id='table-currently-boarding-count'>0</span>
          <a>
            <button type='button' class='pull-right button-add' id='addCurrentlyBoardingButton' data-toggle='modal' data-target='#addFoodModal' data-backdrop='static' title='Add Food'></button>
          </a>
        </div>
        <div class='table-container'>
          <table class='table table-hover table-condensed'>
            <thead class='header-currently-boarding'>
              <tr>
                <th>Room</th>
                <th>Name</th>
                <th>Food</th>
                <th>Feeding Instructions</th>
                <th>Medications</th>
                <th></th>
              </tr>
            </thead>
            <tbody id='table-currently-boarding'></tbody>
          </table>
        </div>
      </div>
      <div class='table-outer'>
        <div class='table-header'>
          <span class='table-heading'>Future Arrivals</span>
          <span class='table-count' id='table-future-arrivals-count'>0</span>
          <a>
            <button type='button' class='pull-right button-add' id='addFutureArrivalsButton' data-toggle='modal' data-target='#addFoodModal' data-backdrop='static' title='Add Food'></button>
          </a>
        </div>
        <div class='table-container'>
          <table class='table table-hover table-condensed'>
            <thead class='header-future-arrivals'>
              <tr>
                <th>Check-In</th>
                <th>Room</th>
                <th>Name</th>
                <th>Food</th>
                <th>Feeding Instructions</th>
                <th>Medications</th>
                <th></th>
              </tr>
            </thead>
            <tbody id='table-future-arrivals'></tbody>
          </table>
        </div>
      </div>
    </div>
    <form action='' method='post' spellcheck='false' autocomplete='off' id='addMedForm'>
      <div class='modal fade' id='addMedModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'></button>
              <h4 class='modal-title'>Add Medication</h4>
            </div>
            <div class='modal-body' id='addMedModalBody'></div>
            <div class='modal-footer'>
              <button type='submit' class='btn btn-primary' id='addMed'>Submit</button>
              <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </form>
    <form action='' method='post' spellcheck='false' autocomplete='off' id='editDogForm'>
      <div class='modal fade' id='editDogModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'></button>
              <h4 class='modal-title'>Edit Food</h4>
            </div>
            <div class='modal-body' id='editDogModalBody'></div>
            <div class='modal-footer'>
              <button type='submit' class='btn btn-primary' id='editDog'>Submit</button>
              <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </form>
    <form action='' method='post' spellcheck='false' autocomplete='off' id='editMedForm'>
      <div class='modal fade' id='editMedModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'></button>
              <h4 class='modal-title'>Edit Medication</h4>
            </div>
            <div class='modal-body' id='editMedModalBody'></div>
            <div class='modal-footer'>
              <button type='submit' class='btn btn-primary' id='editMed'>Submit</button>
              <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </form>
    <form action='' method='post' spellcheck='false' autocomplete='off' id='deleteDogForm'>
      <div class='modal fade' id='deleteDogModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'></button>
              <h4 class='modal-title'>Delete Food</h4>
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
    <form action='' method='post' spellcheck='false' autocomplete='off' id='deleteMedForm'>
      <div class='modal fade' id='deleteMedModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'></button>
              <h4 class='modal-title'>Delete Medication</h4>
            </div>
            <div class='modal-body' id='deleteMedModalBody'></div>
            <div class='modal-footer'>
              <button type='submit' class='btn btn-danger' id='deleteMed'>Delete</button>
              <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </body>
</html>
