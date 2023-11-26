<?php
include '../assets/config.php';
$sql_registrations="SELECT dogID, lastName, dogName FROM dogs d JOIN owners o USING (ownerID) WHERE clientRegistration='Incomplete' ORDER BY lastName, dogName";
$result_registrations=$conn->query($sql_registrations);
if ($result_registrations->num_rows>0) {
  while ($row_registrations=$result_registrations->fetch_assoc()) {
    $dogID=$row_registrations['dogID'];
    $lastName=mysqli_real_escape_string($conn, $row_registrations['lastName']);
    $dogName=mysqli_real_escape_string($conn, $row_registrations['dogName']);
    echo "<div class='panel panel-danger' id='panel-registration-$dogID'>
    <div class='panel-heading dog-heading'>$lastName, <strong>$dogName</strong><button type='button' class='button-complete' id='complete-registration-button' data-id='$dogID' title='Mark Client Registration as Complete'></button></div>
    </div>";
  }
}
?>
