<?php
include '../assets/config.php';
if (isset($_POST['reservationDate'])) {
    $reservationDate=date('Y-m-d', strtotime($_POST['reservationDate']));
    echo "<table class='table table-hover table-condensed'>
    <thead>
    <tr>
    <th>" . date('l, F j, Y', strtotime($reservationDate)) . "</th>
    </tr>
    </thead>
    <tbody>";
    $sql_reservations="SELECT dogID, dogName, lastName FROM dogs d JOIN owners o USING (ownerID) JOIN reservations r USING (dogID) WHERE reservationDate='$reservationDate' UNION SELECT dogID, dogName, lastName FROM dogs d JOIN owners o USING (ownerID) WHERE reserveMondays='Yes' ORDER BY lastName, dogName";
    $result_reservations=$conn->query($sql_reservations);
    if ($result_reservations->num_rows>0) {
        while ($row_reservations=$result_reservations->fetch_assoc()) {
            $dogName=mysqli_real_escape_string($conn, $row_reservations['dogName']);
            echo "<tr>
            <td>$lastName $dogName</td>
            </tr>";
        }
    } else {
        echo "<tr>
        <td><em class='text-muted'>No daycare reservations</em></td>
        </tr>";
    }
    echo "</tbody>
    </table>";
}
?>