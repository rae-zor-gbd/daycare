<?php include 'assets/config.php'; ?>
<!DOCTYPE html>
<html lang='en'>
<head>
  <title>Daycare Journal</title>
  <?php include 'assets/header.php'; ?>
</head>
<body>
  <?php
  if (isset($_GET['id']) AND $_GET['id']!='') {
    $journalID=$_GET['id'];
    $sql_list="SELECT lastName, dogName FROM dogs d JOIN owners o USING (ownerID) WHERE dogID='$journalID'";
  } else {
    $sql_list="SELECT lastName, dogName FROM dogs d JOIN owners o USING (ownerID) WHERE journalEntry='Yes' ORDER BY lastName, dogName";
  }
  $result_list=$conn->query($sql_list);
  while ($row_list=$result_list->fetch_assoc()) {
    $lastName=mysqli_real_escape_string($conn, $row_list['lastName']);
    $dogName=mysqli_real_escape_string($conn, $row_list['dogName']);
    echo "<div>
    <h2 class='journal-header'>$dogName <span class='normal'>$lastName</span></h2>";
    for ($x=0; $x<5; $x++) {
      echo "<div class='row row-no-gutters journal-row'>
      <div class='col-xs-3'>
      <div class='journal-box journal-box-date'>Date</div>
      <div class='journal-box journal-box-initials'>Initials</div>
      </div>
      <div class='col-xs-9'>
      <div class='journal-box journal-box-notes'>Enrichment Notes</div>
      </div>
      </div>";
    }
    echo "</div>";
  }
  ?>
</body>
</html>
