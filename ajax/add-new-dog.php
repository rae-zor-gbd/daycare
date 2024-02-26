<?php
include '../assets/config.php';
if (isset($_POST['id']) AND isset($_POST['dogName']) AND isset($_POST['clientRegistration']) AND isset($_POST['daycareContract']) AND isset($_POST['vetID']) AND isset($_POST['reserveMondays']) AND isset($_POST['reserveTuesdays']) AND isset($_POST['reserveWednesdays']) AND isset($_POST['reserveThursdays']) AND isset($_POST['reserveFridays']) AND isset($_POST['assessmentDayReportCard']) AND isset($_POST['firstDayReportCard']) AND isset($_POST['secondDayReportCard']) AND isset($_POST['thirdDayReportCard'])) {
  $ownerID=$_POST['id'];
  $dogName=mysqli_real_escape_string($conn, trim($_POST['dogName']));
  $clientRegistration=mysqli_real_escape_string($conn, $_POST['clientRegistration']);
  $daycareContract=mysqli_real_escape_string($conn, $_POST['daycareContract']);
  $vetID=$_POST['vetID'];
  $reserveMondays=$_POST['reserveMondays'];
  $reserveTuesdays=$_POST['reserveTuesdays'];
  $reserveWednesdays=$_POST['reserveWednesdays'];
  $reserveThursdays=$_POST['reserveThursdays'];
  $reserveFridays=$_POST['reserveFridays'];
  $assessmentDayReportCard=$_POST['assessmentDayReportCard'];
  $firstDayReportCard=$_POST['firstDayReportCard'];
  $secondDayReportCard=$_POST['secondDayReportCard'];
  $thirdDayReportCard=$_POST['thirdDayReportCard'];
  $sql_add_dog="INSERT INTO dogs (dogName, ownerID, clientRegistration, daycareContract, vetID, reserveMondays, reserveTuesdays, reserveWednesdays, reserveThursdays, reserveFridays, assessmentDayReportCard, firstDayReportCard, secondDayReportCard, thirdDayReportCard) VALUES ('$dogName', '$ownerID', '$clientRegistration', '$daycareContract', '$vetID', '$reserveMondays', '$reserveTuesdays', '$reserveWednesdays', '$reserveThursdays', '$reserveFridays', '$assessmentDayReportCard', '$firstDayReportCard', '$secondDayReportCard', '$thirdDayReportCard')";
  $conn->query($sql_add_dog);
  $sql_next_dog_id="SELECT dogID FROM dogs WHERE dogName='$dogName' AND ownerID='$ownerID' ORDER BY dogID DESC LIMIT 1";
  $result_next_dog_id=$conn->query($sql_next_dog_id);
  $row_next_dog_id=$result_next_dog_id->fetch_assoc();
  $dogID=$row_next_dog_id['dogID'];
  $sql_vaccines="SELECT vaccineID FROM vaccines ORDER BY vaccineID";
  $result_vaccines=$conn->query($sql_vaccines);
  while ($row_vaccines=$result_vaccines->fetch_assoc()) {
    $vaccineID=$row_vaccines['vaccineID'];
    if (isset($_POST['vaccine' . $vaccineID]) AND $_POST['vaccine' . $vaccineID]!='') {
      $dueDate=date('Y-m-d', strtotime($_POST['vaccine' . $vaccineID]));
      $sql_add_vaccine="INSERT INTO dogs_vaccines (dogID, vaccineID, dueDate) VALUES ('$dogID', '$vaccineID', '$dueDate')";
      $conn->query($sql_add_vaccine);
    }
  }
}
?>
