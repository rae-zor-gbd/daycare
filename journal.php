<?php include 'assets/config.php'; ?>
<!DOCTYPE html>
<html lang='en'>
<head>
  <title>Daycare Journal</title>
  <?php include 'assets/header.php'; ?>
  <style>
  :root {
    --journal-box-height:93px;
  }
  .journal-box {
    border-right:1px solid;
    padding-left:5px;
    text-transform:uppercase;
  }
  .journal-box-date, .journal-box-initials {
    border-left:1px solid;
    height:var(--journal-box-height);
  }
  .journal-box-initials {
    border-top:1px solid;
  }
  .journal-box-notes {
    height:calc(var(--journal-box-height)*2);
  }
  .journal-header {
    text-align:right;
  }
  .journal-header:first-of-type {
    margin-top:0;
  }
  .normal {
    font-weight:400;
  }
  .row {
    border-bottom:5px solid;
  }
  .row:first-of-type {
    border-top:5px solid;
  }
  </style>
</head>
<body>
  <?php
  $sql_list="SELECT lastName, dogName FROM dogs d JOIN owners o USING (ownerID) WHERE journalEntry='Yes' ORDER BY lastName, dogName";
  $result_list=$conn->query($sql_list);
  while ($row_list=$result_list->fetch_assoc()) {
    $lastName=mysqli_real_escape_string($conn, $row_list['lastName']);
    $dogName=mysqli_real_escape_string($conn, $row_list['dogName']);
    echo "<div>
    <h2 class='journal-header'>$dogName <span class='normal'>$lastName</span></h2>";
    for ($x=0; $x<5; $x++) {
      echo "<div class='row row-no-gutters'>
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
