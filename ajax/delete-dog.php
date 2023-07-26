<?php
include '../assets/config.php';
if (isset($_POST['id'])) {
  $id=$_POST['id'];
  $sql_delete="DELETE FROM dogs WHERE dogID='$id'";
  $conn->query($sql_delete);
}
?>
