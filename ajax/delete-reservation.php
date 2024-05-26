<?php
include '../assets/config.php';
if (isset($_POST['id']) AND isset($_POST['date'])) {
  $id=$_POST['id'];
  $date=$_POST['date'];
  $sql_delete="DELETE FROM reservations WHERE dogID='$id' AND reservationDate='$date'";
  $conn->query($sql_delete);
}
if (isset($_POST['id']) AND isset($_POST['date']) AND isset($_POST['type']) AND $_POST['type']=='writeIn') {
  $id=$_POST['id'];
  $date=$_POST['date'];
  $type=$_POST['type'];
  $sql_delete="DELETE FROM reservations_write_ins WHERE writeInID='$id' AND reservationDate='$date'";
  $conn->query($sql_delete);
}
?>
