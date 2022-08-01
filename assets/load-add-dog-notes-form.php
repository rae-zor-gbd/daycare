<?php
include 'config.php';
if (isset($_POST['id']) AND isset($_POST['owner'])) {
  $dogID=$_POST['id'];
  $ownerID=$_POST['owner'];
  $sql_dog_info="SELECT lastName, dogName, notes FROM dogs d JOIN owners o USING (ownerID) WHERE dogID='$dogID'";
  $result_dog_info=$conn->query($sql_dog_info);
  $row_dog_info=$result_dog_info->fetch_assoc();
  $editLastName=htmlspecialchars($row_dog_info['lastName'], ENT_QUOTES);
  $editDogName=htmlspecialchars($row_dog_info['dogName'], ENT_QUOTES);
  $editDogNotes=htmlentities($row_dog_info['notes']);
  echo "<input type='hidden' class='form-control' name='id' id='addDogNotesID' value='$dogID' required>
  <input type='hidden' class='form-control' name='ownerID' id='addDogNotesOwnerID' value='$ownerID' required>
  <div class='input-group'>
  <span class='input-group-addon owner'>Last Name</span>
  <input type='text' class='form-control' name='lastName' maxlength='255' id='addDogNotesLastName' value='$editLastName' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon dog'>Dog Name</span>
  <input type='text' class='form-control' name='dogName' maxlength='255' id='addDogNotesName' value='$editDogName' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon notes'>Dog Notes</span>
  <textarea class='form-control' name='dogNotes' id='addDogNotesBox' rows='10'>$editDogNotes</textarea>
  </div>";
}
?>
