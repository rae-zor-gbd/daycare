<?php include 'assets/config.php'; ?>
<!DOCTYPE html>
<html lang='en'>
<head>
  <title>Daycare Journal</title>
  <?php include 'assets/header.php'; ?>
  <style>
  @page {
    margin-bottom:0.25in;
    margin-top:0.25in;
  }
  @page :left {
    margin-left:0.25in;
    margin-right:1in;
  }
  @page :right {
    margin-left:1in;
    margin-right:0.25in;
  }
  </style>
  <script>
  $(document).ready(function(){
    window.print();
  });
  </script>
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
    for ($journalPages=0; $journalPages<2; $journalPages++) {
      echo "<div>
      <h2 class='journal-header'>$dogName <span class='normal'>$lastName</span></h2>";
      for ($journalRows=0; $journalRows<5; $journalRows++) {
        echo "<div class='row row-no-gutters journal-row'>
        <div class='col-xs-2'>
        <div class='journal-box journal-box-date'>Date</div>
        <div class='journal-box journal-box-initials'>Initials</div>
        </div>
        <div class='col-xs-4'>
        <div class='journal-box journal-box-notes'>Activities
        <p>☐ Lick Mat</p>
        <p>☐ Snuffle Mat</p>
        <p>☐ Magic Mat</p>
        <p>☐ Puzzle</p>
        <p>☐ Find It Games</p>
        <p>☐ Daily Doggy Exercises</p>
        </div>
        </div>
        <div class='col-xs-6'>
        <div class='journal-box journal-box-notes'>Enrichment Notes</div>
        </div>
        </div>";
      }
      echo "</div>";
    }
  }
  ?>
</body>
</html>
