<?php
include 'config.php';
if (isset($_POST['id']) AND isset($_POST['dogName']) AND isset($_POST['daycareContract'])) {
  $ownerID=$_POST['id'];
  $dogName=mysqli_real_escape_string($conn, $_POST['dogName']);
  $daycareContract=mysqli_real_escape_string($conn, $_POST['daycareContract']);
  $sql_next_dog_id="SELECT AUTO_INCREMENT AS nextDogID FROM information_schema.TABLES WHERE TABLE_SCHEMA='daycare' AND TABLE_NAME='dogs'";
  $result_next_dog_id=$conn->query($sql_next_dog_id);
  $row_next_dog_id=$result_next_dog_id->fetch_assoc();
  $dogID=$row_next_dog_id['nextDogID'];
  $sql_add_dog="INSERT INTO dogs (dogID, dogName, ownerID, daycareContract) VALUES ('$dogID', '$dogName', '$ownerID', '$daycareContract')";
  $conn->query($sql_add_dog);
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
