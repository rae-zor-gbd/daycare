<?php
include 'assets/config.php';
if (isset($_GET['date']) AND $_GET['date']!='') {
  $reservationDate=date('Y-m-d', strtotime($_GET['date']));
} else {
  $reservationDate=date('Y-m-d');
}
if (isset($_GET['filter']) AND $_GET['filter']!='') {
  $reservationFilter=$_GET['filter'];
} else {
  $reservationFilter='all';
}
?>
<!DOCTYPE html>
<html lang='en'>
  <head>
    <title>Daycare Reservations</title>
    <?php include 'assets/header.php'; ?>
    <script type='text/javascript'>
      function loadReservationCount() {
        $('#reservation-count').empty();
        var reservationCount=$('#table-reservations').find('.reservation-row').length;
        $('#reservation-count').append(reservationCount);
      }
      function loadReservations(reservationDate){
        var reservationFilter='<?php echo $reservationFilter; ?>';
        $.ajax({
          url:'/ajax/load-reservations.php',
          type:'POST',
          cache:false,
          data:{reservationDate:reservationDate, reservationFilter:reservationFilter},
          success:function(data){
            if (data) {
              $('#reservation-list').empty();
              $('#reservation-list').append(data);
              loadReservationCount();
            }
          }
        });
      }
      function changeDate(){
        var goToDate=document.getElementById('goToDate').value;
        window.open('/reservations/'+goToDate, '_self');
      }
      function toggleClienteleType() {
        if (document.getElementById('regularClientele').checked){
          document.getElementById('toggleRegularClientele').style.display='block';
          document.getElementById('toggleWriteInClientele').style.display='none';
        } else if (document.getElementById('writeInClientele').checked){
          document.getElementById('toggleRegularClientele').style.display='none';
          document.getElementById('toggleWriteInClientele').style.display='block';
        }
      }
      $(document).ready(function(){
        $('#reservations').addClass('active');
        loadReservations('<?php echo "$reservationDate"; ?>');
        $(document).on('click', '#add-reservation-button', function() {
          var addReservationDate=$(this).data('date');
          $.ajax({
            url:'/ajax/load-add-reservation-form.php',
            type:'POST',
            cache:false,
            data:{addReservationDate:addReservationDate},
            success:function(response){
              $('#addReservationModalBody').append(response);
            }
          });
        });
        $('#addReservation').click(function (e) {
          e.preventDefault();
          var date=document.getElementById('addReservationDate').value;
          if (document.getElementById('regularClientele').checked==true) {
            var dogID=document.getElementById('addReservationID').value;
            if (dogID!='' && date!='') {
              $.ajax({
                url:'/ajax/add-reservation.php',
                type:'POST',
                cache:false,
                data:{dogID:dogID, date:date},
                success:function(response){
                  $('#addReservationModal').modal('hide');
                  $('#addReservationModalBody').empty();
                  loadReservations(date);
                }
              });
            } else {
              loadIncompleteFormAlert('#addReservationModalBody');
            }
          }
          if (document.getElementById('writeInClientele').checked==true) {
            var dogName=document.getElementById('addDogName').value;
            var lastName=document.getElementById('addLastName').value;
            if (dogName!='' && lastName!='' && date!='') {
              $.ajax({
                url:'/ajax/add-reservation.php',
                type:'POST',
                cache:false,
                data:{dogName:dogName, lastName:lastName, date:date},
                success:function(response){
                  $('#addReservationModal').modal('hide');
                  $('#addReservationModalBody').empty();
                  loadReservations(date);
                }
              });
            } else {
              loadIncompleteFormAlert('#addReservationModalBody');
            }
          }
        });
        $(document).on('click', '#delete-reservation-button', function() {
          var id=$(this).data('id');
          var date=$(this).data('date');
          var type=$(this).data('type');
          $.ajax({
            url:'/ajax/load-delete-reservation-form.php',
            type:'POST',
            cache:false,
            data:{id:id, date:date, type:type},
            success:function(response){
              $('#deleteReservationModalBody').append(response);
            }
          });
        });
        $('#deleteReservation').click(function (e) {
          e.preventDefault();
          var id=document.getElementById('deleteID').value;
          var date=document.getElementById('deleteReservationDate').value;
          var type=document.getElementById('deleteType').value;
          $.ajax({
            url:'/ajax/delete-reservation.php',
            type:'POST',
            cache:false,
            data:{id:id, date:date, type:type},
            success:function(response){
              $('#deleteReservationModal').modal('hide');
              $('#deleteReservationModalBody').empty();
              loadReservations(date);
            }
          });
        });
        $(document).on('click', '.button-check', function() {
          var dogID=$(this).data('id');
          var date='<?php echo $reservationDate; ?>'
          $.ajax({
            url:'/ajax/add-reservation.php',
            type:'POST',
            cache:false,
            data:{dogID:dogID, date:date},
            success:function(response){
              loadReservations(date);
            }
          });
        });
        $('.modal').on('hidden.bs.modal', function(){
          $('#addReservationModalBody').empty();
          $('#deleteReservationModalBody').empty();
        });
      });
    </script>
  </head>
  <body>
    <?php include 'assets/navbar.php'; ?>
    <div class='nav-footer'>
      <form action='' method='post' spellcheck='false' autocomplete='off' id='goToDateForm' onchange='changeDate()'>
        <div class='input-group'>
          <span class='input-group-addon calendar-day'>Date</span>
          <input type='date' class='form-control' name='go-to-date' id='goToDate' value='<?php echo $reservationDate; ?>' required>
        </div>
      </form>
      <a href='?filter=all'>
        <button type='button' class='btn btn-default nav-button' id='filter-reservations-all' title='Display All Reservations'>All Reservations</button>
      </a>
      <a href='?filter=confirmed'>
        <button type='button' class='btn btn-default nav-button' id='filter-reservations-confirmed' title='Display Confirmed Reservations Only'>Confirmed Reservations</button>
      </a>
      <button type='button' class='btn btn-default nav-button' id='add-reservation-button' data-toggle='modal' data-target='#addReservationModal' data-backdrop='static' data-date='<?php echo $reservationDate; ?>' title='Add Reservation'>Add Reservation</button>
    </div>
    <div class='container-fluid'>
      <div class='table-container' id='reservation-list'></div>
    </div>
    <form action='' method='post' spellcheck='false' autocomplete='off' id='addReservationForm'>
      <div class='modal fade' id='addReservationModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'></button>
              <h4 class='modal-title'>Add Reservation</h4>
            </div>
            <div class='modal-body' id='addReservationModalBody'></div>
            <div class='modal-footer'>
              <button type='submit' class='btn btn-primary' id='addReservation'>Submit</button>
              <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </form>
    <form action='' method='post' spellcheck='false' autocomplete='off' id='deleteReservationForm'>
      <div class='modal fade' id='deleteReservationModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'></button>
              <h4 class='modal-title'>Delete Reservation</h4>
            </div>
            <div class='modal-body' id='deleteReservationModalBody'></div>
            <div class='modal-footer'>
              <button type='submit' class='btn btn-danger' id='deleteReservation'>Delete</button>
              <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </body>
</html>
