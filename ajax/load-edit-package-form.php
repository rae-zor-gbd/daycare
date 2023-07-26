<?php
include '../assets/config.php';
if (isset($_POST['id']) AND isset($_POST['owner'])) {
  $id=$_POST['id'];
  $owner=$_POST['owner'];
  $sql_package_info="SELECT lastName, primaryOwner, secondaryOwner, packageTitle, status, daysLeft, startDate, expirationDate FROM owners_packages op JOIN owners o USING (ownerID) JOIN packages p USING (packageID) WHERE ownerPackageID='$id'";
  $result_package_info=$conn->query($sql_package_info);
  $row_package_info=$result_package_info->fetch_assoc();
  $editPackageLastName=htmlspecialchars($row_package_info['lastName'], ENT_QUOTES);
  $editPackagePrimaryOwner=htmlspecialchars($row_package_info['primaryOwner'], ENT_QUOTES);
  $editPackageSecondaryOwner=htmlspecialchars($row_package_info['secondaryOwner'], ENT_QUOTES);
  $editPackageTitle=htmlspecialchars($row_package_info['packageTitle'], ENT_QUOTES);
  $editPackageStatus=htmlspecialchars($row_package_info['status'], ENT_QUOTES);
  $editPackageDaysLeft=$row_package_info['daysLeft'];
  $editPackageStartDate=$row_package_info['startDate'];
  $editPackageExpirationDate=$row_package_info['expirationDate'];
  echo "<input type='hidden' class='form-control' name='id' id='editPackageID' value='$id' required>
  <input type='hidden' class='form-control' name='current-status' id='editPackageCurrentStatus' value='$editPackageStatus' required>
  <input type='hidden' class='form-control' name='owner' id='editPackageForOwnerID' value='$owner' required>
  <div class='input-group'>
  <span class='input-group-addon owner'>Last Name</span>
  <input type='text' class='form-control' name='last-name' maxlength='255' id='editPackageLastName' value='$editPackageLastName' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon owner'>Primary Owner</span>
  <input type='text' class='form-control' name='primary-owner' maxlength='255' id='editPackagePrimaryOwner' value='$editPackagePrimaryOwner' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon owner'>Secondary Owner</span>
  <input type='text' class='form-control' name='secondary-owner' maxlength='255' id='editPackageSecondaryOwner' value='$editPackageSecondaryOwner' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon days'>Package</span>
  <input type='text' class='form-control' name='package-title' maxlength='255' id='editPackageTitle' value='$editPackageTitle' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon info'>Status</span>
  <select class='form-control' name='status' id='editPackageStatus' required>
  <option value='' disabled>Select Status</option>
  <option value='Active'";
  if ($editPackageStatus==='Active') {
    echo " selected";
  }
  echo ">Active</option>
  <option value='Not Started'";
  if ($editPackageStatus==='Not Started') {
    echo " selected";
  }
  echo ">Not Started</option>
  <option value='Expired'";
  if ($editPackageStatus==='Expired') {
    echo " selected";
  }
  echo ">Expired</option>
  <option value='Out of Days'";
  if ($editPackageStatus==='Out of Days') {
    echo " selected";
  }
  echo ">Out of Days</option>
  </select>
  </div>
  <div class='input-group'>
  <span class='input-group-addon days'>Days Left</span>
  <input type='number' class='form-control' name='days-left' min='0' id='editPackageDaysLeft' value='$editPackageDaysLeft'>
  </div>
  <div class='input-group'>
  <span class='input-group-addon clock'>Start Date</span>
  <input type='date' class='form-control' name='start-date' id='editPackageStartDate' value='$editPackageStartDate'>
  </div>
  <div class='input-group'>
  <span class='input-group-addon clock'>Expiration Date</span>
  <input type='date' class='form-control' name='expiration-date' id='editPackageExpirationDate' value='$editPackageExpirationDate'>
  </div>";
}
?>
