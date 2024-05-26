<?php
include '../assets/config.php';
if (isset($_POST['id']) AND isset($_POST['date'])) {
  $id=$_POST['id'];
  $date=$_POST['date'];
  $sql_delete="DELETE FROM reservations_blockoffs WHERE dogID='$id' AND blockoffDate='$date'";
  $conn->query($sql_delete);
}
?>
