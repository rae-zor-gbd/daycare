<?php
include '../assets/config.php';
if (isset($_POST['id']) AND isset($_POST['date'])) {
  $id=$_POST['id'];
  $date=$_POST['date'];
  $sql_blockoff_info="SELECT lastName, dogName FROM dogs d JOIN owners o USING (ownerID) JOIN reservations_blockoffs b USING (dogID) WHERE dogID='$id' AND blockoffDate='$date'";
  $result_blockoff_info=$conn->query($sql_blockoff_info);
  $row_blockoff_info=$result_blockoff_info->fetch_assoc();
  $lastName=htmlspecialchars($row_blockoff_info['lastName'], ENT_QUOTES);
  $dogName=htmlspecialchars($row_blockoff_info['dogName'], ENT_QUOTES);
  echo "<input type='hidden' class='form-control' name='id' id='deleteID' value='$id' required>
  <input type='hidden' class='form-control' name='blockoff-date' id='deleteBlockoffDate' value='$date' required>
  <div class='input-group'>
  <span class='input-group-addon owner'>Last Name</span>
  <input type='text' class='form-control' name='last-name' id='deleteBlockoffLastName' value='$lastName' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon dog'>Dog Name</span>
  <input type='text' class='form-control' name='dog-name' id='deleteBlockoffName' value='$dogName' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon calendar-day'>Blockoff Date</span>
  <input type='text' class='form-control' name='display-date' id='deleteDisplayDate' value='" . date('l, F j, Y', strtotime($date)) . "' disabled>
  </div>";
}
?>
