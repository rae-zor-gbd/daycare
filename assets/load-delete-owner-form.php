<?php
include 'config.php';
if (isset($_POST['id'])) {
  $id=$_POST['id'];
  $sql_owner_info="SELECT lastName, primaryOwner, secondaryOwner FROM owners WHERE ownerID='$id'";
  $result_owner_info=$conn->query($sql_owner_info);
  $row_owner_info=$result_owner_info->fetch_assoc();
  $lastName=htmlspecialchars($row_owner_info['lastName'], ENT_QUOTES);
  $primaryOwner=htmlspecialchars($row_owner_info['primaryOwner'], ENT_QUOTES);
  $secondaryOwner=htmlspecialchars($row_owner_info['secondaryOwner'], ENT_QUOTES);
  echo "<input type='hidden' class='form-control' name='id' id='deleteID' value='$id' required>
  <div class='input-group'>
  <span class='input-group-addon owner'>Last Name</span>
  <input type='text' class='form-control' name='last-name' id='deleteLastName' value='$lastName' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon owner'>Primary Owner</span>
  <input type='text' class='form-control' name='primary-owner' id='deletePrimaryOwner' value='$primaryOwner' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon owner'>Secondary Owner</span>
  <input type='text' class='form-control' name='secondary-owner' id='deleteSecondaryOwner' value='$secondaryOwner' disabled>
  </div>";
}
?>
