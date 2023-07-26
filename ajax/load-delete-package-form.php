<?php
include '../assets/config.php';
if (isset($_POST['id'])) {
  $id=$_POST['id'];
  $sql_package_info="SELECT lastName, primaryOwner, secondaryOwner, packageTitle, daysLeft, startDate, expirationDate FROM owners o JOIN owners_packages op USING (ownerID) JOIN packages p USING (packageID) WHERE ownerPackageID='$id'";
  $result_package_info=$conn->query($sql_package_info);
  $row_package_info=$result_package_info->fetch_assoc();
  $lastName=htmlspecialchars($row_package_info['lastName'], ENT_QUOTES);
  $primaryOwner=htmlspecialchars($row_package_info['primaryOwner'], ENT_QUOTES);
  $secondaryOwner=htmlspecialchars($row_package_info['secondaryOwner'], ENT_QUOTES);
  $packageTitle=htmlspecialchars($row_package_info['packageTitle'], ENT_QUOTES);
  $daysLeft=$row_package_info['daysLeft'];
  $startDate=$row_package_info['startDate'];
  $expirationDate=$row_package_info['expirationDate'];
  echo "<input type='hidden' class='form-control' name='id' id='deletePackageID' value='$id' required>
  <div class='input-group'>
  <span class='input-group-addon owner'>Last Name</span>
  <input type='text' class='form-control' name='last-name' id='deletePackageLastName' value='$lastName' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon owner'>Primary Owner</span>
  <input type='text' class='form-control' name='primary-owner' id='deletePackagePrimaryOwner' value='$primaryOwner' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon owner'>Secondary Owner</span>
  <input type='text' class='form-control' name='secondary-owner' id='deletePackageSecondaryOwner' value='$secondaryOwner' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon days'>Package</span>
  <input type='text' class='form-control' name='package-title' id='deletePackageTitle' value='$packageTitle' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon day'>Days Left</span>
  <input type='text' class='form-control' name='days-left' id='deletePackageDaysLeft' value='$daysLeft' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon clock'>Start Date</span>
  <input type='date' class='form-control' name='start-date' id='deletePackageStartDate' value='$startDate' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon clock'>Expiration Date</span>
  <input type='date' class='form-control' name='expiration-date' id='deletePackageExpirationDate' value='$expirationDate' disabled>
  </div>";
}
?>
