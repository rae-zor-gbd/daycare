<?php
include '../assets/config.php';
$sql_vets="SELECT vetID, vetName, vetEmail FROM (SELECT vetID, vetName, vetEmail FROM dogs d JOIN dogs_vaccines dv USING (dogID) JOIN vaccines vx USING (vaccineID) JOIN owners o USING (ownerID) JOIN vets v USING (vetID) WHERE requirementStatus='Required' AND vaccineTitle!='Fecal' AND vaccineTitle!='Flu' AND dueDate<=DATE_ADD(CURDATE(), INTERVAL (SELECT followUpDueIn FROM follow_ups WHERE service='Daycare') DAY) GROUP BY vetID, vetName, vetEmail UNION SELECT vetID, vetName, vetEmail FROM dogs d JOIN dogs_vaccines dv USING (dogID) JOIN vaccines vx USING (vaccineID) JOIN owners o USING (ownerID) JOIN vets v USING (vetID) WHERE vaccineTitle='Fecal' AND dueDate<=DATE_ADD(CURDATE(), INTERVAL (SELECT followUpDueIn FROM follow_ups WHERE service='Daycare') DAY) AND ownerID NOT IN (SELECT ownerID FROM dogs d JOIN dogs_vaccines dv USING (dogID) JOIN vaccines v USING (vaccineID) JOIN owners o USING (ownerID) WHERE vaccineTitle='Fecal' AND dueDate>=DATE_ADD(NOW(), INTERVAL (SELECT followUpDueIn FROM follow_ups WHERE service='Daycare') DAY) GROUP BY ownerID) GROUP BY vetID, vetName, vetEmail UNION SELECT vetID, vetName, vetEmail FROM dogs d JOIN dogs_vaccines dv USING (dogID) JOIN vaccines vx USING (vaccineID) JOIN owners o USING (ownerID) JOIN vets v USING (vetID) WHERE vaccineTitle='Flu' AND dueDate<=DATE_ADD(CURDATE(), INTERVAL (SELECT followUpDueIn FROM follow_ups WHERE service='Daycare') DAY) AND dogID NOT IN (SELECT dogID FROM dogs_vaccines dv JOIN vaccines v USING (vaccineID) WHERE vaccineTitle='Flu Waiver') GROUP BY vetID, vetName, vetEmail) r GROUP BY vetID, vetName, vetEmail ORDER BY vetName";
$result_vets=$conn->query($sql_vets);
if ($result_vets->num_rows>0) {
  while ($row_vets=$result_vets->fetch_assoc()) {
    $vetID=$row_vets['vetID'];
    $vetName=mysqli_real_escape_string($conn, $row_vets['vetName']);
    $vetEmail=mysqli_real_escape_string($conn, $row_vets['vetEmail']);
    echo "<div class='panel panel-default panel-vet' id='panel-vet-$vetID'>
    <a class='collapsed' data-toggle='collapse' data-parent='#panel-vets' data-target='#collapse-vet-$vetID'>
    <div class='panel-heading'>
    <div class='panel-title vet-heading'>
    <strong>" . stripslashes($vetName) . "</strong>
    <div class='panel-arrow'></div>
    </div>
    </div>
    </a>
    <div id='collapse-vet-$vetID' class='panel-collapse collapse'>
    <div class='panel-body' id='vet-$vetID'>
    <script type='text/javascript'>
    loadRequests($vetID);
    </script>
    </div>
    <div class='panel-footer'>";
    if (isset($vetEmail) AND $vetEmail!='') {
      echo "<button type='button' class='button-email' id='email-vet-button' data-email='$vetEmail' title='Copy Vet Email Address to Clipboard'></button>";
    } else {
      echo "<button type='button' class='button-email disabled' title='No Vet Email on File' disabled></button>";
    }
    echo "</div>
    </div>
    </div>";
  }
}
?>
