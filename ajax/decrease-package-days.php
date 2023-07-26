<?php
include '../assets/config.php';
if (isset($_POST['id'])) {
  $id=$_POST['id'];
  $sql_decrease="UPDATE owners_packages SET daysLeft=daysLeft-1 WHERE ownerPackageID='$id'";
  $conn->query($sql_decrease);
  $sql_package_info="SELECT daysLeft FROM owners_packages WHERE ownerPackageID='$id'";
  $result_package_info=$conn->query($sql_package_info);
  $row_package_info=$result_package_info->fetch_assoc();
  $daysLeft=$row_package_info['daysLeft'];
  if ($daysLeft==0) {
    $sql_out_of_days="UPDATE owners_packages SET status='Out of Days' WHERE ownerPackageID='$id'";
    $conn->query($sql_out_of_days);
  }
}
?>
