<?php
include '../assets/config.php';
if (isset($_POST['id'])) {
  $id=$_POST['id'];
  echo "<input type='hidden' class='form-control' name='id' id='addToOwnerID' value='$id' required>
  <div class='input-group'>
  <span class='input-group-addon dog'>Dog Name</span>
  <input type='text' class='form-control' name='dogName' maxlength='255' id='addDogName' required>
  </div>
  <div class='input-group'>
  <span class='input-group-addon contract'>Client Registration</span>
  <select class='form-control' id='addClientRegistration' name='clientRegistration' required>
  <option value='' disabled selected>Select Status</option>
  <option value='Incomplete'>Incomplete</option>
  <option value='Complete'>Complete</option>
  <option value='Exempt'>Exempt</option>
  </select>
  </div>
  <div class='input-group'>
  <span class='input-group-addon contract'>Daycare Contract</span>
  <select class='form-control' id='addDaycareContract' name='daycareContract' required>
  <option value='' disabled selected>Select Status</option>
  <option value='Incomplete'>Incomplete</option>
  <option value='Complete'>Complete</option>
  <option value='Exempt'>Exempt</option>
  </select>
  </div>
  <div class='input-group'>
  <span class='input-group-addon day'>Preferred Days</span>
  <div class='preferred-days'>
  <div class='row'>
  <div class='col-sm-6'>
  <div class='input-group'>
  <input type='checkbox' id='addMondays' name='addMondays' value='Yes'>
  <label for='addMondays'>Mondays</label>
  </div>
  </div>
  <div class='col-sm-6'>
  <div class='input-group'>
  <input type='checkbox' id='addTuesdays' name='addTuesdays' value='Yes'>
  <label for='addTuesdays'>Tuesdays</label>
  </div>
  </div>
  <div class='col-sm-6'>
  <div class='input-group'>
  <input type='checkbox' id='addWednesdays' name='addWednesdays' value='Yes'>
  <label for='addWednesdays'>Wednesdays</label>
  </div>
  </div>
  <div class='col-sm-6'>
  <div class='input-group'>
  <input type='checkbox' id='addThursdays' name='addThursdays' value='Yes'>
  <label for='addThursdays'>Thursdays</label>
  </div>
  </div>
  <div class='col-sm-6'>
  <div class='input-group'>
  <input type='checkbox' id='addFridays' name='addFridays' value='Yes'>
  <label for='addFridays'>Fridays</label>
  </div>
  </div>
  </div>
  </div>
  </div>";
  $sql_vaccines="SELECT vaccineID, vaccineTitle, maxMonthsAhead FROM vaccines ORDER BY vaccineTitle";
  $result_vaccines=$conn->query($sql_vaccines);
  while ($row_vaccines=$result_vaccines->fetch_assoc()) {
    $vaccineID=$row_vaccines['vaccineID'];
    $vaccineTitle=mysqli_real_escape_string($conn, $row_vaccines['vaccineTitle']);
    $maxMonthsAhead=$row_vaccines['maxMonthsAhead'];
    $maxDueDate=date('Y-m-d', strtotime('today + ' . $maxMonthsAhead . ' months'));
    echo "<div class='input-group'>
    <span class='input-group-addon vaccine'>$vaccineTitle</span>
    <input type='date' class='form-control' name='vaccine$vaccineID' id='addVaccine$vaccineID' max='$maxDueDate'>
    </div>";
  }
  echo "<div class='input-group'>
  <span class='input-group-addon report-card'>Report Cards</span>
  <div class='report-cards'>
  <div class='row'>
  <div class='col-sm-6'>
  <div class='input-group'>
  <input type='checkbox' id='addAssessmentDayReportCard' name='assessmentDayReportCard' value='Yes'>
  <label for='addAssessmentDayReportCard'>Assessment Day</label>
  </div>
  </div>
  <div class='col-sm-6'>
  <div class='input-group'>
  <input type='checkbox' id='addFirstDayReportCard' name='firstDayReportCard' value='Yes'>
  <label for='addFirstDayReportCard'>First Day</label>
  </div>
  </div>
  <div class='col-sm-6'>
  <div class='input-group'>
  <input type='checkbox' id='addSecondDayReportCard' name='secondDayReportCard' value='Yes'>
  <label for='addSecondDayReportCard'>Second Day</label>
  </div>
  </div>
  <div class='col-sm-6'>
  <div class='input-group'>
  <input type='checkbox' id='addThirdDayReportCard' name='thirdDayReportCard' value='Yes'>
  <label for='addThirdDayReportCard'>Third Day</label>
  </div>
  </div>
  </div>
  </div>
  </div>";
}
?>
