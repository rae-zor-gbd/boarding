<meta charset='utf-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<link rel='icon' href='data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M152.1 38.2c9.9 8.9 10.7 24 1.8 33.9l-72 80c-4.4 4.9-10.6 7.8-17.2 7.9s-12.9-2.4-17.6-7L7 113C-2.3 103.6-2.3 88.4 7 79s24.6-9.4 33.9 0l22.1 22.1 55.1-61.2c8.9-9.9 24-10.7 33.9-1.8zm0 160c9.9 8.9 10.7 24 1.8 33.9l-72 80c-4.4 4.9-10.6 7.8-17.2 7.9s-12.9-2.4-17.6-7L7 273c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l22.1 22.1 55.1-61.2c8.9-9.9 24-10.7 33.9-1.8zM224 96c0-17.7 14.3-32 32-32H480c17.7 0 32 14.3 32 32s-14.3 32-32 32H256c-17.7 0-32-14.3-32-32zm0 160c0-17.7 14.3-32 32-32H480c17.7 0 32 14.3 32 32s-14.3 32-32 32H256c-17.7 0-32-14.3-32-32zM160 416c0-17.7 14.3-32 32-32H480c17.7 0 32 14.3 32 32s-14.3 32-32 32H192c-17.7 0-32-14.3-32-32zM48 368a48 48 0 1 1 0 96 48 48 0 1 1 0-96z"/></svg>'>
<link rel='stylesheet' href='/css/bootstrap.min.css'>
<script type='text/javascript' src='/js/jquery.min.js'></script>
<script type='text/javascript' src='/js/bootstrap.min.js'></script>
<?php $mainStylesheetTimestamp=filemtime('css/main.css'); ?>
<link rel='stylesheet' href='<?php echo "/css/main.css?v=" . $mainStylesheetTimestamp; ?>'>
<?php $printStylesheetTimestamp=filemtime('css/print.css'); ?>
<link rel='stylesheet' media='print' href='<?php echo "/css/print.css?v=" . $printStylesheetTimestamp; ?>'>
<script type='text/javascript'>
  function hideLoader() {
    $('#loading').hide();
  }
  function loadIncompleteFormAlert(id){
    $.ajax({
      url:'/assets/incomplete-form-alert.php',
      type:'POST',
      cache:false,
      data:{},
      success:function(data){
        if (data) {
          $(id+' .alert').remove();
          $(id).prepend(data);
        }
      }
    });
  }
  function loadInvalidReservationDatesAlert(id){
    $.ajax({
      url:'/assets/invalid-reservation-dates-alert.php',
      type:'POST',
      cache:false,
      data:{},
      success:function(data){
        if (data) {
          $(id+' .alert').remove();
          $(id).prepend(data);
        }
      }
    });
  }
  $(window).ready(hideLoader);
</script>
