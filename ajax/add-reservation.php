<?php
include '../assets/config.php';
if (isset($_POST['dogID']) AND isset($_POST['date'])) {
  $dogID=$_POST['dogID'];
  $date=$_POST['date'];
  $sql_add_reservation="INSERT INTO reservations (dogID, reservationDate) VALUES ('$dogID', '$date')";
  $conn->query($sql_add_reservation);
}
if (isset($_POST['dogName']) AND isset($_POST['lastName']) AND isset($_POST['date'])) {
  $dogName=mysqli_real_escape_string($conn, trim($_POST['dogName']));
  $lastName=mysqli_real_escape_string($conn, trim($_POST['lastName']));
  $date=$_POST['date'];
  $sql_add_reservation_write_in="INSERT INTO reservations_write_ins (reservationDate, lastName, dogName) VALUES ('$date', '$lastName', '$dogName')";
  $conn->query($sql_add_reservation_write_in);
}
?>
