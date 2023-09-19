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
  function changeDate(){
    var goToDate=document.getElementById('goToDate').value;
    window.open('/reservations/'+goToDate, '_self');
  }
  $(document).ready(function(){
    $('#reservations').addClass('active');
    loadReservations('<?php echo "$reservationDate"; ?>');
  });
  </script>
</head>
<body>
  <?php include 'assets/navbar.php'; ?>
  <div class='nav-footer'>
    <form action='' method='post' spellcheck='false' id='goToDateForm' onchange='changeDate()'>
      <div class='input-group'>
        <span class='input-group-addon clock'>Date</span>
        <input type='date' class='form-control' name='go-to-date' id='goToDate' value='<?php echo $reservationDate; ?>' required>
      </div>
    </form>
    <button type='button' class='btn btn-default nav-button' id='addReservationButton' data-toggle='modal' data-target='#addReservationModal' data-backdrop='static' title='Add Reservation'>Add Reservation</button>
  </div>
  <div class='container-fluid'>
    <div class='table-container' id='reservation-list'></div>
  </div>
</body>
</html>
