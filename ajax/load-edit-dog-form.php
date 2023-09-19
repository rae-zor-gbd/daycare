<?php
include '../assets/config.php';
if (isset($_POST['id']) AND isset($_POST['owner'])) {
  $id=$_POST['id'];
  $owner=$_POST['owner'];
  $sql_dog_info="SELECT dogName, clientRegistration, daycareContract, vetID FROM dogs WHERE dogID='$id'";
  $result_dog_info=$conn->query($sql_dog_info);
  $row_dog_info=$result_dog_info->fetch_assoc();
  $editDogName=htmlspecialchars($row_dog_info['dogName'], ENT_QUOTES);
  $editClientRegistration=htmlspecialchars($row_dog_info['clientRegistration'], ENT_QUOTES);
  $editDaycareContract=htmlspecialchars($row_dog_info['daycareContract'], ENT_QUOTES);
  $editVet=$row_dog_info['vetID'];
  echo "<input type='hidden' class='form-control' name='id' id='editDogID' value='$id' required>
  <input type='hidden' class='form-control' name='editDogForOwnerID' id='editDogForOwnerID' value='$owner' required>
  <div class='input-group'>
  <span class='input-group-addon dog'>Dog Name</span>
  <input type='text' class='form-control' name='editDogName' maxlength='255' id='editDogName' value='$editDogName' required>
  </div>
  <div class='input-group'>
  <span class='input-group-addon contract'>Client Registration</span>
  <select class='form-control' name='editClientRegistration' id='editClientRegistration' required>
  <option value='' disabled>Select Status</option>
  <option value='Incomplete'";
  if ($editClientRegistration==='Incomplete') {
    echo " selected";
  }
  echo ">Incomplete</option>
  <option value='Completed'";
  if ($editClientRegistration==='Completed') {
    echo " selected";
  }
  echo ">Completed</option>
  </select>
  </div>
  <div class='input-group'>
  <span class='input-group-addon contract'>Daycare Contract</span>
  <select class='form-control' name='editDaycareContract' id='editDaycareContract' required>
  <option value='' disabled>Select Status</option>
  <option value='Incomplete'";
  if ($editDaycareContract==='Incomplete') {
    echo " selected";
  }
  echo ">Incomplete</option>
  <option value='Completed'";
  if ($editDaycareContract==='Completed') {
    echo " selected";
  }
  echo ">Completed</option>
  </select>
  </div>
  <div class='input-group'>
  <span class='input-group-addon vet'>Vet</span>
  <select class='form-control' name='editVet' id='editVet' required>
  <option value='' disabled>Select Vet</option>";
  $sql_all_vets="SELECT vetID, vetName FROM vets ORDER BY vetName";
  $result_all_vets=$conn->query($sql_all_vets);
  while ($row_all_vets=$result_all_vets->fetch_assoc()) {
    $vetID=$row_all_vets['vetID'];
    $vetName=stripslashes(mysqli_real_escape_string($conn, $row_all_vets['vetName']));
    echo "<option value='$vetID'";
    if ($editVet===$vetID) {
      echo " selected";
    }
    echo ">$vetName</option>";
  }
  echo "</select>
  </div>";
  $sql_all_vaccines="SELECT vaccineID, vaccineTitle FROM vaccines ORDER BY vaccineTitle";
  $result_all_vaccines=$conn->query($sql_all_vaccines);
  while ($row_all_vaccines=$result_all_vaccines->fetch_assoc()) {
    $vaccineID=$row_all_vaccines['vaccineID'];
    $vaccineTitle=mysqli_real_escape_string($conn, $row_all_vaccines['vaccineTitle']);
    $sql_all_vaccines_given="SELECT dueDate FROM dogs_vaccines dv JOIN vaccines v USING (vaccineID) WHERE dogID='$id' AND vaccineID='$vaccineID'";
    $result_all_vaccines_given=$conn->query($sql_all_vaccines_given);
    $row_all_vaccines_given=$result_all_vaccines_given->fetch_assoc();
    if(isset($row_all_vaccines_given['dueDate']) AND $row_all_vaccines_given['dueDate']!='') {
      $dueDate=$row_all_vaccines_given['dueDate'];
    } else {
      $dueDate='';
    }
    echo "<div class='input-group'>
    <span class='input-group-addon vaccine'>$vaccineTitle</span>
    <input type='date' class='form-control' name='vaccine$vaccineID' id='editVaccine$vaccineID'";
    if (isset($dueDate) AND $dueDate!='') {
      echo " value='$dueDate'";
    }
    echo ">
    </div>";
  }
}
?>