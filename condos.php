<?php
include 'assets/config.php';
if (isset($_GET['startDate']) AND $_GET['startDate']!='' AND isset($_GET['endDate']) AND $_GET['endDate']!='') {
  $startDate=date('Y-m-d', strtotime($_GET['startDate']));
  $endDate=date('Y-m-d', strtotime($_GET['endDate']));
} else {
  $startDate=date('Y-m-d');
  $endDate=date('Y-m-d', strtotime($startDate. ' + 30 days'));
}
$minStartDate=date('Y-m-d', strtotime($startDate. ' - 1 day'));
$titleStartDate=date('D n/j', strtotime($startDate));
$titleEndDate=date('D n/j', strtotime($endDate));
?>
<!DOCTYPE html>
<html lang='en'>
  <head>
    <title>Condos | <?php echo "$titleStartDate – $titleEndDate"; ?></title>
    <?php include 'assets/header.php'; ?>
    <script type='text/javascript'>
      function loadBookCondoForm(){
        $.ajax({
          url:'/ajax/load-book-condo-form.php',
          type:'POST',
          cache:false,
          data:{},
          success:function(data){
            if (data) {
              $('#bookCondoModalBody').append(data);
            }
          }
        });
      }
      function loadCondos(startDate, endDate){
        $.ajax({
          url:'/ajax/load-condos.php',
          type:'POST',
          cache:false,
          data:{startDate:startDate, endDate:endDate},
          success:function(data){
            if (data) {
              $('#condos-container').empty();
              $('#condos-container').append(data);
            }
          }
        });
      }
      function loadCounts(startDate, endDate){
        $.ajax({
          url:'/ajax/load-cat-counts.php',
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
      function toggleDates(){
        var startDate=document.getElementById('startDate').value;
        var endDate=document.getElementById('endDate').value;
        window.open('/cats/condos/'+startDate+'/'+endDate, '_self');
      }
      $(document).ready(function() {
        $('#condos').addClass('active');
        loadCondos('<?php echo "$startDate" ?>', '<?php echo "$endDate" ?>');
        loadCounts('<?php echo "$startDate" ?>', '<?php echo "$endDate" ?>');
        $('#bookCondoButton').click(function (e) {
          loadBookCondoForm();
        });
        $('#bookCondo').click(function (e) {
          e.preventDefault();
          var condo=document.getElementById('newCondo').value;
          var name=document.getElementById('newCatName').value.toUpperCase();
          var checkIn=document.getElementById('newCheckIn').value;
          var checkOut=document.getElementById('newCheckOut').value;
          if (condo!='' && name!='' && checkIn!='' && checkOut!='') {
            $.ajax({
              url:'/ajax/book-condo.php',
              type:'POST',
              cache:false,
              data:{condo:condo, name:name, checkIn:checkIn, checkOut:checkOut},
              success:function(response){
                loadCondos('<?php echo "$startDate" ?>', '<?php echo "$endDate" ?>');
                loadCounts('<?php echo "$startDate" ?>', '<?php echo "$endDate" ?>');
                $('#bookCondoModal').modal('hide');
                document.getElementById('bookCondoForm').reset();
              }
            });
          } else {
            loadIncompleteFormAlert('#bookCondoModalBody');
          }
        });
        $(document).on('click', '#delete-condo-button', function() {
          var id=$(this).data('id');
          $.ajax({
            url:'/ajax/load-delete-condo-form.php',
            type:'POST',
            cache:false,
            data:{id:id},
            success:function(response){
              $('#deleteCondoModalBody').append(response);
            }
          });
        });
        $('#deleteCondo').click(function (e) {
          e.preventDefault();
          var id=document.getElementById('deleteID').value;
          $.ajax({
            url:'/ajax/delete-condo.php',
            type:'POST',
            cache:false,
            data:{id:id},
            success:function(response){
              $('#condo-occupant-'+id).remove();
              $('#deleteCondoModal').modal('hide');
              $('#deleteCondoModalBody').empty();
              loadCounts('<?php echo "$startDate" ?>', '<?php echo "$endDate" ?>');
            }
          });
        });
        $(document).on('click', '#edit-condo-button', function() {
          var id=$(this).data('id');
          $.ajax({
            url:'/ajax/load-edit-condo-form.php',
            type:'POST',
            cache:false,
            data:{id:id},
            success:function(response){
              $('#editCondoModalBody').append(response);
            }
          });
        });
        $('#editCondo').click(function (e) {
          e.preventDefault();
          var id=document.getElementById('editID').value;
          var condo=document.getElementById('editCondo').value;
          var catName=document.getElementById('editCatName').value.toUpperCase();
          var checkIn=document.getElementById('editCheckIn').value;
          var checkOut=document.getElementById('editCheckOut').value;
          if (id!='' && condo!='' && catName!='' && checkIn!='' && checkOut!='') {
            $.ajax({
              url:'/ajax/edit-condo.php',
              type:'POST',
              cache:false,
              data:{id:id, condo:condo, catName:catName, checkIn:checkIn, checkOut:checkOut},
              success:function(response){
                $('#editCondoModal').modal('hide');
                $('#editCondoModalBody').empty();
                loadCondos('<?php echo "$startDate" ?>', '<?php echo "$endDate" ?>');
                loadCounts('<?php echo "$startDate" ?>', '<?php echo "$endDate" ?>');
              }
            });
          } else {
            loadIncompleteFormAlert('#editCondoModalBody');
          } 
        });
        $('.modal').on('hidden.bs.modal', function(){
          $('#bookCondoModalBody').empty();
          $('#deleteCondoModalBody').empty();
          $('#editCondoModalBody').empty();
        });
      });
    </script>
  </head>
  <body>
    <?php include 'assets/navbar.php'; ?>
    <form action='' method='post' spellcheck='false' autocomplete='off' id='bookCondoForm'>
      <div class='modal fade' id='bookCondoModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'></button>
              <h4 class='modal-title'>Book Condo</h4>
            </div>
            <div class='modal-body' id='bookCondoModalBody'></div>
            <div class='modal-footer'>
              <button type='submit' class='btn btn-primary' id='bookCondo'>Submit</button>
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
          <input type='date' class='form-control' name='start-date' id='startDate' value='<?php echo $startDate; ?>' min='<?php echo $minStartDate; ?>' required>
        </div>
        <div class='input-group'>
          <span class='input-group-addon clock'>End Date</span>
          <input type='date' class='form-control' name='end-date' id='endDate' value='<?php echo $endDate; ?>' min='<?php echo $startDate; ?>' required>
        </div>
      </form>
      <button type='button' class='btn btn-default nav-button' id='bookCondoButton' data-toggle='modal' data-target='#bookCondoModal' data-backdrop='static' title='Book Condo'>Book Condo</button>
    </div>
    <div class='container-fluid'>
      <div class='condos-container' id='condos-container'></div>
    </div>
    <form action='' method='post' spellcheck='false' autocomplete='off' id='editCondoForm'>
      <div class='modal fade' id='editCondoModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'></button>
              <h4 class='modal-title'>Edit Reservation</h4>
            </div>
            <div class='modal-body' id='editCondoModalBody'></div>
            <div class='modal-footer'>
              <button type='submit' class='btn btn-primary' id='editCondo'>Submit</button>
              <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </form>
    <form action='' method='post' spellcheck='false' autocomplete='off' id='deleteCondoForm'>
      <div class='modal fade' id='deleteCondoModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'></button>
              <h4 class='modal-title'>Delete Reservation</h4>
            </div>
            <div class='modal-body' id='deleteCondoModalBody'></div>
            <div class='modal-footer'>
              <button type='submit' class='btn btn-danger' id='deleteCondo'>Delete</button>
              <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </body>
</html>