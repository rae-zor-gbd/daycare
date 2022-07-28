<?php
include 'config.php';
if (isset($_POST['id'])) {
  $id=$_POST['id'];
  $sql_owner_info="SELECT lastName, primaryOwner, secondaryOwner FROM owners WHERE ownerID='$id'";
  $result_owner_info=$conn->query($sql_owner_info);
  $row_owner_info=$result_owner_info->fetch_assoc();
  $editLastName=htmlspecialchars($row_owner_info['lastName'], ENT_QUOTES);
  $editPrimaryOwner=htmlspecialchars($row_owner_info['primaryOwner'], ENT_QUOTES);
  $editSecondaryOwner=htmlspecialchars($row_owner_info['secondaryOwner'], ENT_QUOTES);
  $sql_owner_emails="SELECT email FROM emails WHERE ownerID='$id'";
  $result_owner_emails=$conn->query($sql_owner_emails);
  $ownerEmails=array();
  while ($row_owner_emails=$result_owner_emails->fetch_assoc()) {
    array_push($ownerEmails, htmlspecialchars($row_owner_emails['email'], ENT_QUOTES));
  }
  if (isset($ownerEmails[0]) AND $ownerEmails[0]!='') {
    $primaryOwner=$ownerEmails[0];
  } else {
    $primaryOwner='';
  }
  if (isset($ownerEmails[1]) AND $ownerEmails[1]!='') {
    $secondaryOwner=$ownerEmails[1];
  } else {
    $secondaryOwner='';
  }
  if (isset($ownerEmails[2]) AND $ownerEmails[2]!='') {
    $tertiaryOwner=$ownerEmails[2];
  } else {
    $tertiaryOwner='';
  }
  echo "<input type='hidden' class='form-control' name='id' id='editID' value='$id' required>
  <div class='input-group'>
  <span class='input-group-addon owner'>Last Name</span>
  <input type='text' class='form-control' name='lastName' maxlength='255' id='editLastName' value='$editLastName' required>
  </div>
  <div class='input-group'>
  <span class='input-group-addon owner'>Primary Owner</span>
  <input type='text' class='form-control' name='primaryOwner' maxlength='255' id='editPrimaryOwner' value='$editPrimaryOwner' required>
  </div>
  <div class='input-group'>
  <span class='input-group-addon owner'>Secondary Owner</span>
  <input type='text' class='form-control' name='secondaryOwner' maxlength='255' id='editSecondaryOwner' value='$editSecondaryOwner'>
  </div>
  <div class='input-group'>
  <span class='input-group-addon email'>Primary Email</span>
  <input type='email' class='form-control' name='primaryEmail' maxlength='255' id='editPrimaryEmail' value='$primaryOwner'>
  </div>
  <div class='input-group'>
  <span class='input-group-addon email'>Secondary Email</span>
  <input type='email' class='form-control' name='secondaryEmail' maxlength='255' id='editSecondaryEmail' value='$secondaryOwner'>
  </div>
  <div class='input-group'>
  <span class='input-group-addon email'>Tertiary Email</span>
  <input type='email' class='form-control' name='tertiaryEmail' maxlength='255' id='editTertiaryEmail' value='$tertiaryOwner'>
  </div>";
}
?>
