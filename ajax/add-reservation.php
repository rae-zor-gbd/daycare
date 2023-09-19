<?php
include '../assets/config.php';
if (isset($_POST['dogID']) AND isset($_POST['date'])) {
  $dogID=$_POST['dogID'];
  $date=$_POST['date'];
  $sql_add_reservation="INSERT INTO reservations (dogID, reservationDate) VALUES ('$dogID', '$date')";
  $conn->query($sql_add_reservation);
}
?>
