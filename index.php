<?php
include 'assets/config.php';
$sql_owners="SELECT * FROM owners ORDER BY lastName, primaryOwner LIMIT 10";
$result_owners=$conn->query($sql_owners);
?>
<!DOCTYPE html>
<html lang='en'>
<head>
  <title>Daycare</title>
  <?php include 'assets/header.php'; ?>
</head>
<body>
  <?php include 'assets/navbar.php'; ?>
  <div class='container'>
    <a href='/'>
      <h1>Daycare</h1>
    </a>
    <form action='' spellcheck='false'>
      <div class='form-group'>
        <input type='text' name='search' class='form-control' placeholder='Search'>
      </div>
    </form>
  </div>
  <div class='container-fluid'>
    <div class='panel-group' id='accordion'>
      <?php
      while ($row_owners=$result_owners->fetch_assoc()) {
        $ownerID=$row_owners['ownerID'];
        echo "<div class='panel panel-default panel-owner'>
        <a class='accordion-toggle' data-toggle='collapse' data-parent='#accordion' data-target='#owner-" . $ownerID . "'>
        <div class='panel-heading'>
        <div class='panel-title'>
        <div class='container'><strong>" . $row_owners['lastName'] . "</strong>, " . $row_owners['primaryOwner'];
        if (isset($row_owners['secondaryOwner'])) {
          echo " & " . $row_owners['secondaryOwner'];
        }
        echo "</div>
        </div>
        </div>
        </a>
        <div id='owner-" . $ownerID . "' class='panel-collapse collapse'>
        <div class='panel-body'>
        <div class='container'>";
        $sql_dogs="SELECT dogID, dogName, daycareContract, notes FROM dogs WHERE ownerID='$ownerID' ORDER BY dogName";
        $result_dogs=$conn->query($sql_dogs);
        while ($row_dogs=$result_dogs->fetch_assoc()) {
          echo $row_dogs['dogName'] . "<br>";
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
