<?php
include 'assets/config.php';
?>
<!DOCTYPE html>
<html lang='en'>
<head>
  <title>Daycare Reminders</title>
  <?php include 'assets/header.php'; ?>
  <script type='text/javascript'>
  function loadReminders(){
    $.ajax({
      url:'/assets/load-reminders.php',
      type:'POST',
      cache:false,
      data:{},
      success:function(data){
        if (data) {
          $('#table-reminders').empty();
          $('#table-reminders').append(data);
        }
      }
    });
  }
  $(document).ready(function(){
    $('#reminders').addClass('active');
    loadReminders();
  });
  </script>
</head>
<body>
  <?php include 'assets/navbar.php'; ?>
  <div class='container-fluid'>
    <div class='table-container'>
      <table class='table table-hover table-condensed'>
        <thead>
          <tr>
            <th>Owner</th>
            <th>Email Address</th>
            <th>Package Reminders</th>
            <th>Vaccine Reminders</th>
          </tr>
        </thead>
        <tbody id='table-reminders'></tbody>
      </table>
    </div>
  </div>
</body>
</html>
