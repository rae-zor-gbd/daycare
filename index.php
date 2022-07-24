<?php
include 'assets/config.php';
if (isset($_GET['search']) AND $_GET['search']!=='') {
  $search=$_GET['search'];
  $sql_owners="SELECT o.ownerID, lastName, primaryOwner, secondaryOwner FROM owners o JOIN dogs d USING (ownerID) WHERE dogName LIKE '%$search%' OR lastName LIKE '%$search%' OR primaryOwner LIKE '%$search%' OR secondaryOwner LIKE '%$search%' GROUP BY o.ownerID, lastName, primaryOwner, secondaryOwner ORDER BY lastName, primaryOwner LIMIT 12";
} else {
  $sql_owners="SELECT ownerID, lastName, primaryOwner, secondaryOwner FROM owners ORDER BY lastName, primaryOwner LIMIT 12";
}
$result_owners=$conn->query($sql_owners);
?>
<!DOCTYPE html>
<html lang='en'>
<head>
  <title>Daycare</title>
  <?php include 'assets/header.php'; ?>
  <script type='text/javascript'>
  $(document).ready(function(){
    $('#packages').addClass('active');
  });
  </script>
</head>
<body>
  <?php include 'assets/navbar.php'; ?>
  <div class='container-fluid'>
    <form action='' spellcheck='false'>
      <div class='form-group'>
        <input type='text' name='search' class='form-control' placeholder='Search'>
      </div>
    </form>
    <div class='panel-group' id='panel-owners'>
      <?php
      while ($row_owners=$result_owners->fetch_assoc()) {
        $ownerID=$row_owners['ownerID'];
        echo "<div class='panel panel-default panel-owner'>
        <a class='collapsed' data-toggle='collapse' data-parent='#panel-owners' data-target='#owner-" . $ownerID . "'>
        <div class='panel-heading'>
        <div class='panel-title owner-heading'>
        <strong>" . $row_owners['lastName'] . "</strong>, " . $row_owners['primaryOwner'];
        if (isset($row_owners['secondaryOwner'])) {
          echo " & " . $row_owners['secondaryOwner'];
        }
        echo "<div class='panel-arrow'></div>
        </div>
        </div>
        </a>
        <div id='owner-" . $ownerID . "' class='panel-collapse collapse'>
        <div class='panel-body'>";
        $sql_packages="SELECT packageTitle, status, daysLeft, daysLeftWarning, startDate, expirationDate, DATE_SUB(expirationDate, INTERVAL expirationWarning DAY) AS expirationWarning, notes FROM owners_packages op JOIN packages p USING (packageID) WHERE ownerID='$ownerID' ORDER BY FIELD(status, 'Expired', 'Out of Days', 'Active', 'Not Started');";
        $result_packages=$conn->query($sql_packages);
        while ($row_packages=$result_packages->fetch_assoc()) {
          echo "<div class='panel panel-";
          if ($row_packages['status']==='Expired' OR $row_packages['status']==='Out of Days' OR (isset($row_packages['expirationDate']) AND $row_packages['expirationDate']!='' AND strtotime($row_packages['expirationDate'])<=strtotime(today)) OR (isset($row_packages['daysLeft']) AND $row_packages['daysLeft']!='' AND $row_packages['daysLeft']==0)) {
            echo "danger";
          } elseif ((isset($row_packages['expirationDate']) AND $row_packages['expirationDate']!='' AND $row_packages['expirationDate']<=$row_packages['expirationWarning']) OR (isset($row_packages['daysLeft']) AND $row_packages['daysLeft']!='' AND $row_packages['daysLeft']<=$row_packages['daysLeftWarning'])) {
            echo "warning";
          } elseif ($row_packages['status']==='Active') {
            echo "success";
          } elseif ($row_packages['status']==='Not Started') {
            echo "info";
          }
          echo "'>
          <div class='panel-heading package-heading'>" . $row_packages['packageTitle'] . "<span class='package-status'>" . $row_packages['status'] . "</span></div>
          <div class='panel-body'>";
          if (isset($row_packages['daysLeft']) AND $row_packages['daysLeft']!=='') {
            echo "<div class='package-days-left'>
            <span class='label label-";
            if ($row_packages['daysLeft']==0) {
              echo "danger";
            } elseif ($row_packages['daysLeft']<=$row_packages['daysLeftWarning']) {
              echo "warning";
            } else {
              echo "success";
            }
            echo "'>" . $row_packages['daysLeft'] . " day";
            if ($row_packages['daysLeft']!=1) {
              echo "s";
            }
            echo " left</span>
            </div>";
          }
          if (isset($row_packages['expirationDate']) AND $row_packages['expirationDate']!=='') {
            echo "<div class='package-expiration-date'>
            <span class='label label-";
            if (strtotime($row_packages['expirationDate'])<strtotime(today)) {
              echo "danger";
            } elseif (strtotime(today)>=strtotime($row_packages['expirationWarning'])) {
              echo "warning";
            } else {
              echo "success";
            }
            echo "'>Expire";
            if (strtotime($row_packages['expirationDate'])>=strtotime(today)) {
              echo "s ";
            } elseif (strtotime($row_packages['expirationDate'])<strtotime(today)) {
              echo "d ";
            }
            echo date('D, M j, Y', strtotime($row_packages['expirationDate'])) . "</span>
            </div>";
          }
          if (isset($row_packages['notes']) AND $row_packages['notes']!=='') {
            echo "<div class='package-notes'>
            <span class='label label-default'>" . $row_packages['notes'] . "</span>
            </div>";
          }
          echo "</div>
          <div class='panel-footer'>
          <button type='button' class='button-delete' title='Delete Package'></button>
          <button type='button' class='button-edit' title='Edit Package'></button>
          <button type='button' class='button-notes' title='Add Note'></button>";
          if ($row_packages['status']==='Active' AND $row_packages['daysLeft']>0) {
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
          <div class='panel-heading dog-heading'>" . $row_dogs['dogName'] . "</div>
          <div class='panel-body'>
          <div class='dog-daycare-contract-status'>";
          if ($row_dogs['daycareContract']==='Completed') {
            echo "<span class='label label-success'>Completed Daycare Contract</span>";
          } elseif ($row_dogs['daycareContract']==='Incomplete') {
            echo "<span class='label label-danger'>Incomplete Daycare Contract</span>";
          }
          echo "</div>";
          if ($result_vaccines->num_rows>0) {
            while ($row_vaccines=$result_vaccines->fetch_assoc()) {
              echo "<div class='dog-vaccine-status'>
              <span class='label label-";
              if (strtotime($row_vaccines['dueDate'])<strtotime(today)) {
                echo "danger";
              } elseif (strtotime($row_vaccines['dueDate'])>=strtotime(today)) {
                echo "warning";
              }
              echo "'>" . $row_vaccines['vaccineTitle'] . " due " . date('D, M j, Y', strtotime($row_vaccines['dueDate'])) . "</span>
              </div>";
            }
          }
          if (isset($row_dogs['notes']) AND $row_dogs['notes']!=='') {
            echo "<div class='dog-notes'>
            <span class='label label-default'>" . $row_dogs['notes'] . "</span>
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
        <button type='button' class='button-delete' title='Delete Owner'></button>
        <button type='button' class='button-edit' title='Edit Owner'></button>
        <button type='button' class='button-dog' title='Add New Dog'></button>
        <button type='button' class='button-email' title='View Owner Email Addresses'></button>
        <button type='button' class='button-package' title='Add New Package'></button>
        </div>
        </div>
        </div>";
      }
      ?>
    </div>
  </div>
</body>
</html>
