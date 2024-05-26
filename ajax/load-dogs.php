<?php
include '../assets/config.php';
if (isset($_POST['owner']) AND $_POST['owner']!='') {
  $ownerID=$_POST['owner'];
  $sql_dogs="SELECT dogID, dogName, clientRegistration, daycareContract, notes, assessmentDayReportCard, firstDayReportCard, secondDayReportCard, thirdDayReportCard FROM dogs WHERE ownerID='$ownerID' ORDER BY dogName";
  $result_dogs=$conn->query($sql_dogs);
  while ($row_dogs=$result_dogs->fetch_assoc()) {
    $dogID=$row_dogs['dogID'];
    $dogName=mysqli_real_escape_string($conn, $row_dogs['dogName']);
    $clientRegistration=mysqli_real_escape_string($conn, $row_dogs['clientRegistration']);
    $daycareContract=mysqli_real_escape_string($conn, $row_dogs['daycareContract']);
    $dogNotes=nl2br($row_dogs['notes']);
    $assessmentDayReportCard=$row_dogs['assessmentDayReportCard'];
    $firstDayReportCard=$row_dogs['firstDayReportCard'];
    $secondDayReportCard=$row_dogs['secondDayReportCard'];
    $thirdDayReportCard=$row_dogs['thirdDayReportCard'];
    $sql_current_fecal="SELECT * FROM dogs d JOIN dogs_vaccines dv USING (dogID) JOIN vaccines v USING (vaccineID) WHERE ownerID='$ownerID' AND vaccineTitle='Fecal' AND dueDate>=DATE_ADD(DATE(NOW()), INTERVAL sendReminder DAY)";
    $result_current_fecal=$conn->query($sql_current_fecal);
    $sql_vaccines_not_given="SELECT vaccineTitle FROM vaccines WHERE requirementStatus='Required'";
    if ($result_current_fecal->num_rows>0) {
      $sql_vaccines_not_given.=" AND vaccineTitle!='Fecal'";
    }
    $sql_vaccines_not_given.=" AND vaccineID NOT IN (SELECT vaccineID FROM dogs_vaccines WHERE dogID='$dogID') ORDER BY vaccineTitle";
    $result_vaccines_not_given=$conn->query($sql_vaccines_not_given);
    $sql_vaccines="SELECT vaccineTitle, dueDate FROM dogs d JOIN dogs_vaccines dv USING (dogID) JOIN vaccines USING (vaccineID) WHERE ownerID='$ownerID' AND dogID='$dogID' AND requirementStatus='Required'";
    if ($result_current_fecal->num_rows>0) {
      $sql_vaccines.="AND vaccineTitle!='Fecal'";
    }
    $sql_vaccines.="AND dueDate<=DATE_ADD(DATE(NOW()), INTERVAL sendReminder DAY) ORDER BY dueDate, vaccineTitle";
    $result_vaccines=$conn->query($sql_vaccines);
    echo "<div class='panel panel-";
    if ($result_vaccines->num_rows>0 OR $result_vaccines_not_given->num_rows>0) {
      $sql_past_due_vaccines="SELECT vaccineTitle, dueDate FROM dogs d JOIN dogs_vaccines dv USING (dogID) JOIN vaccines USING (vaccineID) WHERE ownerID='$ownerID' AND dogID='$dogID' AND requirementStatus='Required'";
      if ($result_current_fecal->num_rows>0) {
        $sql_past_due_vaccines.="AND vaccineTitle!='Fecal'";
      }
      $sql_past_due_vaccines.="AND dueDate<DATE(NOW()) ORDER BY dueDate, vaccineTitle";
      $result_past_due_vaccines=$conn->query($sql_past_due_vaccines);
      $sql_vaccines_due_soon="SELECT vaccineTitle, dueDate FROM dogs d JOIN dogs_vaccines dv USING (dogID) JOIN vaccines USING (vaccineID) WHERE ownerID='$ownerID' AND dogID='$dogID' AND requirementStatus='Required'";
      if ($result_current_fecal->num_rows>0) {
        $sql_vaccines_due_soon.="AND vaccineTitle!='Fecal'";
      }
      $sql_vaccines_due_soon.="AND dueDate>=DATE(NOW()) ORDER BY dueDate, vaccineTitle";
      $result_vaccines_due_soon=$conn->query($sql_vaccines_due_soon);
      if ($result_past_due_vaccines->num_rows>0 OR $result_vaccines_not_given->num_rows>0) {
        echo "danger";
      } elseif ($result_vaccines_due_soon->num_rows>0) {
        echo "warning";
      }
    } else {
      echo "success";
    }
    echo "' id='panel-dog-$dogID'>
    <div class='panel-heading dog-heading'>" . stripslashes($dogName) . "</div>";
    if (stripslashes($clientRegistration)==='Incomplete') {
      echo "<div class='panel-body dog-client-registration-status text-danger'>Incomplete Client Registration</div>";
    }
    if (stripslashes($daycareContract)==='Incomplete') {
      echo "<div class='panel-body dog-daycare-contract-status text-danger'>Incomplete Daycare Contract</div>";
    }
    if ($assessmentDayReportCard=='No' AND $firstDayReportCard=='No' AND $secondDayReportCard=='No' AND $thirdDayReportCard=='No') {
      echo "<div class='panel-body dog-report-card text-danger'>Assessment-Day Report Card</div>";
    } elseif ($firstDayReportCard=='No' AND $secondDayReportCard=='No' AND $thirdDayReportCard=='No') {
      echo "<div class='panel-body dog-report-card text-danger'>First-Day Report Card</div>";
    } elseif ($secondDayReportCard=='No' AND $thirdDayReportCard=='No') {
      echo "<div class='panel-body dog-report-card text-danger'>Second-Day Report Card</div>";
    } elseif ($thirdDayReportCard=='No') {
      echo "<div class='panel-body dog-report-card text-danger'>Third-Day Report Card</div>";
    }
    if ($result_vaccines_not_given->num_rows>0) {
      while ($row_vaccines_not_given=$result_vaccines_not_given->fetch_assoc()) {
        $vaccineTitle=mysqli_real_escape_string($conn, $row_vaccines_not_given['vaccineTitle']);
        echo "<div class='panel-body dog-vaccine-status text-danger'>" . stripslashes($vaccineTitle) . " required</div>";
      }
    }
    if ($result_vaccines->num_rows>0) {
      while ($row_vaccines=$result_vaccines->fetch_assoc()) {
        $vaccineTitle=mysqli_real_escape_string($conn, $row_vaccines['vaccineTitle']);
        $dueDate=strtotime($row_vaccines['dueDate']);
        echo "<div class='panel-body dog-vaccine-status text-";
        if ($dueDate<$today) {
          echo "danger";
        } elseif ($dueDate>=$today) {
          echo "warning";
        }
        echo "'>" . stripslashes($vaccineTitle) . " due " . date('D n/j', $dueDate) . "</div>";
      }
    }
    $sql_reservations="SELECT reservationDate AS date, 'reservation' AS type FROM reservations r JOIN dogs d USING (dogID) WHERE dogID='$dogID' AND reservationDate>=DATE(NOW()) UNION SELECT blockoffDate AS date, 'blockoff' AS type FROM reservations_blockoffs b JOIN dogs d USING (dogID) WHERE dogID='$dogID' AND blockoffDate>=DATE(NOW()) ORDER BY date";
    $result_reservations=$conn->query($sql_reservations);
    while ($row_reservations=$result_reservations->fetch_assoc()) {
      $reservationDate=date('Y-m-d', strtotime($row_reservations['date']));
      $reservationType=$row_reservations['type'];
      if ($reservationType=='reservation') {
        echo "<div class='panel-body dog-reservation' id='reservation-$dogID-$reservationDate'>" . date('l, F j', strtotime($reservationDate)) . "<button type='button' class='button-delete' id='delete-reservation-button' style='float:right;' data-toggle='modal' data-target='#deleteReservationModal' data-id='$dogID' data-date='$reservationDate' data-backdrop='static' title='Delete Reservation'></button></div>";
      } elseif ($reservationType=='blockoff') {
        echo "<div class='panel-body dog-blockoff' id='blockoff-$dogID-$reservationDate'>" . date('l, F j', strtotime($reservationDate)) . "<button type='button' class='button-delete' id='delete-blockoff-button' style='float:right;' data-toggle='modal' data-target='#deleteBlockoffModal' data-id='$dogID' data-date='$reservationDate' data-backdrop='static' title='Delete Blockoff'></button></div>";
      }
    }
    if (isset($dogNotes) AND $dogNotes!=='') {
      echo "<div class='panel-body dog-notes'>" . stripslashes($dogNotes) . "</div>";
    }
    echo "<div class='panel-footer'>
    <button type='button' class='button-delete' id='delete-dog-button' data-toggle='modal' data-target='#deleteDogModal' data-id='$dogID' data-backdrop='static' title='Delete Dog'></button>
    <button type='button' class='button-edit' id='edit-dog-button' data-toggle='modal' data-target='#editDogModal' data-id='$dogID' data-owner='$ownerID' data-backdrop='static' title='Edit Dog'></button>
    <button type='button' class='button-notes' id='add-dog-notes-button' data-toggle='modal' data-target='#addDogNotesModal' data-id='$dogID' data-owner='$ownerID' data-backdrop='static' title='Add Note'></button>
    <a href='/journal/$dogID' target='_blank'><button type='button' class='button-journal' id='enrichment-journal-button' title='Print Enrichment Journal Entry'></button></a>
    <button type='button' class='button-reservation' id='add-dog-reservation-button' data-toggle='modal' data-target='#addReservationModal' data-id='$dogID' data-backdrop='static' title='Add Reservation'></button>
    <button type='button' class='button-blockoff' id='add-dog-blockoff-button' data-toggle='modal' data-target='#addBlockoffModal' data-id='$dogID' data-backdrop='static' title='Add Blockoff'></button>
    </div>
    </div>";
  }
}
?>
