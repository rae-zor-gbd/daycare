<?php
include '../assets/config.php';
if (isset($_POST['id'])) {
  $id=$_POST['id'];
  $sql_complete_contract="UPDATE dogs SET daycareContract='Complete' WHERE dogID='$id'";
  $conn->query($sql_complete_contract);
}
?>
