<?php
include '../assets/config.php';
if (isset($_POST['id'])) {
  $id=$_POST['id'];
  $sql_delete="DELETE FROM owners_packages WHERE ownerPackageID='$id'";
  $conn->query($sql_delete);
}
?>
