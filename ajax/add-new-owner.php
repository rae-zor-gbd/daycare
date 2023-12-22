<?php
include '../assets/config.php';
if (isset($_POST['lastName']) AND isset($_POST['primaryOwner']) AND isset($_POST['secondaryOwner']) AND isset($_POST['primaryCell']) AND isset($_POST['secondaryCell']) AND isset($_POST['homePhone']) AND isset($_POST['primaryEmail']) AND isset($_POST['secondaryEmail']) AND isset($_POST['tertiaryEmail'])) {
  $lastName=mysqli_real_escape_string($conn, trim($_POST['lastName']));
  $primaryOwner=mysqli_real_escape_string($conn, trim($_POST['primaryOwner']));
  $secondaryOwner=mysqli_real_escape_string($conn, trim($_POST['secondaryOwner']));
  $primaryCell=trim($_POST['primaryCell']);
  $secondaryCell=trim($_POST['secondaryCell']);
  $homePhone=trim($_POST['homePhone']);
  $primaryEmail=mysqli_real_escape_string($conn, trim($_POST['primaryEmail']));
  $secondaryEmail=mysqli_real_escape_string($conn, trim($_POST['secondaryEmail']));
  $tertiaryEmail=mysqli_real_escape_string($conn, trim($_POST['tertiaryEmail']));
  $sql_next_owner_id="SELECT AUTO_INCREMENT AS nextOwnerID FROM information_schema.TABLES WHERE TABLE_SCHEMA='daycare' AND TABLE_NAME='owners'";
  $result_next_owner_id=$conn->query($sql_next_owner_id);
  $row_next_owner_id=$result_next_owner_id->fetch_assoc();
  $ownerID=$row_next_owner_id['nextOwnerID'];
  $sql_add_owner="INSERT INTO owners (ownerID, lastName, primaryOwner, secondaryOwner, primaryCell, secondaryCell, homePhone) VALUES ('$ownerID', '$lastName', '$primaryOwner', '$secondaryOwner', '$primaryCell', '$secondaryCell', '$homePhone')";
  $conn->query($sql_add_owner);
  if (isset($primaryEmail) AND $primaryEmail!='') {
    $sql_add_primary_email="INSERT INTO emails (ownerID, email) VALUES ('$ownerID', '$primaryEmail')";
    $conn->query($sql_add_primary_email);
  }
  if (isset($secondaryEmail) AND $secondaryEmail!='') {
    $sql_add_secondary_email="INSERT INTO emails (ownerID, email) VALUES ('$ownerID', '$secondaryEmail')";
    $conn->query($sql_add_secondary_email);
  }
  if (isset($tertiaryEmail) AND $tertiaryEmail!='') {
    $sql_add_tertiary_email="INSERT INTO emails (ownerID, email) VALUES ('$ownerID', '$tertiaryEmail')";
    $conn->query($sql_add_tertiary_email);
  }
}
?>
