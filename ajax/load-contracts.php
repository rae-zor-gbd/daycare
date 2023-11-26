<?php
include '../assets/config.php';
$sql_contracts="SELECT dogID, lastName, dogName FROM dogs d JOIN owners o USING (ownerID) WHERE daycareContract='Incomplete' ORDER BY lastName, dogName";
$result_contracts=$conn->query($sql_contracts);
if ($result_contracts->num_rows>0) {
  while ($row_contracts=$result_contracts->fetch_assoc()) {
    $dogID=$row_contracts['dogID'];
    $lastName=mysqli_real_escape_string($conn, $row_contracts['lastName']);
    $dogName=mysqli_real_escape_string($conn, $row_contracts['dogName']);
    echo "<div class='panel panel-danger' id='panel-contract-$dogID'>
    <div class='panel-heading dog-heading'>$lastName, <strong>$dogName</strong><button type='button' class='button-complete' id='complete-contract-button' data-id='$dogID' title='Mark Daycare Contract as Complete'></button></div>
    </div>";
  }
}
?>
