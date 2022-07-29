<?php
include 'config.php';
if (isset($_POST['owner']) AND $_POST['owner']!='') {
  $ownerID=$_POST['owner'];
  $sql_packages="SELECT packageTitle, status, daysLeft, daysLeftWarning, startDate, expirationDate, DATE_SUB(expirationDate, INTERVAL expirationWarning DAY) AS expirationWarning, notes FROM owners_packages op JOIN packages p USING (packageID) WHERE ownerID='$ownerID' ORDER BY FIELD(status, 'Expired', 'Out of Days', 'Active', 'Not Started');";
  $result_packages=$conn->query($sql_packages);
  while ($row_packages=$result_packages->fetch_assoc()) {
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
    $packageNotes=mysqli_real_escape_string($conn, $row_packages['notes']);
    echo "<div class='panel panel-";
    if (stripslashes($status)==='Expired' OR stripslashes($status)==='Out of Days' OR (isset($expirationDate) AND $expirationDate!='' AND $expirationDate<=$today) OR (isset($daysLeft) AND $daysLeft!='' AND $daysLeft==0)) {
      echo "danger";
    } elseif ((isset($expirationDate) AND $expirationDate!='' AND $expirationDate<=$expirationWarning) OR (isset($daysLeft) AND $daysLeft!='' AND $daysLeft<=$daysLeftWarning)) {
      echo "warning";
    } elseif (stripslashes($status)==='Active') {
      echo "success";
    } elseif (stripslashes($status)==='Not Started') {
      echo "info";
    }
    echo "'>
    <div class='panel-heading package-heading'>" . stripslashes($packageTitle) . "<span class='package-status'>" . stripslashes($status) . "</span></div>
    <div class='panel-body'>";
    if (isset($daysLeft) AND $daysLeft!=='') {
      echo "<div class='package-days-left'>
      <span class='label label-";
      if ($daysLeft==0) {
        echo "danger";
      } elseif ($daysLeft<=$daysLeftWarning) {
        echo "warning";
      } else {
        echo "success";
      }
      echo "'>$daysLeft day";
      if ($daysLeft!=1) {
        echo "s";
      }
      echo " left</span>
      </div>";
    }
    if (isset($expirationDate) AND $expirationDate!=='') {
      echo "<div class='package-expiration-date'>
      <span class='label label-";
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
      echo date('D, M j, Y', $expirationDate) . "</span>
      </div>";
    }
    if (isset($packageNotes) AND $packageNotes!=='') {
      echo "<div class='package-notes'>
      <span class='label label-default'>" . stripslashes($packageNotes) . "</span>
      </div>";
    }
    echo "</div>
    <div class='panel-footer'>
    <button type='button' class='button-delete' title='Delete Package'></button>
    <button type='button' class='button-edit' title='Edit Package'></button>
    <button type='button' class='button-notes' title='Add Note'></button>";
    if (stripslashes($status)==='Active' AND $daysLeft>0) {
      echo "<button type='button' class='button-decrease' title='Decrease Package Days'></button>";
    }
    echo "</div>
    </div>";
  }
}
?>
