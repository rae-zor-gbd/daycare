<?php
include '../assets/config.php';
if (isset($_POST['id'])) {
  $id=$_POST['id'];
  echo "<input type='hidden' class='form-control' name='id' id='addToOwnerID' value='$id' required>
  <div class='input-group'>
  <span class='input-group-addon dog'>Dog Name</span>
  <input type='text' class='form-control' name='dogName' maxlength='255' id='addDogName' required>
  </div>
  <div class='input-group'>
  <span class='input-group-addon contract'>Client Registration</span>
  <select class='form-control' id='addClientRegistration' name='clientRegistration' required>
  <option value='' disabled selected>Select Status</option>
  <option value='Incomplete'>Incomplete</option>
  <option value='Complete'>Complete</option>
  <option value='Exempt'>Exempt</option>
  </select>
  </div>
  <div class='input-group'>
  <span class='input-group-addon contract'>Daycare Contract</span>
  <select class='form-control' id='addDaycareContract' name='daycareContract' required>
  <option value='' disabled selected>Select Status</option>
  <option value='Incomplete'>Incomplete</option>
  <option value='Complete'>Complete</option>
  <option value='Exempt'>Exempt</option>
  </select>
  </div>
  <div class='input-group'>
  <span class='input-group-addon vet'>Vet</span>
  <select class='form-control' name='vet' id='addVet' required>
  <option value='' selected disabled>Select Vet</option>";
  $sql_all_vets="SELECT vetID, vetName FROM vets ORDER BY vetName";
  $result_all_vets=$conn->query($sql_all_vets);
  while ($row_all_vets=$result_all_vets->fetch_assoc()) {
    $vetID=$row_all_vets['vetID'];
    $vetName=stripslashes(mysqli_real_escape_string($conn, $row_all_vets['vetName']));
    echo "<option value='$vetID'>$vetName</option>";
  }
  echo "</select>
  </div>";
  $sql_vaccines="SELECT vaccineID, vaccineTitle, maxMonthsAhead FROM vaccines ORDER BY vaccineTitle";
  $result_vaccines=$conn->query($sql_vaccines);
  while ($row_vaccines=$result_vaccines->fetch_assoc()) {
    $vaccineID=$row_vaccines['vaccineID'];
    $vaccineTitle=mysqli_real_escape_string($conn, $row_vaccines['vaccineTitle']);
    $maxMonthsAhead=$row_vaccines['maxMonthsAhead'];
    $maxDueDate=date('Y-m-d', strtotime('today + ' . $maxMonthsAhead . ' months'));
    echo "<div class='input-group'>
    <span class='input-group-addon vaccine'>$vaccineTitle</span>
    <input type='date' class='form-control' name='vaccine$vaccineID' id='addVaccine$vaccineID' max='$maxDueDate'>
    </div>";
  }
  echo "<div class='row'>
  <div class='col-sm-1'></div>
  <div class='col-sm-2'>
  <div class='input-group'>
  <input type='checkbox' id='addMondays' name='addMondays' value='Yes'>
  <label for='addMondays'>Mon</label>
  </div>
  </div>
  <div class='col-sm-2'>
  <div class='input-group'>
  <input type='checkbox' id='addTuesdays' name='addTuesdays' value='Yes'>
  <label for='addTuesdays'>Tue</label>
  </div>
  </div>
  <div class='col-sm-2'>
  <div class='input-group'>
  <input type='checkbox' id='addWednesdays' name='addWednesdays' value='Yes'>
  <label for='addWednesdays'>Wed</label>
  </div>
  </div>
  <div class='col-sm-2'>
  <div class='input-group'>
  <input type='checkbox' id='addThursdays' name='addThursdays' value='Yes'>
  <label for='addThursdays'>Thu</label>
  </div>
  </div>
  <div class='col-sm-2'>
  <div class='input-group'>
  <input type='checkbox' id='addFridays' name='addFridays' value='Yes'>
  <label for='addFridays'>Fri</label>
  </div>
  </div>
  <div class='col-sm-1'></div>
  </div>";
}
?>
