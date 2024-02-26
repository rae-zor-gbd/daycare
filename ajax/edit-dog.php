<?php
include '../assets/config.php';
if (isset($_POST['id']) AND isset($_POST['owner']) AND isset($_POST['dogName']) AND isset($_POST['clientRegistration']) AND isset($_POST['daycareContract']) AND isset($_POST['vetID']) AND isset($_POST['reserveMondays']) AND isset($_POST['reserveTuesdays']) AND isset($_POST['reserveWednesdays']) AND isset($_POST['reserveThursdays']) AND isset($_POST['reserveFridays']) AND isset($_POST['assessmentDayReportCard']) AND isset($_POST['firstDayReportCard']) AND isset($_POST['secondDayReportCard']) AND isset($_POST['thirdDayReportCard'])) {
  $dogID=$_POST['id'];
  $ownerID=$_POST['owner'];
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
  $sql_edit_dog="UPDATE dogs SET dogName='$dogName', clientRegistration='$clientRegistration', daycareContract='$daycareContract', vetID='$vetID', reserveMondays='$reserveMondays', reserveTuesdays='$reserveTuesdays', reserveWednesdays='$reserveWednesdays', reserveThursdays='$reserveThursdays', reserveFridays='$reserveFridays', assessmentDayReportCard='$assessmentDayReportCard', firstDayReportCard='$firstDayReportCard', secondDayReportCard='$secondDayReportCard', thirdDayReportCard='$thirdDayReportCard' WHERE dogID='$dogID'";
  $conn->query($sql_edit_dog);
  $sql_remove_vaccines="DELETE FROM dogs_vaccines WHERE dogID='$dogID'";
  $conn->query($sql_remove_vaccines);
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
