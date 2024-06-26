<?php
include '../assets/config.php';
if (isset($_POST['id']) AND isset($_POST['owner']) AND isset($_POST['currentStatus']) AND isset($_POST['status'])) {
  $packageID=$_POST['id'];
  $ownerID=$_POST['owner'];
  $currentStatus=mysqli_real_escape_string($conn, $_POST['currentStatus']);
  $status=mysqli_real_escape_string($conn, $_POST['status']);
  $daysLeft=$_POST['daysLeft'];
  $startDate=date('Y-m-d', strtotime($_POST['startDate']));
  $expirationDate=date('Y-m-d', strtotime($_POST['expirationDate']));
  $sql_expiration_exception="SELECT expirationException FROM owners WHERE ownerID='$ownerID'";
  $result_expiration_exception=$conn->query($sql_expiration_exception);
  $row_expiration_exception=$result_expiration_exception->fetch_assoc();
  $expirationException=$row_expiration_exception['expirationException'];
  $sql_edit_package="UPDATE owners_packages SET status='$status' WHERE ownerPackageID='$packageID'";
  $conn->query($sql_edit_package);
  $sql_package_info="SELECT totalDays, durationDays, durationMonths FROM packages p JOIN owners_packages op USING (packageID) WHERE ownerPackageID='$packageID'";
  $result_package_info=$conn->query($sql_package_info);
  $row_package_info=$result_package_info->fetch_assoc();
  if ($currentStatus==='Not Started' AND $status==='Active') {
    if ($row_package_info['totalDays']>0) {
      $daysLeft=$row_package_info['totalDays'];
      if ($row_package_info['durationDays']>0 AND $row_package_info['durationMonths']>0) {
        $durationDays=$row_package_info['durationDays'];
        $durationMonths=$row_package_info['durationMonths'];
      } else {
        $durationDays=NULL;
        $durationMonths=NULL;
      }
    } else {
      $daysLeft=NULL;
      $durationDays=NULL;
      $durationMonths=NULL;
    }
    if (isset($startDate) AND $startDate!='' AND $durationDays>0 AND $durationMonths>0) {
      $startDate=$_POST['startDate'];
      if ($expirationException=='Yes' OR $durationDays===0 OR $durationDays===NULL OR $durationMonths===0 OR $durationMonths===NULL) {
        $expirationDate=NULL;
      } else {
        if ($currentStatus==='Not Started' AND $status==='Active') {
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
        }
      }
    } else {
      $startDate=NULL;
      $expirationDate=NULL;
    }
    $sql_edit_package_start_expiration="UPDATE owners_packages SET startDate='$startDate', expirationDate='$expirationDate' WHERE ownerPackageID='$packageID'";
    $conn->query($sql_edit_package_start_expiration);
  }
  if (isset($daysLeft) AND $daysLeft!='') {
    $sql_edit_package_days="UPDATE owners_packages SET daysLeft='$daysLeft' WHERE ownerPackageID='$packageID'";
    $conn->query($sql_edit_package_days);
  }
  if (isset($_POST['startDate']) AND $_POST['startDate']!='') {
    $sql_edit_package_start="UPDATE owners_packages SET startDate='$startDate' WHERE ownerPackageID='$packageID'";
    $conn->query($sql_edit_package_start);
  }
  if ($row_package_info['durationDays']==0 AND $row_package_info['durationMonths']==0) {
    $expirationDate=NULL;
  }
  $sql_edit_package_expiration="UPDATE owners_packages SET expirationDate='$expirationDate' WHERE ownerPackageID='$packageID'";
  $conn->query($sql_edit_package_expiration);
}
?>
