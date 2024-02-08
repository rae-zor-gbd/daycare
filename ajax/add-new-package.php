<?php
include '../assets/config.php';
if (isset($_POST['ownerID']) AND isset($_POST['packageID'])) {
  $ownerID=$_POST['ownerID'];
  $packageID=$_POST['packageID'];
  $sql_package_info="SELECT totalDays, durationDays, durationMonths FROM packages WHERE packageID='$packageID'";
  $result_package_info=$conn->query($sql_package_info);
  $row_package_info=$result_package_info->fetch_assoc();
  if ($row_package_info['totalDays']>0) {
    $daysLeft=$row_package_info['totalDays'];
    $durationDays=$row_package_info['durationDays'];
    $durationMonths=$row_package_info['durationMonths'];
  } else {
    $daysLeft=NULL;
    $durationDays=NULL;
    $durationMonths=NULL;
  }
  if (isset($_POST['startDate']) AND $_POST['startDate']!='') {
    $startDate=date('Y-m-d', strtotime($_POST['startDate']));
    $status='Active';
    if ($durationDays>0 AND $durationMonths>0) {
      $expirationDateDays=date('Y-m-d', strtotime($startDate . ' + ' . $durationDays . ' days'));
      $expirationDateMonths=date('Y-m-d', strtotime($startDate . ' + ' . $durationMonths . ' months'));
      if ($expirationDateMonths>=$expirationDateDays) {
        $expirationDate=$expirationDateMonths;
      } elseif ($expirationDateDays>$expirationDateMonths) {
        $expirationDate=$expirationDateDays;
      }
    } else {
      $expirationDate=NULL;
    }
  } else {
    $startDate=NULL;
    $status='Not Started';
    $expirationDate=NULL;
  }
  $sql_add_package="INSERT INTO owners_packages (ownerID, packageID, status) VALUES ('$ownerID', '$packageID', '$status')";
  $conn->query($sql_add_package);
  $sql_next_package_id="SELECT ownerPackageID FROM owners_packages WHERE ownerID='$ownerID' AND packageID='$packageID' AND status='$status' ORDER BY ownerPackageID DESC LIMIT 1";
  $result_next_package_id=$conn->query($sql_next_package_id);
  $row_next_package_id=$result_next_package_id->fetch_assoc();
  $ownerPackageID=$row_next_package_id['ownerPackageID'];
  if (isset($daysLeft) AND $daysLeft!='') {
    $sql_add_days_left="UPDATE owners_packages SET daysLeft='$daysLeft' WHERE ownerPackageID='$ownerPackageID'";
    $conn->query($sql_add_days_left);
  }
  if (isset($startDate) AND $startDate!='') {
    $sql_add_start_date="UPDATE owners_packages SET startDate='$startDate' WHERE ownerPackageID='$ownerPackageID'";
    $conn->query($sql_add_start_date);
  }
  if (isset($expirationDate) AND $expirationDate!='') {
    $sql_expiration_exception="SELECT expirationException FROM owners WHERE ownerID='$ownerID'";
    $result_expiration_exception=$conn->query($sql_expiration_exception);
    $row_expiration_exception=$result_expiration_exception->fetch_assoc();
    $expirationException=$row_expiration_exception['expirationException'];
    if ($expirationException=='Yes') {
      $expirationDate=NULL;
    }
    $sql_add_expiration_date="UPDATE owners_packages SET expirationDate='$expirationDate' WHERE ownerPackageID='$ownerPackageID'";
    $conn->query($sql_add_expiration_date);
  }
}
?>
