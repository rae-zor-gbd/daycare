<?php
include 'assets/config.php';
if (isset($_GET['search']) AND $_GET['search']!=='') {
  $search=$_GET['search'];
  $sql_owners="SELECT o.ownerID, lastName, primaryOwner, secondaryOwner, o.notes FROM owners o JOIN dogs d USING (ownerID) WHERE dogName LIKE '%$search%' OR lastName LIKE '%$search%' OR primaryOwner LIKE '%$search%' OR secondaryOwner LIKE '%$search%' GROUP BY o.ownerID, lastName, primaryOwner, secondaryOwner, o.notes ORDER BY lastName, primaryOwner LIMIT 12";
} else {
  $sql_owners="SELECT ownerID, lastName, primaryOwner, secondaryOwner, notes FROM owners ORDER BY lastName, primaryOwner LIMIT 12";
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
    <div class='panel-group' id='accordion'>
      <?php
      while ($row_owners=$result_owners->fetch_assoc()) {
        $ownerID=$row_owners['ownerID'];
        echo "<div class='panel panel-default panel-owner'>
        <a class='accordion-toggle' data-toggle='collapse' data-parent='#accordion' data-target='#owner-" . $ownerID . "'>
        <div class='panel-heading'>
        <div class='panel-title'>
        <strong>" . $row_owners['lastName'] . "</strong>, " . $row_owners['primaryOwner'];
        if (isset($row_owners['secondaryOwner'])) {
          echo " & " . $row_owners['secondaryOwner'];
        }
        echo "</div>
        </div>
        </a>
        <div id='owner-" . $ownerID . "' class='panel-collapse collapse'>
        <div class='panel-body'>
        <div><strong>Packages</strong></div>
        <div>";
        $sql_packages="SELECT packageTitle, status, daysLeft, daysLeftWarning, startDate, expirationDate, expirationWarning, notes FROM owners_packages op JOIN packages p USING (packageID) WHERE ownerID='$ownerID' ORDER BY FIELD(status, 'Expired', 'Out of Days', 'Active', 'Not Started');";
        $result_packages=$conn->query($sql_packages);
        while ($row_packages=$result_packages->fetch_assoc()) {
          echo "<div class='panel panel-";
          if ($row_packages['status']==='Active') {
            echo "success";
          } elseif ($row_packages['status']==='Not Started') {
            echo "info";
          } elseif ($row_packages['status']==='Expired' OR $row_packages['status']==='Out of Days') {
            echo "danger";
          }
          echo "'>
          <div class='panel-heading'>" . $row_packages['packageTitle'] . "</div>
          <div class='panel-body'>";
          if (isset($row_packages['daysLeft']) AND $row_packages['daysLeft']!=='') {
            echo "<div>" . $row_packages['daysLeft'] . " days left</div>";
          }
          if (isset($row_packages['expirationDate']) AND $row_packages['expirationDate']!=='') {
            echo "<div>Expires " . date('M j, Y', strtotime($row_packages['expirationDate'])) . "</div>";
          }
          if (isset($row_packages['notes']) AND $row_packages['notes']!=='') {
            echo "<div>Notes: " . $row_packages['notes'] . "</div>";
          }
          echo "</div>
          </div>";
        }
        echo "</div>
        </div>
        <div class='panel-body'>
        <div><strong>Pets</strong></div>";
        $sql_dogs="SELECT dogID, dogName, daycareContract, notes FROM dogs WHERE ownerID='$ownerID' ORDER BY dogName";
        $result_dogs=$conn->query($sql_dogs);
        while ($row_dogs=$result_dogs->fetch_assoc()) {
          echo $row_dogs['dogName'] . "<br>";
        }
        echo "</div>
        <div class='panel-body'>
        <div><strong>Owner Notes</strong></div>
        <div>";
        if (isset($row_owners['notes']) AND $row_owners['notes']!=='') {
          echo $row_owners['notes'];
        } else {
          echo "<em>None</em>";
        }
        echo "</div>
        </div>
        </div>
        </div>";
      }
      ?>
    </div>
  </div>
</body>
</html>
