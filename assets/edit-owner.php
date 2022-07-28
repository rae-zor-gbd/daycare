<?php
include 'config.php';
if (isset($_POST['id']) AND isset($_POST['lastName']) AND isset($_POST['primaryOwner']) AND isset($_POST['secondaryOwner']) AND isset($_POST['primaryEmail']) AND isset($_POST['secondaryEmail']) AND isset($_POST['tertiaryEmail'])) {
  $id=$_POST['id'];
  $lastName=mysqli_real_escape_string($conn, $_POST['lastName']);
  $primaryOwner=mysqli_real_escape_string($conn, $_POST['primaryOwner']);
  $secondaryOwner=mysqli_real_escape_string($conn, $_POST['secondaryOwner']);
  $primaryEmail=mysqli_real_escape_string($conn, $_POST['primaryEmail']);
  $secondaryEmail=mysqli_real_escape_string($conn, $_POST['secondaryEmail']);
  $tertiaryEmail=mysqli_real_escape_string($conn, $_POST['tertiaryEmail']);
  $sql_update="UPDATE owners SET lastName='$lastName', primaryOwner='$primaryOwner', secondaryOwner='$secondaryOwner' WHERE ownerID='$id'";
  $conn->query($sql_update);
  $sql_delete_emails="DELETE FROM emails WHERE ownerID='$id'";
  $conn->query($sql_delete_emails);
  if (isset($primaryEmail) AND $primaryEmail!='') {
    $sql_add_primary_email="INSERT INTO emails (ownerID, email) VALUES ('$id', '$primaryEmail')";
    $conn->query($sql_add_primary_email);
  }
  if (isset($secondaryOwner) AND $secondaryOwner!='') {
    $sql_add_secondary_email="INSERT INTO emails (ownerID, email) VALUES ('$id', '$secondaryOwner')";
    $conn->query($sql_add_secondary_email);
  }
  if (isset($tertiaryEmail) AND $tertiaryEmail!='') {
    $sql_add_tertiary_email="INSERT INTO emails (ownerID, email) VALUES ('$id', '$tertiaryEmail')";
    $conn->query($sql_add_tertiary_email);
  }
}
?>
