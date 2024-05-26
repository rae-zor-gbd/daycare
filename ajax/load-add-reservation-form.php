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
if (isset($_POST['addReservationDate'])) {
  $reservationDate=$_POST['addReservationDate'];
  $dayOfWeek=date('l', strtotime($reservationDate));
  echo "<input type='hidden' class='form-control' name='reservationDate' id='addReservationDate' value='$reservationDate' required>
  <div class='reservation-clientele-type'>
  <div>
  <input type='radio' id='regularClientele' name='type' value='regularClientele' onchange='toggleClienteleType()' checked>
  <label for='regularClientele'>Regular Daycare Clientele</label>
  </div>
  <div>
  <input type='radio' id='writeInClientele' name='type' value='writeInClientele' onchange='toggleClienteleType()'>
  <label for='writeInClientele'>Write-In Clientele</label>
  </div>
  </div>
  <div id='toggleRegularClientele'>";
  echo "<div class='input-group'>
  <span class='input-group-addon dog'>Dog Name</span>
  <select class='form-control' id='addReservationID' name='dogNameRegular' required>
  <option value='' disabled selected>Select Dog</option>";
  $sql_all_dogs="SELECT dogID, lastName, dogName FROM dogs d JOIN owners o USING (ownerID) WHERE dogID NOT IN (SELECT dogID FROM reservations WHERE reservationDate='$reservationDate')";
  if ($dayOfWeek=='Monday') {
    $sql_all_dogs.=" AND dogID NOT IN (SELECT dogID FROM dogs d JOIN owners o USING (ownerID) WHERE reserveMondays='Yes')";
  } elseif ($dayOfWeek=='Tuesday') {
    $sql_all_dogs.=" AND dogID NOT IN (SELECT dogID FROM dogs d JOIN owners o USING (ownerID) WHERE reserveTuesdays='Yes')";
  } elseif ($dayOfWeek=='Wednesday') {
    $sql_all_dogs.=" AND dogID NOT IN (SELECT dogID FROM dogs d JOIN owners o USING (ownerID) WHERE reserveWednesdays='Yes')";
  } elseif ($dayOfWeek=='Thursday') {
    $sql_all_dogs.=" AND dogID NOT IN (SELECT dogID FROM dogs d JOIN owners o USING (ownerID) WHERE reserveThursdays='Yes')";
  } elseif ($dayOfWeek=='Friday') {
    $sql_all_dogs.=" AND dogID NOT IN (SELECT dogID FROM dogs d JOIN owners o USING (ownerID) WHERE reserveFridays='Yes')";
  }
  $sql_all_dogs.=" ORDER BY lastName, dogName";
  $result_all_dogs=$conn->query($sql_all_dogs);
  while ($row_all_dogs=$result_all_dogs->fetch_assoc()) {
    $editDogID=htmlspecialchars($row_all_dogs['dogID'], ENT_QUOTES);
    $editLastName=htmlspecialchars($row_all_dogs['lastName'], ENT_QUOTES);
    $editDogName=htmlspecialchars($row_all_dogs['dogName'], ENT_QUOTES);
    echo "<option value='$editDogID'>$editLastName, $editDogName</option>";
  }
  echo "</select>
  </div>";
  echo "</div>
  <div id='toggleWriteInClientele' style='display:none;'>
  <div class='input-group'>
  <span class='input-group-addon dog'>Dog Name</span>
  <input type='text' class='form-control' name='dogNameWriteIn' maxlength='255' id='addDogName' required>
  </div>
  <div class='input-group'>
  <span class='input-group-addon owner'>Last Name</span>
  <input type='text' class='form-control' name='lastNameWriteIn' maxlength='255' id='addLastName' required>
  </div>
  </div>";
}
?>
