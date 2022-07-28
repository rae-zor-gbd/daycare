<?php
include 'config.php';
if (isset($_POST['id'])) {
  $id=$_POST['id'];
  echo "<input type='hidden' class='form-control' name='id' id='addToOwnerID' value='$id' required>
  <div class='input-group'>
  <span class='input-group-addon dog'>Dog Name</span>
  <input type='text' class='form-control' name='dogName' maxlength='255' id='addDogName' required>
  </div>
  <div class='input-group'>
  <span class='input-group-addon contract'>Daycare Contract</span>
  <select class='form-control' id='addDaycareContract' name='daycareContract' required>
  <option value='' disabled selected>Select Status</option>
  <option value='Incomplete'>Incomplete</option>
  <option value='Completed'>Completed</option>
  </select>
  </div>";
  $sql_vaccines="SELECT vaccineID, vaccineTitle FROM vaccines ORDER BY vaccineTitle";
  $result_vaccines=$conn->query($sql_vaccines);
  while ($row_vaccines=$result_vaccines->fetch_assoc()) {
    $vaccineID=$row_vaccines['vaccineID'];
    $vaccineTitle=mysqli_real_escape_string($conn, $row_vaccines['vaccineTitle']);
    echo "<div class='input-group'>
    <span class='input-group-addon vaccine'>$vaccineTitle</span>
    <input type='date' class='form-control' name='vaccine$vaccineID' id='addVaccine$vaccineID'>
    </div>";
  }
}
?>
