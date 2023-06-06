<?php
include 'config.php';
if (isset($_POST['ownerID']) AND isset($_POST['packageID'])) {
  $ownerID=$_POST['ownerID'];
  $packageID=$_POST['packageID'];
  $sql_next_package_id="SELECT AUTO_INCREMENT AS nextPackageID FROM information_schema.TABLES WHERE TABLE_SCHEMA='daycare' AND TABLE_NAME='owners_packages'";
  $result_next_package_id=$conn->query($sql_next_package_id);
  $row_next_package_id=$result_next_package_id->fetch_assoc();
  $ownerPackageID=$row_next_package_id['nextPackageID'];
  $sql_package_info="SELECT totalDays, duration FROM packages WHERE packageID='$packageID'";
  $result_package_info=$conn->query($sql_package_info);
  $row_package_info=$result_package_info->fetch_assoc();
  if ($row_package_info['totalDays']>0) {
    $daysLeft=$row_package_info['totalDays'];
    $duration=$row_package_info['duration'];
  } else {
    $daysLeft=NULL;
    $duration=NULL;
  }
  if (isset($_POST['startDate']) AND $_POST['startDate']!='') {
    $startDate=date('Y-m-d', strtotime($_POST['startDate']));
    $status='Active';
    if ($duration>0) {
      $expirationDate=date('Y-m-d', strtotime($startDate . ' + ' . $duration . ' days'));
    } else {
      $expirationDate=NULL;
    }
  } else {
    $startDate=NULL;
    $status='Not Started';
    $expirationDate=NULL;
  }
  $sql_add_package="INSERT INTO owners_packages (ownerPackageID, ownerID, packageID, status) VALUES ('$ownerPackageID', '$ownerID', '$packageID', '$status')";
  $conn->query($sql_add_package);
  if (isset($daysLeft) AND $daysLeft!='') {
    $sql_add_days_left="UPDATE owners_packages SET daysLeft='$daysLeft' WHERE ownerPackageID='$ownerPackageID'";
    $conn->query($sql_add_days_left);
  }
  if (isset($startDate) AND $startDate!='') {
    $sql_add_start_date="UPDATE owners_packages SET startDate='$startDate' WHERE ownerPackageID='$ownerPackageID'";
    $conn->query($sql_add_start_date);
  }
  if (isset($expirationDate) AND $expirationDate!='') {
    $sql_add_expiration_date="UPDATE owners_packages SET expirationDate='$expirationDate' WHERE ownerPackageID='$ownerPackageID'";
    $conn->query($sql_add_expiration_date);
  }
}
?>
