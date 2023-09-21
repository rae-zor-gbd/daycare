<?php
include 'assets/config.php';
?>
<!DOCTYPE html>
<html lang='en'>
<head>
  <title>Incomplete Paperwork</title>
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
  function loadRegistrations(){
    $.ajax({
      url:'/ajax/load-registrations.php',
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
    $('#paperwork').addClass('active');
    loadContracts();
    loadRegistrations();
    $(document).on('click', '#complete-contract-button', function() {
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
    $(document).on('click', '#complete-registration-button', function() {
      var id=$(this).data('id');
      $.ajax({
        url:'ajax/complete-client-registration.php',
        type:'POST',
        cache:false,
        data:{id:id},
        success:function(response){
          $('#panel-registration-'+id).remove();
        }
      });
    });
  });
  </script>
</head>
<body>
  <?php include 'assets/navbar.php'; ?>
  <div class='container-fluid paperwork-container'>
    <h3 class='paperwork-header'>Incomplete Client Registrations</h3>
    <div class='paperwork-panels' id='registrations-panels'></div>
    <h3 class='paperwork-header'>Incomplete Daycare Contracts</h3>
    <div class='paperwork-panels' id='contracts-panels'></div>
  </div>
</body>
</html>
