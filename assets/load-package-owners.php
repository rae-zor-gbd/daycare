<?php
include 'config.php';
if (isset($_POST['page']) AND $_POST['page']!='') {
  $page=$_POST['page'];
} else {
  $page=0;
}
$limit=12;
$offset=($page-1)*$limit;
$previous=$page-1;
$next=$page+1;
if (isset($_POST['search']) AND $_POST['search']!=='') {
  $search=mysqli_real_escape_string($conn, $_POST['search']);
  $sql_total_owners="SELECT COUNT(ownerID) AS totalResults FROM ((SELECT ownerID, lastName, primaryOwner, secondaryOwner FROM owners WHERE lastName LIKE '%$search%' OR primaryOwner LIKE '%$search%' OR secondaryOwner LIKE '%$search%') UNION (SELECT o.ownerID, lastName, primaryOwner, secondaryOwner FROM owners o JOIN dogs d USING (ownerID) WHERE dogName LIKE '%$search%' OR lastName LIKE '%$search%' OR primaryOwner LIKE '%$search%' OR secondaryOwner LIKE '%$search%' GROUP BY o.ownerID, lastName, primaryOwner, secondaryOwner)) r";
  $sql_owners="(SELECT ownerID, lastName, primaryOwner, secondaryOwner FROM owners WHERE lastName LIKE '%$search%' OR primaryOwner LIKE '%$search%' OR secondaryOwner LIKE '%$search%') UNION (SELECT o.ownerID, lastName, primaryOwner, secondaryOwner FROM owners o JOIN dogs d USING (ownerID) WHERE dogName LIKE '%$search%' OR lastName LIKE '%$search%' OR primaryOwner LIKE '%$search%' OR secondaryOwner LIKE '%$search%' GROUP BY o.ownerID, lastName, primaryOwner, secondaryOwner) ORDER BY lastName, primaryOwner LIMIT $page, $limit";
} else {
  $sql_total_owners="SELECT COUNT(ownerID) AS totalResults FROM owners";
  $sql_owners="SELECT ownerID, lastName, primaryOwner, secondaryOwner FROM owners ORDER BY lastName, primaryOwner LIMIT $page, $limit";
}
$result_total_owners=$conn->query($sql_total_owners);
$row_total_owners=$result_total_owners->fetch_assoc();
$totalOwners=$row_total_owners['totalResults'];
$result_owners=$conn->query($sql_owners);
if ($result_owners->num_rows>0) {
  while ($row_owners=$result_owners->fetch_assoc()) {
    $page++;
    $ownerID=$row_owners['ownerID'];
    $lastName=mysqli_real_escape_string($conn, $row_owners['lastName']);
    $primaryOwner=mysqli_real_escape_string($conn, $row_owners['primaryOwner']);
    $secondaryOwner=mysqli_real_escape_string($conn, $row_owners['secondaryOwner']);
    echo "<div class='panel panel-default panel-owner' id='panel-owner-$ownerID'>
    <a class='collapsed' data-toggle='collapse' data-parent='#panel-owners' data-target='#owner-$ownerID'>
    <div class='panel-heading'>
    <div class='panel-title owner-heading'>
    <strong>" . stripslashes($lastName) . "</strong>, " . stripslashes($primaryOwner);
    if (isset($secondaryOwner) AND $secondaryOwner!='') {
      echo " & " . stripslashes($secondaryOwner);
    }
    echo "<div class='panel-arrow'></div>
    </div>
    </div>
    </a>
    <div id='owner-$ownerID' class='panel-collapse collapse'>
    <div class='panel-body'>";
    $sql_packages="SELECT packageTitle, status, daysLeft, daysLeftWarning, startDate, expirationDate, DATE_SUB(expirationDate, INTERVAL expirationWarning DAY) AS expirationWarning, notes FROM owners_packages op JOIN packages p USING (packageID) WHERE ownerID='$ownerID' ORDER BY FIELD(status, 'Expired', 'Out of Days', 'Active', 'Not Started');";
    $result_packages=$conn->query($sql_packages);
    while ($row_packages=$result_packages->fetch_assoc()) {
      $packageTitle=mysqli_real_escape_string($conn, $row_packages['packageTitle']);
      $status=mysqli_real_escape_string($conn, $row_packages['status']);
      $today=strtotime(date('Y-m-d'));
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
    echo "</div>
    <div class='panel-body'>";
    $sql_dogs="SELECT dogID, dogName, daycareContract, notes FROM dogs WHERE ownerID='$ownerID' ORDER BY dogName";
    $result_dogs=$conn->query($sql_dogs);
    while ($row_dogs=$result_dogs->fetch_assoc()) {
      $dogID=$row_dogs['dogID'];
      $dogName=mysqli_real_escape_string($conn, $row_dogs['dogName']);
      $daycareContract=mysqli_real_escape_string($conn, $row_dogs['daycareContract']);
      $dogNotes=mysqli_real_escape_string($conn, $row_dogs['notes']);
      $sql_current_fecal="SELECT * FROM dogs d JOIN dogs_vaccines dv USING (dogID) JOIN vaccines v USING (vaccineID) WHERE ownerID='$ownerID' AND vaccineTitle='Fecal' AND dueDate>=DATE_ADD(NOW(), INTERVAL (SELECT followUpDueIn FROM follow_ups WHERE service='Daycare') DAY)";
      $result_current_fecal=$conn->query($sql_current_fecal);
      $sql_vaccines="SELECT vaccineTitle, dueDate FROM dogs d JOIN dogs_vaccines dv USING (dogID) JOIN vaccines USING (vaccineID) WHERE ownerID='$ownerID' AND dogID='$dogID' AND requirementStatus='Required'";
      if ($result_current_fecal->num_rows>0) {
        $sql_vaccines.="AND vaccineTitle!='Fecal'";
      }
      $sql_vaccines.="AND dueDate<=DATE_ADD(NOW(), INTERVAL (SELECT followUpDueIn FROM follow_ups WHERE service='Daycare') DAY) ORDER BY dueDate, vaccineTitle";
      $result_vaccines=$conn->query($sql_vaccines);
      echo "<div class='panel panel-";
      if ($result_vaccines->num_rows>0) {
        $sql_past_due_vaccines="SELECT vaccineTitle, dueDate FROM dogs d JOIN dogs_vaccines dv USING (dogID) JOIN vaccines USING (vaccineID) WHERE ownerID='$ownerID' AND dogID='$dogID' AND requirementStatus='Required'";
        if ($result_current_fecal->num_rows>0) {
          $sql_past_due_vaccines.="AND vaccineTitle!='Fecal'";
        }
        $sql_past_due_vaccines.="AND dueDate<NOW() ORDER BY dueDate, vaccineTitle";
        $result_past_due_vaccines=$conn->query($sql_past_due_vaccines);
        $sql_vaccines_due_soon="SELECT vaccineTitle, dueDate FROM dogs d JOIN dogs_vaccines dv USING (dogID) JOIN vaccines USING (vaccineID) WHERE ownerID='$ownerID' AND dogID='$dogID' AND requirementStatus='Required'";
        if ($result_current_fecal->num_rows>0) {
          $sql_vaccines_due_soon.="AND vaccineTitle!='Fecal'";
        }
        $sql_vaccines_due_soon.="AND dueDate>=NOW() ORDER BY dueDate, vaccineTitle";
        $result_vaccines_due_soon=$conn->query($sql_vaccines_due_soon);
        if ($result_past_due_vaccines->num_rows>0) {
          echo "danger";
        } elseif ($result_vaccines_due_soon->num_rows>0) {
          echo "warning";
        }
      } else {
        echo "success";
      }
      echo "'>
      <div class='panel-heading dog-heading'>" . stripslashes($dogName) . "</div>
      <div class='panel-body'>
      <div class='dog-daycare-contract-status'>";
      if (stripslashes($daycareContract)==='Completed') {
        echo "<span class='label label-success'>Completed Daycare Contract</span>";
      } elseif (stripslashes($daycareContract)==='Incomplete') {
        echo "<span class='label label-danger'>Incomplete Daycare Contract</span>";
      }
      echo "</div>";
      if ($result_vaccines->num_rows>0) {
        while ($row_vaccines=$result_vaccines->fetch_assoc()) {
          $vaccineTitle=mysqli_real_escape_string($conn, $row_vaccines['vaccineTitle']);
          $dueDate=strtotime($row_vaccines['dueDate']);
          echo "<div class='dog-vaccine-status'>
          <span class='label label-";
          if ($dueDate<$today) {
            echo "danger";
          } elseif ($dueDate>=$today) {
            echo "warning";
          }
          echo "'>" . stripslashes($vaccineTitle) . " due " . date('D, M j, Y', $dueDate) . "</span>
          </div>";
        }
      }
      if (isset($dogNotes) AND $dogNotes!=='') {
        echo "<div class='dog-notes'>
        <span class='label label-default'>" . stripslashes($dogNotes) . "</span>
        </div>";
      }
      echo "</div>
      <div class='panel-footer'>
      <button type='button' class='button-delete' title='Delete Dog'></button>
      <button type='button' class='button-edit' title='Edit Dog'></button>
      <button type='button' class='button-notes' title='Add Note'></button>
      </div>
      </div>";
    }
    echo "</div>
    <div class='panel-footer'>
    <button type='button' class='button-delete' id='delete-owner-button' data-toggle='modal' data-target='#deleteOwnerModal' data-id='$ownerID' title='Delete Owner'></button>
    <button type='button' class='button-edit' id='edit-owner-button' data-toggle='modal' data-target='#editOwnerModal' data-id='$ownerID' title='Edit Owner'></button>
    <button type='button' class='button-dog' id='add-dog-button' data-toggle='modal' data-target='#addDogModal' data-id='$ownerID' title='Add New Dog'></button>
    <button type='button' class='button-package' title='Add New Package'></button>
    </div>
    </div>
    </div>";
  }
  if ($page!=$totalOwners) {
    echo "<button type='button' class='btn btn-default load-more' id='pagination' data-id='{$page}'>Load More</button>";
  }
}
?>
