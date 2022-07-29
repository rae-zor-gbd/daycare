<?php
include 'config.php';
if (isset($_POST['id']) AND isset($_POST['editDogForOwnerID']) AND isset($_POST['editDogName']) AND isset($_POST['editDaycareContract'])) {
  $dogID=$_POST['id'];
  $ownerID=$_POST['editDogForOwnerID'];
  $dogName=mysqli_real_escape_string($conn, $_POST['editDogName']);
  $daycareContract=mysqli_real_escape_string($conn, $_POST['editDaycareContract']);
  $sql_edit_dog="UPDATE dogs SET dogName='$dogName', daycareContract='$daycareContract' WHERE dogID='$dogID'";
  $conn->query($sql_edit_dog);
  $sql_remove_vaccines="DELETE FROM dogs_vaccines WHERE dogID='$dogID'";
  $conn->query($sql_remove_vaccines);
  $sql_vaccines="SELECT vaccineID FROM vaccines ORDER BY vaccineID";
  $result_vaccines=$conn->query($sql_vaccines);
  while ($row_vaccines=$result_vaccines->fetch_assoc()) {
    $vaccineID=$row_vaccines['vaccineID'];
    if (isset($_POST['vaccine' . $vaccineID]) AND $_POST['vaccine' . $vaccineID]!='') {
      $dueDate=$_POST['vaccine' . $vaccineID];
      $sql_add_vaccine="INSERT INTO dogs_vaccines (dogID, vaccineID, dueDate) VALUES ('$dogID', '$vaccineID', '$dueDate')";
      $conn->query($sql_add_vaccine);
    }
  }
}
?>
