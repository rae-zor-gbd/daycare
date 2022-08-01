<?php
include 'config.php';
if (isset($_POST['id']) AND isset($_POST['owner'])) {
  $packageID=$_POST['id'];
  $ownerID=$_POST['owner'];
  $sql_package_info="SELECT lastName, primaryOwner, secondaryOwner, packageTitle, status, notes FROM owners_packages op JOIN owners USING (ownerID) JOIN packages p USING (packageID) WHERE ownerPackageID='$packageID'";
  $result_package_info=$conn->query($sql_package_info);
  $row_package_info=$result_package_info->fetch_assoc();
  $addPackageNotesLastName=htmlspecialchars($row_package_info['lastName'], ENT_QUOTES);
  $addPackageNotesPrimaryOwner=htmlspecialchars($row_package_info['primaryOwner'], ENT_QUOTES);
  $addPackageNotesSecondaryOwner=htmlspecialchars($row_package_info['secondaryOwner'], ENT_QUOTES);
  $addPackageNotesTitle=htmlspecialchars($row_package_info['packageTitle'], ENT_QUOTES);
  $addPackageNotesStatus=htmlspecialchars($row_package_info['status'], ENT_QUOTES);
  $addPackageNotesNotes=htmlentities($row_package_info['notes']);
  echo "<input type='hidden' class='form-control' name='id' id='addPackageNotesID' value='$packageID' required>
  <input type='hidden' class='form-control' name='ownerID' id='addPackageNotesForOwnerID' value='$ownerID' required>
  <div class='input-group'>
  <span class='input-group-addon owner'>Last Name</span>
  <input type='text' class='form-control' name='lastName' maxlength='255' id='addPackageNotesLastName' value='$addPackageNotesLastName' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon owner'>Primary Owner</span>
  <input type='text' class='form-control' name='primaryOwner' maxlength='255' id='addPackageNotesPrimaryOwner' value='$addPackageNotesPrimaryOwner' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon owner'>Secondary Owner</span>
  <input type='text' class='form-control' name='secondaryOwner' maxlength='255' id='addPackageNotesSecondaryOwner' value='$addPackageNotesSecondaryOwner' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon days'>Package</span>
  <input type='text' class='form-control' name='packageTitle' maxlength='255' id='addPackageNotesTitle' value='$addPackageNotesTitle' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon info'>Status</span>
  <input type='text' class='form-control' name='packageStatus' maxlength='255' id='addPackageNotesStatus' value='$addPackageNotesStatus' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon notes'>Package Notes</span>
  <textarea class='form-control' name='packageNotes' id='addPackageNotesBox' rows='10'>$addPackageNotesNotes</textarea>
  </div>";
}
?>
