<?php
include 'config.php';
if (isset($_POST['id'])) {
  $id=$_POST['id'];
  $sql_delete="DELETE FROM owners WHERE ownerID='$id'";
  $conn->query($sql_delete);
}
?>
