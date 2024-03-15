<?php
include '../assets/config.php';
if (isset($_POST['owner']) AND $_POST['owner']!='') {
  $ownerID=$_POST['owner'];
  $sql_packages="SELECT ownerPackageID, packageTitle, status, daysLeft, daysLeftWarning, startDate, expirationDate, DATE_SUB(expirationDate, INTERVAL expirationWarning DAY) AS expirationWarning, notes FROM owners_packages op JOIN packages p USING (packageID) WHERE ownerID='$ownerID' ORDER BY FIELD(status, 'Out of Days', 'Expired', 'Active', 'Not Started'), FIELD(packageTitle, 'Day Training'), ownerPackageID";
  $result_packages=$conn->query($sql_packages);
  while ($row_packages=$result_packages->fetch_assoc()) {
    $packageID=$row_packages['ownerPackageID'];
    $packageTitle=mysqli_real_escape_string($conn, $row_packages['packageTitle']);
    $status=mysqli_real_escape_string($conn, $row_packages['status']);
    if(isset($row_packages['expirationDate']) AND $row_packages['expirationDate']!='') {
      $expirationDate=strtotime($row_packages['expirationDate']);
      $expirationWarning=strtotime($row_packages['expirationWarning']);
    } else {
      $expirationDate='';
    }
    $daysLeft=$row_packages['daysLeft'];
    $daysLeftWarning=$row_packages['daysLeftWarning'];
    $packageNotes=nl2br($row_packages['notes']);
    echo "<div class='panel panel-";
    if (stripslashes($status)==='Expired' OR stripslashes($status)==='Out of Days' OR (isset($expirationDate) AND $expirationDate!='' AND $expirationDate<$today) OR (isset($daysLeft) AND $daysLeft!='' AND $daysLeft==0)) {
      echo "danger";
    } elseif ((isset($expirationDate) AND $expirationDate!='' AND $expirationDate<=$expirationWarning) OR (isset($daysLeft) AND $daysLeft!='' AND $daysLeft<=$daysLeftWarning)) {
      echo "warning";
    } elseif (stripslashes($status)==='Active') {
      echo "success";
    } elseif (stripslashes($status)==='Not Started') {
      echo "info";
    }
    echo "' id='panel-package-$packageID'>
    <div class='panel-heading package-heading'>" . stripslashes($packageTitle) . "<span class='package-status' id='package-status-$packageID'>" . stripslashes($status) . "</span></div>";
    if (isset($daysLeft) AND $daysLeft!=='') {
      echo "<div class='panel-body package-days-left text-";
      if ($daysLeft==0) {
        echo "danger";
      } elseif ($daysLeft<=$daysLeftWarning) {
        echo "warning";
      } else {
        echo "success";
      }
      echo "' id='days-left-$packageID'><span id='days-left-count-$packageID'>$daysLeft</span> day";
      if ($daysLeft!=1) {
        echo "<span id='days-left-plural-$packageID'>s</span>";
      }
      echo " left</div>";
    }
    if (isset($expirationDate) AND $expirationDate!=='') {
      echo "<div class='panel-body package-expiration-date text-";
      if ($expirationDate<$today) {
        echo "danger";
      } elseif ($today>=$expirationWarning) {
        echo "warning";
      } else {
        echo "success";
      }
      echo "'>Expire";
      if ($expirationDate>=$today) {
        echo "s ";
      } elseif ($expirationDate<$today) {
        echo "d ";
      }
      echo date('D n/j', $expirationDate) . "</div>";
    }
    if (isset($packageNotes) AND $packageNotes!=='') {
      echo "<div class='panel-body package-notes text-default'>" . stripslashes($packageNotes) . "</div>";
    }
    echo "<div class='panel-footer'>
    <button type='button' class='button-delete' id='delete-package-button' data-toggle='modal' data-target='#deletePackageModal' data-id='$packageID' data-backdrop='static' title='Delete Package'></button>
    <button type='button' class='button-edit' id='edit-package-button' data-toggle='modal' data-target='#editPackageModal' data-id='$packageID' data-owner='$ownerID' data-backdrop='static' title='Edit Package'></button>
    <button type='button' class='button-notes' id='add-package-notes-button' data-toggle='modal' data-target='#addPackageNotesModal' data-id='$packageID' data-owner='$ownerID' data-backdrop='static' title='Add Note'></button>";
    if (stripslashes($status)==='Active' AND $daysLeft>0) {
      echo "<button type='button' class='button-decrease' id='decrease-package-days-button-$packageID' data-id='$packageID' data-days='$daysLeft' data-warning='$daysLeftWarning' title='Decrease Package Days'></button>";
    }
    echo "</div>
    </div>";
  }
}
?>
