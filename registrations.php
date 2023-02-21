<?php
include 'assets/config.php';
?>
<!DOCTYPE html>
<html lang='en'>
<head>
  <title>Incomplete Client Registrations</title>
  <?php include 'assets/header.php'; ?>
  <script type='text/javascript'>
  function loadRegistrations(){
    $.ajax({
      url:'/assets/load-registrations.php',
      type:'POST',
      cache:false,
      data:{},
      success:function(data){
        if (data) {
          $('#registrations-panels').empty();
          $('#registrations-panels').append(data);
        }
      }
    });
  }
  $(document).ready(function(){
    $('#registrations').addClass('active');
    loadRegistrations();
    $(document).on('click', '.button-complete', function() {
      var id=$(this).data('id');
      $.ajax({
        url:'assets/complete-client-registration.php',
        type:'POST',
        cache:false,
        data:{id:id},
        success:function(response){
          $('#panel-contract-'+id).remove();
        }
      });
    });
  });
  </script>
</head>
<body>
  <?php include 'assets/navbar.php'; ?>
  <div class='container-fluid'>
    <div class='contracts-container' id='registrations-panels'></div>
  </div>
</body>
</html>
