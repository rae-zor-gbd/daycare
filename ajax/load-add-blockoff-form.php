<?php
include '../assets/config.php';
if (isset($_POST['id'])) {
  $dogID=$_POST['id'];
  $today=date('Y-m-d');
  $sql_blockoff_info="SELECT lastName, dogName, o.ownerID FROM dogs d JOIN owners o USING (ownerID) WHERE dogID='$dogID'";
  $result_blockoff_info=$conn->query($sql_blockoff_info);
  $row_blockoff_info=$result_blockoff_info->fetch_assoc();
  $editLastName=htmlspecialchars($row_blockoff_info['lastName'], ENT_QUOTES);
  $editDogName=htmlspecialchars($row_blockoff_info['dogName'], ENT_QUOTES);
  $ownerID=$row_blockoff_info['ownerID'];
  echo "<input type='hidden' class='form-control' name='id' id='addBlockoffID' value='$dogID' required>
  <input type='hidden' class='form-control' name='ownerID' id='addBlockoffOwnerID' value='$ownerID' required>
  <div class='input-group'>
  <span class='input-group-addon owner'>Last Name</span>
  <input type='text' class='form-control' name='lastName' id='addBlockoffLastName' value='$editLastName' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon dog'>Dog Name</span>
  <input type='text' class='form-control' name='dogName' id='addBlockoffName' value='$editDogName' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon calendar-day'>Blockoff Date</span>
  <input type='date' class='form-control' name='blockoffDate' id='addBlockoffDate' min='$today' required>
  </div>";
}
?>
