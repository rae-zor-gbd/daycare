<?php
include '../assets/config.php';
if (isset($_POST['id'])) {
  $id=$_POST['id'];
  $sql_dog_info="SELECT lastName, dogName FROM dogs d JOIN owners o USING (ownerID) WHERE dogID='$id'";
  $result_dog_info=$conn->query($sql_dog_info);
  $row_dog_info=$result_dog_info->fetch_assoc();
  $lastName=htmlspecialchars($row_dog_info['lastName'], ENT_QUOTES);
  $dogName=htmlspecialchars($row_dog_info['dogName'], ENT_QUOTES);
  echo "<input type='hidden' class='form-control' name='id' id='deleteID' value='$id' required>
  <div class='input-group'>
  <span class='input-group-addon owner'>Last Name</span>
  <input type='text' class='form-control' name='last-name' id='deleteDogLastName' value='$lastName' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon dog'>Dog Name</span>
  <input type='text' class='form-control' name='dog-name' id='deleteDogName' value='$dogName' disabled>
  </div>";
}
?>
