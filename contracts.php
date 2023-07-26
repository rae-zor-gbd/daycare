<?php
include 'assets/config.php';
?>
<!DOCTYPE html>
<html lang='en'>
<head>
  <title>Incomplete Daycare Contracts</title>
  <?php include 'assets/header.php'; ?>
  <script type='text/javascript'>
  function loadContracts(){
    $.ajax({
      url:'/ajax/load-contracts.php',
      type:'POST',
      cache:false,
      data:{},
      success:function(data){
        if (data) {
          $('#contracts-panels').empty();
          $('#contracts-panels').append(data);
        }
      }
    });
  }
  $(document).ready(function(){
    $('#contracts').addClass('active');
    loadContracts();
    $(document).on('click', '.button-complete', function() {
      var id=$(this).data('id');
      $.ajax({
        url:'ajax/complete-daycare-contract.php',
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
    <div class='contracts-container' id='contracts-panels'></div>
  </div>
</body>
</html>
