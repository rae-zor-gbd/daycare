<?php
include 'assets/config.php';
?>
<!DOCTYPE html>
<html lang='en'>
  <head>
    <title>Daycare Report Cards</title>
    <?php include 'assets/header.php'; ?>
    <script type='text/javascript'>
      $(document).ready(function(){
        $('#report-cards').addClass('active');
        $('#assessment-day-count').empty();
        var assessmentDayCount=$('#assessment-day-panels').find('.panel').length;
        $('#assessment-day-count').append(assessmentDayCount);
        $('#first-day-count').empty();
        var firstDayCount=$('#first-day-panels').find('.panel').length;
        $('#first-day-count').append(firstDayCount);
        $('#second-day-count').empty();
        var secondDayCount=$('#second-day-panels').find('.panel').length;
        $('#second-day-count').append(secondDayCount);
        $('#third-day-count').empty();
        var thirdDayCount=$('#third-day-panels').find('.panel').length;
        $('#third-day-count').append(thirdDayCount);
      });
    </script>
  </head>
  <body>
    <?php include 'assets/navbar.php'; ?>
    <div class='container-fluid report-card-container'>
      <h3 class='report-card-header'>Incomplete Assessment-Day Report Cards
        <span class='report-card-count' id='assessment-day-count'>0</span>
      </h3>
      <div class='report-card-panels' id='assessment-day-panels'>
        <?php
        $sql_assessment_day="SELECT lastName, dogName FROM dogs d JOIN owners o USING (ownerID) WHERE assessmentDayReportCard='No' AND firstDayReportCard='No' AND secondDayReportCard='No' AND thirdDayReportCard='No' ORDER BY lastName, dogName";
        $result_assessment_day=$conn->query($sql_assessment_day);
        if ($result_assessment_day->num_rows>0) {
          while ($row_assessment_day=$result_assessment_day->fetch_assoc()) {
            $assessmentDayLastName=mysqli_real_escape_string($conn, $row_assessment_day['lastName']);
            $assessmentDayDogName=mysqli_real_escape_string($conn, $row_assessment_day['dogName']);
            echo "<div class='panel panel-danger'>
            <div class='panel-heading dog-heading'>$assessmentDayLastName, <strong>$assessmentDayDogName</strong></div>
            </div>";
          }
        }
        ?>
      </div>
    </div>
    <div class='container-fluid report-card-container'>
      <h3 class='report-card-header'>Incomplete First-Day Report Cards
        <span class='report-card-count' id='first-day-count'>0</span>
      </h3>
      <div class='report-card-panels' id='first-day-panels'>
        <?php
        $sql_first_day="SELECT lastName, dogName FROM dogs d JOIN owners o USING (ownerID) WHERE firstDayReportCard='No' AND secondDayReportCard='No' AND thirdDayReportCard='No' AND dogID NOT IN (SELECT dogID FROM dogs d JOIN owners o USING (ownerID) WHERE assessmentDayReportCard='No' AND firstDayReportCard='No' AND secondDayReportCard='No' AND thirdDayReportCard='No') ORDER BY lastName, dogName";
        $result_first_day=$conn->query($sql_first_day);
        if ($result_first_day->num_rows>0) {
          while ($row_first_day=$result_first_day->fetch_assoc()) {
            $firstDayLastName=mysqli_real_escape_string($conn, $row_first_day['lastName']);
            $firstDayDogName=mysqli_real_escape_string($conn, $row_first_day['dogName']);
            echo "<div class='panel panel-danger'>
            <div class='panel-heading dog-heading'>$firstDayLastName, <strong>$firstDayDogName</strong></div>
            </div>";
          }
        }
        ?>
      </div>
    </div>
    <div class='container-fluid report-card-container'>
      <h3 class='report-card-header'>Incomplete Second-Day Report Cards
        <span class='report-card-count' id='second-day-count'>0</span>
      </h3>
      <div class='report-card-panels' id='second-day-panels'>
        <?php
        $sql_second_day="SELECT lastName, dogName FROM dogs d JOIN owners o USING (ownerID) WHERE secondDayReportCard='No' AND thirdDayReportCard='No' AND dogID NOT IN (SELECT dogID FROM dogs d JOIN owners o USING (ownerID) WHERE firstDayReportCard='No' AND secondDayReportCard='No' AND thirdDayReportCard='No') ORDER BY lastName, dogName";
        $result_second_day=$conn->query($sql_second_day);
        if ($result_second_day->num_rows>0) {
          while ($row_second_day=$result_second_day->fetch_assoc()) {
            $secondDayLastName=mysqli_real_escape_string($conn, $row_second_day['lastName']);
            $secondDayDogName=mysqli_real_escape_string($conn, $row_second_day['dogName']);
            echo "<div class='panel panel-danger'>
            <div class='panel-heading dog-heading'>$secondDayLastName, <strong>$secondDayDogName</strong></div>
            </div>";
          }
        }
        ?>
      </div>
    </div>
    <div class='container-fluid report-card-container'>
      <h3 class='report-card-header'>Incomplete Third-Day Report Cards
        <span class='report-card-count' id='third-day-count'>0</span>
      </h3>
      <div class='report-card-panels' id='third-day-panels'>
        <?php
        $sql_third_day="SELECT lastName, dogName FROM dogs d JOIN owners o USING (ownerID) WHERE thirdDayReportCard='No' AND dogID NOT IN (SELECT dogID FROM dogs d JOIN owners o USING (ownerID) WHERE secondDayReportCard='No' AND thirdDayReportCard='No') ORDER BY lastName, dogName";
        $result_third_day=$conn->query($sql_third_day);
        if ($result_third_day->num_rows>0) {
          while ($row_third_day=$result_third_day->fetch_assoc()) {
            $thirdDayLastName=mysqli_real_escape_string($conn, $row_third_day['lastName']);
            $thirdDayDogName=mysqli_real_escape_string($conn, $row_third_day['dogName']);
            echo "<div class='panel panel-danger'>
            <div class='panel-heading dog-heading'>$thirdDayLastName, <strong>$thirdDayDogName</strong></div>
            </div>";
          }
        }
        ?>
      </div>
    </div>
  </body>
</html>
