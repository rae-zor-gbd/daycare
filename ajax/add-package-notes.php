<?php
include '../assets/config.php';
if (isset($_POST['packageID']) AND isset($_POST['packageNotes'])) {
  $packageID=$_POST['packageID'];
  $packageNotes=mysqli_real_escape_string($conn, trim($_POST['packageNotes']));
  $sql_add_package_notes="UPDATE owners_packages SET notes='$packageNotes' WHERE ownerPackageID='$packageID'";
  $conn->query($sql_add_package_notes);
}
?>
