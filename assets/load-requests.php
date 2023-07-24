<?php
include 'config.php';
if (isset($_POST['vetID']) AND $_POST['vetID']!='') {
  $vetID=$_POST['vetID'];
  $sql_requests="SELECT ownerID, lastName, primaryOwner, secondaryOwner, dogID, dogName, primaryCell, secondaryCell, homePhone, notes FROM (SELECT o.ownerID, lastName, primaryOwner, secondaryOwner, d.dogID, dogName, primaryCell, secondaryCell, homePhone, notes FROM dogs d JOIN dogs_vaccines dv USING (dogID) JOIN vaccines vx USING (vaccineID) JOIN owners o USING (ownerID) JOIN vets v USING (vetID) WHERE vetID='$vetID' AND requirementStatus='Required' AND vaccineTitle!='Fecal' AND vaccineTitle!='Flu' AND dueDate<=DATE_ADD(CURDATE(), INTERVAL (SELECT followUpDueIn FROM follow_ups WHERE service='Daycare') DAY) GROUP BY o.ownerID, lastName, primaryOwner, secondaryOwner, d.dogID, dogName, primaryCell, secondaryCell, homePhone, notes UNION SELECT o.ownerID, lastName, primaryOwner, secondaryOwner, d.dogID, dogName, primaryCell, secondaryCell, homePhone, notes FROM dogs d JOIN dogs_vaccines dv USING (dogID) JOIN vaccines vx USING (vaccineID) JOIN owners o USING (ownerID) JOIN vets v USING (vetID) WHERE vetID='$vetID' AND vaccineTitle='Fecal' AND dueDate<=DATE_ADD(CURDATE(), INTERVAL (SELECT followUpDueIn FROM follow_ups WHERE service='Daycare') DAY) AND ownerID NOT IN (SELECT ownerID FROM dogs d JOIN dogs_vaccines dv USING (dogID) JOIN vaccines v USING (vaccineID) JOIN owners o USING (ownerID) WHERE vaccineTitle='Fecal' AND dueDate>=DATE_ADD(NOW(), INTERVAL (SELECT followUpDueIn FROM follow_ups WHERE service='Daycare') DAY) GROUP BY ownerID) GROUP BY o.ownerID, lastName, primaryOwner, secondaryOwner, d.dogID, dogName, primaryCell, secondaryCell, homePhone, notes UNION SELECT o.ownerID, lastName, primaryOwner, secondaryOwner, d.dogID, dogName, primaryCell, secondaryCell, homePhone, notes FROM dogs d JOIN dogs_vaccines dv USING (dogID) JOIN vaccines vx USING (vaccineID) JOIN owners o USING (ownerID) JOIN vets v USING (vetID) WHERE vetID='$vetID' AND vaccineTitle='Flu' AND dueDate<=DATE_ADD(CURDATE(), INTERVAL (SELECT followUpDueIn FROM follow_ups WHERE service='Daycare') DAY) AND dogID NOT IN (SELECT dogID FROM dogs_vaccines dv JOIN vaccines v USING (vaccineID) WHERE vaccineTitle='Flu Waiver') GROUP BY o.ownerID, lastName, primaryOwner, secondaryOwner, d.dogID, dogName, primaryCell, secondaryCell, homePhone, notes) r GROUP BY ownerID, lastName, primaryOwner, secondaryOwner, dogID, dogName, primaryCell, secondaryCell, homePhone, notes ORDER BY ownerID, lastName, primaryOwner, secondaryOwner, dogID, dogName, primaryCell, secondaryCell, homePhone, notes";
  $result_requests=$conn->query($sql_requests);
  $vetIndex=0;
  while ($row_requests=$result_requests->fetch_assoc()) {
    $vetIndex++;
    $ownerID=$row_requests['ownerID'];
    $lastName=mysqli_real_escape_string($conn, $row_requests['lastName']);
    $primaryOwner=mysqli_real_escape_string($conn, $row_requests['primaryOwner']);
    $secondaryOwner=mysqli_real_escape_string($conn, $row_requests['secondaryOwner']);
    $dogName=mysqli_real_escape_string($conn, $row_requests['dogName']);
    $dogID=$row_requests['dogID'];
    $dogNotes=nl2br($row_requests['notes']);
    $primaryCell=$row_requests['primaryCell'];
    $secondaryCell=$row_requests['secondaryCell'];
    $homePhone=$row_requests['homePhone'];
    echo "<div class='row'>
    <div class='col-sm-5'>";
    if ($vetIndex>1) {
      echo "<br>";
    }
    echo "<p><strong>Dog:</strong> $dogName</p>
    <p><strong>Owner:</strong> $lastName, $primaryOwner";
    if (isset($secondaryOwner) AND $secondaryOwner!='') {
      echo " & $secondaryOwner";
    }
    echo "</p>
    <p><strong>Phone: </strong>";
    $phoneNumbers=array();
    if (isset($primaryCell) AND $primaryCell!='') {
      array_push($phoneNumbers, $primaryCell);
    }
    if (isset($secondaryCell) AND $secondaryCell!='') {
      array_push($phoneNumbers, $secondaryCell);
    }
    if (isset($homePhone) AND $homePhone!='') {
      array_push($phoneNumbers, $homePhone);
    }
    $phoneNoIndex=0;
    foreach ($phoneNumbers as $phoneNo) {
      $phoneNoIndex++;
      if ($phoneNoIndex>1) {
        echo ", ";
      }
      echo "$phoneNo";
    }
    echo "</p>
    </div>
    <div class='col-sm-7' style='user-select:none;'>";
    if ($vetIndex>1) {
      echo "<br>";
    }
    echo "<button type='button' class='button-notes' id='add-vaccine-notes-button' data-toggle='modal' data-target='#addVaccineNotesModal' data-id='$dogID' data-owner='$ownerID' data-vet='$vetID' data-backdrop='static' title='Add Vaccine Notes for $dogName'></button>
    <strong>Notes for $dogName: </strong>" . stripslashes($dogNotes) . "</div>
    </div>";
  }
}
?>
