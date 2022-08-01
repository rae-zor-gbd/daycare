<?php
include 'config.php';
if (isset($_POST['id']) AND isset($_POST['owner']) AND isset($_POST['status']) AND isset($_POST['daysLeft']) AND isset($_POST['startDate']) AND isset($_POST['expirationDate'])) {
  $packageID=$_POST['id'];
  $ownerID=$_POST['owner'];
  $status=mysqli_real_escape_string($conn, $_POST['status']);
  $daysLeft=$_POST['daysLeft'];
  $startDate=$_POST['startDate'];
  $expirationDate=$_POST['expirationDate'];
  $sql_edit_package="UPDATE owners_packages SET status='$status', daysLeft='$daysLeft', startDate='$startDate', expirationDate='$expirationDate' WHERE ownerPackageID='$packageID'";
  $conn->query($sql_edit_package);
}
?>
