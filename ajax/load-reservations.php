<?php
include '../assets/config.php';
if (isset($_POST['reservationDate'])) {
    $reservationDate=date('Y-m-d', strtotime($_POST['reservationDate']));
    $dayOfWeek=date('l', strtotime($reservationDate));
    $previousDate=date('Y-m-d', strtotime($reservationDate . ' - 1 weekday'));
    $nextDate=date('Y-m-d', strtotime($reservationDate . ' + 1 weekday'));
    echo "<table class='table table-hover table-condensed'>
    <thead>
    <tr>
    <th colspan='3'>
    <a href='/reservations/$previousDate'>
    <button class='previous-button' title='Previous Date'></button>
    </a>";
    echo date('l, F j, Y', strtotime($reservationDate));
    echo "<a href='/reservations/$nextDate'>
    <button class='next-button' title='Next Date'></button>
    </a>
    </th>
    </tr>
    </thead>
    <tbody>";
    $sql_confirmed="SELECT dogID FROM reservations WHERE reservationDate='$reservationDate'";
    $result_confirmed=$conn->query($sql_confirmed);
    $confirmations=$result_confirmed->fetch_array(MYSQLI_NUM);
    $sql_reservations="SELECT dogID, dogName, lastName FROM dogs d JOIN owners o USING (ownerID) JOIN reservations r USING (dogID) WHERE reservationDate='$reservationDate' UNION SELECT dogID, dogName, lastName FROM dogs d JOIN owners o USING (ownerID) WHERE";
    if ($dayOfWeek=='Monday') {
        $sql_reservations.=" reserveMondays='Yes'";
    } elseif ($dayOfWeek=='Tuesday') {
        $sql_reservations.=" reserveTuesdays='Yes'";
    } elseif ($dayOfWeek=='Wednesday') {
        $sql_reservations.=" reserveWednesdays='Yes'";
    } elseif ($dayOfWeek=='Thursday') {
        $sql_reservations.=" reserveThursdays='Yes'";
    } elseif ($dayOfWeek=='Friday') {
        $sql_reservations.=" reserveFridays='Yes'";
    }
    $sql_reservations.="ORDER BY lastName, dogName";
    $result_reservations=$conn->query($sql_reservations);
    if ($result_reservations->num_rows>0) {
        while ($row_reservations=$result_reservations->fetch_assoc()) {
            $dogID=$row_reservations['dogID'];
            $dogName=mysqli_real_escape_string($conn, $row_reservations['dogName']);
            $lastName=mysqli_real_escape_string($conn, $row_reservations['lastName']);
            echo "<tr>
            <td>$lastName, <strong>$dogName</strong></td>
            <td>";
            if (in_array($dogID, $confirmations)) {
                echo "<span class='label label-success'>Confirmed</span>";
            }
            echo "</td>
            <td style='text-align:right; width:75px;'>";
            if (in_array($dogID, $confirmations)) {
                echo "<button type='button' class='button-edit' id='edit-reservation-button' data-toggle='modal' data-target='#editReservationModal' data-id='$dogID' data-date='$reservationDate' data-backdrop='static' title='Edit Reservation'></button>
                <button type='button' class='button-delete' id='delete-reservation-button' data-toggle='modal' data-target='#deleteReservationModal' data-id='$dogID' data-date='$reservationDate' data-backdrop='static' title='Delete Reservation'></button>";
            } else {
                echo "<button type='button' class='button-check' id='confirm-reservation-button' data-toggle='modal' data-target='#ConfirmReservationModal' data-id='$dogID' data-backdrop='static' title='Confirm Reservation'></button>";
            }
            echo "</td>
            </tr>";
        }
    } else {
        echo "<tr>
        <td><em class='text-muted'>No daycare reservations</em></td>
        <td></td>
        </tr>";
    }
    echo "</tbody>
    </table>";
}
?>