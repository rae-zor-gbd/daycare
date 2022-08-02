<?php
include 'config.php';
$sql_reminders="SELECT ownerID, ownerName, MIN(reminderDate) AS reminderDate FROM (SELECT ownerID, CONCAT(lastName, ', ', primaryOwner, IF(secondaryOwner!='' AND secondaryOwner IS NOT NULL, CONCAT(' & ', secondaryOwner), '')) AS ownerName, DATE_SUB(expirationDate, INTERVAL expirationWarning DAY) AS reminderDate FROM owners o JOIN owners_packages op USING (ownerID) JOIN packages USING (packageID) WHERE expirationDate<=DATE_ADD(CURDATE(), INTERVAL expirationWarning DAY) AND daysLeft>0 AND status!='Expired' AND ownerID NOT IN (SELECT ownerID FROM owners_packages WHERE status='Not Started') UNION SELECT ownerID, CONCAT(lastName, ', ', primaryOwner, IF(secondaryOwner!='' AND secondaryOwner IS NOT NULL, CONCAT(' & ', secondaryOwner), '')) AS ownerName, DATE(NOW()) AS reminderDate FROM owners o JOIN owners_packages op USING (ownerID) JOIN packages USING (packageID) WHERE daysLeft>0 AND daysLeft<=daysLeftWarning AND status!='Expired' AND ownerID NOT IN (SELECT ownerID FROM owners_packages WHERE status='Not Started') UNION SELECT ownerID, CONCAT(lastName, ', ', primaryOwner, IF(secondaryOwner!='' AND secondaryOwner IS NOT NULL, CONCAT(' & ', secondaryOwner), '')) AS ownerName, MIN(DATE_SUB(dueDate, INTERVAL (SELECT followUpDueIn FROM follow_ups WHERE service='Daycare') DAY)) AS reminderDate FROM dogs d JOIN dogs_vaccines dv USING (dogID) JOIN vaccines v USING (vaccineID) JOIN owners o USING (ownerID) WHERE requirementStatus='Required' AND vaccineTitle!='Fecal' AND vaccineTitle!='Flu' AND dueDate<=DATE_ADD(CURDATE(), INTERVAL (SELECT followUpDueIn FROM follow_ups WHERE service='Daycare') DAY) GROUP BY ownerID, ownerName UNION SELECT ownerID, CONCAT(lastName, ', ', primaryOwner, IF(secondaryOwner!='' AND secondaryOwner IS NOT NULL, CONCAT(' & ', secondaryOwner), '')) AS ownerName, MIN(DATE_SUB(dueDate, INTERVAL (SELECT followUpDueIn FROM follow_ups WHERE service='Daycare') DAY)) AS reminderDate FROM dogs d JOIN dogs_vaccines dv USING (dogID) JOIN vaccines v USING (vaccineID) JOIN owners o USING (ownerID) WHERE vaccineTitle='Fecal' AND dueDate<=DATE_ADD(CURDATE(), INTERVAL (SELECT followUpDueIn FROM follow_ups WHERE service='Daycare') DAY) AND ownerID NOT IN (SELECT ownerID FROM dogs d JOIN dogs_vaccines dv USING (dogID) JOIN vaccines v USING (vaccineID) JOIN owners o USING (ownerID) WHERE vaccineTitle='Fecal' AND dueDate>=DATE_ADD(NOW(), INTERVAL (SELECT followUpDueIn FROM follow_ups WHERE service='Daycare') DAY) GROUP BY ownerID) GROUP BY ownerID, ownerName UNION SELECT ownerID, CONCAT(lastName, ', ', primaryOwner, IF(secondaryOwner!='' AND secondaryOwner IS NOT NULL, CONCAT(' & ', secondaryOwner), '')) AS ownerName, MIN(DATE_SUB(dueDate, INTERVAL (SELECT followUpDueIn FROM follow_ups WHERE service='Daycare') DAY)) AS reminderDate FROM dogs d JOIN dogs_vaccines dv USING (dogID) JOIN vaccines v USING (vaccineID) JOIN owners o USING (ownerID) WHERE vaccineTitle='Flu' AND dueDate<=DATE_ADD(CURDATE(), INTERVAL (SELECT followUpDueIn FROM follow_ups WHERE service='Daycare') DAY) AND dogID NOT IN (SELECT dogID FROM dogs_vaccines dv JOIN vaccines v USING (vaccineID) WHERE vaccineTitle='Flu Waiver') GROUP BY ownerID, ownerName) r GROUP BY ownerID, ownerName ORDER BY reminderDate DESC, ownerName";
$result_reminders=$conn->query($sql_reminders);
if ($result_reminders->num_rows>0) {
  while ($row_reminders=$result_reminders->fetch_assoc()) {
    $ownerID=$row_reminders['ownerID'];
    $ownerName=mysqli_real_escape_string($conn, $row_reminders['ownerName']);
    echo "<tr>
      <td>$ownerName</td>
      <td>";
      $sql_emails="SELECT email FROM emails WHERE ownerID='$ownerID'";
      $result_emails=$conn->query($sql_emails);
      if ($result_emails->num_rows>0) {
        while ($row_emails=$result_emails->fetch_assoc()) {
          $ownerEmail=mysqli_real_escape_string($conn, $row_emails['email']);
          echo "$ownerEmail<br>";
        }
      } else {
        echo "<em class='text-muted'>None</em>";
      }
      echo "</td>
      <td>";
      $sql_package_reminders="SELECT ownerPackageID, packageTitle, daysLeft, expirationDate, notes FROM owners o JOIN owners_packages op USING (ownerID) JOIN packages USING (packageID) WHERE ownerID='$ownerID' AND status!='Expired' AND daysLeft>0 AND (daysLeft<=daysLeftWarning OR expirationDate<=DATE_ADD(CURDATE(), INTERVAL expirationWarning DAY))";
      $result_package_reminders=$conn->query($sql_package_reminders);
      if ($result_package_reminders->num_rows>0) {
        while ($row_package_reminders=$result_package_reminders->fetch_assoc()) {
          $packageID=$row_package_reminders['ownerPackageID'];
          $packageTitle=mysqli_real_escape_string($conn, $row_package_reminders['packageTitle']);
          $daysLeft=$row_package_reminders['daysLeft'];
          $expirationDate=strtotime($row_package_reminders['expirationDate']);
          $packageNotes=nl2br($row_package_reminders['notes']);
          echo "<div class='package-reminder'>
          <span class='label label-";
          if ($expirationDate<$today OR $daysLeft==0) {
            echo "danger";
          } else {
            echo "warning";
          }
          echo "'>$daysLeft day";
          if ($daysLeft!=1) {
            echo "s";
          }
          echo " left by " . date('D, M j, Y', $expirationDate) . "</span>
          </div>";
          if (isset($packageNotes) AND $packageNotes!=='') {
            echo "<div class='package-reminder-notes'>
            <span class='label label-default'>" . stripslashes($packageNotes) . "</span>
            </div>";
          }
        }
      } else {
        echo "<em class='text-muted'>None</em>";
      }
      echo "</td>
      <td>";
      $sql_vaccine_reminders="SELECT dogID, dogName, vaccineTitle, dueDate, notes FROM dogs d JOIN dogs_vaccines dv USING (dogID) JOIN vaccines v USING (vaccineID) WHERE ownerID='$ownerID' AND requirementStatus='Required' AND dueDate<=DATE_ADD(NOW(), INTERVAL (SELECT followUpDueIn FROM follow_ups WHERE service='Daycare') DAY) ORDER BY dueDate";
      $result_vaccine_reminders=$conn->query($sql_vaccine_reminders);
      if ($result_vaccine_reminders->num_rows>0) {
        while ($row_vaccine_reminders=$result_vaccine_reminders->fetch_assoc()) {
          $dogID=$row_vaccine_reminders['dogID'];
          $dogName=mysqli_real_escape_string($conn, $row_vaccine_reminders['dogName']);
          $vaccineTitle=mysqli_real_escape_string($conn, $row_vaccine_reminders['vaccineTitle']);
          $vaccineDueDate=strtotime($row_vaccine_reminders['dueDate']);
          $vaccineNotes=nl2br($row_vaccine_reminders['notes']);
          echo "<div class='vaccine-reminder'>
          <span class='label label-";
          if ($vaccineDueDate<$today) {
            echo "danger";
          } else {
            echo "warning";
          }
          echo "'>$dogName: $vaccineTitle due " . date('D, M j, Y', $vaccineDueDate) . "</span>
          </div>";
        }
        if (isset($vaccineNotes) AND $vaccineNotes!=='') {
          echo "<div class='vaccine-reminder-notes'>
          <span class='label label-default'>" . stripslashes($vaccineNotes) . "</span>
          </div>";
        }
      } else {
        echo "<em class='text-muted'>None</em>";
      }
      echo "</td>
    </tr>";
  }
}
?>
