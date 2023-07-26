<?php
include '../assets/config.php';
if (isset($_POST['dogID']) AND isset($_POST['dogNotes'])) {
  $dogID=$_POST['dogID'];
  $dogNotes=mysqli_real_escape_string($conn, $_POST['dogNotes']);
  $sql_add_dog_notes="UPDATE dogs SET notes='$dogNotes' WHERE dogID='$dogID'";
  $conn->query($sql_add_dog_notes);
}
?>
