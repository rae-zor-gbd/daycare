<?php
include 'assets/config.php';
if (isset($_GET['reservationDate']) AND $_GET['reservationDate']!='') {
  $reservationDate=date('Y-m-d', strtotime($_GET['reservationDate']));
} else {
  $reservationDate=date('Y-m-d');
}
?>
<!DOCTYPE html>
<html lang='en'>
<head>
  <title>Daycare Reservations</title>
  <?php include 'assets/header.php'; ?>
  <script type='text/javascript'>
  function loadReservations(reservationDate){
    $.ajax({
      url:'/ajax/load-reservations.php',
      type:'POST',
      cache:false,
      data:{reservationDate:reservationDate},
      success:function(data){
        if (data) {
          $('#reservation-list').empty();
          $('#reservation-list').append(data);
        }
      }
    });
  }
  $(document).ready(function(){
    $('#reservations').addClass('active');
    loadReservations('<?php echo "$reservationDate"; ?>');
  });
  </script>
</head>
<body>
  <?php include 'assets/navbar.php'; ?>
  <div class='container-fluid'>
    <div class='table-container' id='reservation-list'></div>
  </div>
</body>
</html>
