<?php
include '../assets/config.php';
if (isset($_POST['id']) AND isset($_POST['date'])) {
  $id=$_POST['id'];
  $date=$_POST['date'];
  if ($_POST['type']=='writeIn') {
    echo "<input type='hidden' class='form-control' name='type' id='deleteType' value='writeIn' required>";
    $sql_reservation_info="SELECT lastName, dogName FROM reservations_write_ins WHERE writeInID='$id' AND reservationDate='$date'";
  } else {
    echo "<input type='hidden' class='form-control' name='type' id='deleteType' value='regular' required>";
    $sql_reservation_info="SELECT lastName, dogName FROM dogs d JOIN owners o USING (ownerID) JOIN reservations r USING (dogID) WHERE dogID='$id' AND reservationDate='$date'";
  }
  $result_reservation_info=$conn->query($sql_reservation_info);
  $row_reservation_info=$result_reservation_info->fetch_assoc();
  $lastName=htmlspecialchars($row_reservation_info['lastName'], ENT_QUOTES);
  $dogName=htmlspecialchars($row_reservation_info['dogName'], ENT_QUOTES);
  echo "<input type='hidden' class='form-control' name='id' id='deleteID' value='$id' required>
  <input type='hidden' class='form-control' name='reservation-date' id='deleteReservationDate' value='$date' required>
  <div class='input-group'>
  <span class='input-group-addon owner'>Last Name</span>
  <input type='text' class='form-control' name='last-name' id='deleteReservationLastName' value='$lastName' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon dog'>Dog Name</span>
  <input type='text' class='form-control' name='dog-name' id='deleteReservationName' value='$dogName' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon calendar-day'>Reservation Date</span>
  <input type='text' class='form-control' name='display-date' id='deleteDisplayDate' value='" . date('l, F j, Y', strtotime($date)) . "' disabled>
  </div>";
}
?>
