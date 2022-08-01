<?php
include 'config.php';
if (isset($_POST['page']) AND $_POST['page']!='') {
  $page=$_POST['page'];
} else {
  $page=0;
}
$limit=12;
$offset=($page-1)*$limit;
$previous=$page-1;
$next=$page+1;
if (isset($_POST['search']) AND $_POST['search']!=='') {
  $search=mysqli_real_escape_string($conn, $_POST['search']);
  $sql_total_owners="SELECT COUNT(ownerID) AS totalResults FROM ((SELECT ownerID, lastName, primaryOwner, secondaryOwner FROM owners WHERE lastName LIKE '%$search%' OR primaryOwner LIKE '%$search%' OR secondaryOwner LIKE '%$search%') UNION (SELECT o.ownerID, lastName, primaryOwner, secondaryOwner FROM owners o JOIN dogs d USING (ownerID) WHERE dogName LIKE '%$search%' OR lastName LIKE '%$search%' OR primaryOwner LIKE '%$search%' OR secondaryOwner LIKE '%$search%' GROUP BY o.ownerID, lastName, primaryOwner, secondaryOwner)) r";
  $sql_owners="(SELECT ownerID, lastName, primaryOwner, secondaryOwner FROM owners WHERE lastName LIKE '%$search%' OR primaryOwner LIKE '%$search%' OR secondaryOwner LIKE '%$search%') UNION (SELECT o.ownerID, lastName, primaryOwner, secondaryOwner FROM owners o JOIN dogs d USING (ownerID) WHERE dogName LIKE '%$search%' OR lastName LIKE '%$search%' OR primaryOwner LIKE '%$search%' OR secondaryOwner LIKE '%$search%' GROUP BY o.ownerID, lastName, primaryOwner, secondaryOwner) ORDER BY lastName, primaryOwner LIMIT $page, $limit";
} else {
  $sql_total_owners="SELECT COUNT(ownerID) AS totalResults FROM owners";
  $sql_owners="SELECT ownerID, lastName, primaryOwner, secondaryOwner FROM owners ORDER BY lastName, primaryOwner LIMIT $page, $limit";
}
$result_total_owners=$conn->query($sql_total_owners);
$row_total_owners=$result_total_owners->fetch_assoc();
$totalOwners=$row_total_owners['totalResults'];
$result_owners=$conn->query($sql_owners);
if ($result_owners->num_rows>0) {
  while ($row_owners=$result_owners->fetch_assoc()) {
    $page++;
    $ownerID=$row_owners['ownerID'];
    $lastName=mysqli_real_escape_string($conn, $row_owners['lastName']);
    $primaryOwner=mysqli_real_escape_string($conn, $row_owners['primaryOwner']);
    $secondaryOwner=mysqli_real_escape_string($conn, $row_owners['secondaryOwner']);
    echo "<div class='panel panel-default panel-owner' id='panel-owner-$ownerID'>
    <a class='collapsed' data-toggle='collapse' data-parent='#panel-owners' data-target='#owner-$ownerID'>
    <div class='panel-heading'>
    <div class='panel-title owner-heading'>
    <strong>" . stripslashes($lastName) . "</strong>, " . stripslashes($primaryOwner);
    if (isset($secondaryOwner) AND $secondaryOwner!='') {
      echo " & " . stripslashes($secondaryOwner);
    }
    echo "<div class='panel-arrow'></div>
    </div>
    </div>
    </a>
    <div id='owner-$ownerID' class='panel-collapse collapse'>
    <div class='panel-body' id='dogs-$ownerID'></div>
    <script type='text/javascript'>
    loadDogs($ownerID);
    </script>
    <div class='panel-body' id='packages-$ownerID'></div>
    <script type='text/javascript'>
    loadPackages($ownerID);
    </script>
    <div class='panel-footer'>
    <button type='button' class='button-delete' id='delete-owner-button' data-toggle='modal' data-target='#deleteOwnerModal' data-id='$ownerID' title='Delete Owner'></button>
    <button type='button' class='button-edit' id='edit-owner-button' data-toggle='modal' data-target='#editOwnerModal' data-id='$ownerID' title='Edit Owner'></button>
    <button type='button' class='button-dog' id='add-dog-button' data-toggle='modal' data-target='#addDogModal' data-id='$ownerID' title='Add New Dog'></button>
    <button type='button' class='button-package' id='add-package-button' data-toggle='modal' data-target='#addPackageModal' data-id='$ownerID' title='Add New Package'></button>
    </div>
    </div>
    </div>";
  }
  if ($page!=$totalOwners) {
    echo "<button type='button' class='btn btn-default load-more' id='pagination' data-id='{$page}'>Load More</button>";
  }
}
?>
