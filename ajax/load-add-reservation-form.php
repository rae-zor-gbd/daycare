<?php
include '../assets/config.php';
if (isset($_POST['id'])) {
  $dogID=$_POST['id'];
  $today=date('Y-m-d');
  $sql_reservation_info="SELECT lastName, dogName, o.ownerID FROM dogs d JOIN owners o USING (ownerID) WHERE dogID='$dogID'";
  $result_reservation_info=$conn->query($sql_reservation_info);
  $row_reservation_info=$result_reservation_info->fetch_assoc();
  $editLastName=htmlspecialchars($row_reservation_info['lastName'], ENT_QUOTES);
  $editDogName=htmlspecialchars($row_reservation_info['dogName'], ENT_QUOTES);
  $ownerID=$row_reservation_info['ownerID'];
  echo "<input type='hidden' class='form-control' name='id' id='addReservationID' value='$dogID' required>
  <input type='hidden' class='form-control' name='ownerID' id='addReservationOwnerID' value='$ownerID' required>
  <div class='input-group'>
  <span class='input-group-addon owner'>Last Name</span>
  <input type='text' class='form-control' name='lastName' id='addReservationLastName' value='$editLastName' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon dog'>Dog Name</span>
  <input type='text' class='form-control' name='dogName' id='addReservationName' value='$editDogName' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon calendar-day'>Reservation Date</span>
  <input type='date' class='form-control' name='reservationDate' id='addReservationDate' min='$today' required>
  </div>";
}
?>
