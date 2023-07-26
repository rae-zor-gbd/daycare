<?php
include '../assets/config.php';
if (isset($_POST['id'])) {
  $id=$_POST['id'];
  echo "<input type='hidden' class='form-control' name='id' id='addPackageToOwnerID' value='$id' required>
  <div class='input-group'>
  <span class='input-group-addon days'>Package</span>
  <select class='form-control' id='addDaycarePackage' name='package' required>
  <option value='' disabled selected>Select Package</option>";
  $sql_all_packages="SELECT packageID, packageTitle FROM packages ORDER BY sortOrder, packageTitle";
  $result_all_packages=$conn->query($sql_all_packages);
  while ($row_all_packages=$result_all_packages->fetch_assoc()) {
    $packageID=$row_all_packages['packageID'];
    $packageTitle=mysqli_real_escape_string($conn, $row_all_packages['packageTitle']);
    echo "<option value='$packageID'>$packageTitle</option>";
  }
  echo "</select>
  </div>
  <div class='input-group'>
  <span class='input-group-addon day'>Start Date</span>
  <input type='date' class='form-control' name='startDate' maxlength='255' id='addPackageStartDate'>
  </div>";
}
?>
