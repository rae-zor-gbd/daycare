<?php
include 'assets/config.php';
if (isset($_GET['search']) AND $_GET['search']!=='') {
  $search=mysqli_real_escape_string($conn, $_GET['search']);
} else {
  $search='';
}
?>
<!DOCTYPE html>
<html lang='en'>
  <head>
    <title>Daycare Packages</title>
    <?php include 'assets/header.php'; ?>
    <script type='text/javascript'>
      function loadDogs(owner){
        $.ajax({
          url:'/ajax/load-dogs.php',
          type:'POST',
          cache:false,
          data:{owner:owner},
          success:function(data){
            if (data) {
              $('#dogs-'+owner).empty();
              $('#dogs-'+owner).append(data);
            }
          }
        });
      }
      function loadOwners(page, search='<?php echo $search; ?>'){
        $.ajax({
          url:'/ajax/load-owners.php',
          type:'POST',
          cache:false,
          data:{page:page, search:search},
          success:function(data){
            if (data) {
              $('#pagination').remove();
              $('#panel-owners').append(data);
            }
          }
        });
      }
      function loadPackages(owner){
        $.ajax({
          url:'/ajax/load-packages.php',
          type:'POST',
          cache:false,
          data:{owner:owner},
          success:function(data){
            if (data) {
              $('#packages-'+owner).empty();
              $('#packages-'+owner).append(data);
            }
          }
        });
      }
      $(document).ready(function(){
        $('#packages').addClass('active');
        loadOwners();
        $(document).on('click', '.load-more', function(){
          $('.load-more').html('Loading');
          var pId=$(this).data('id');
          loadOwners(pId);
        });
        $(document).on('click', '#add-dog-button', function() {
          var id=$(this).data('id');
          $.ajax({
            url:'ajax/load-add-dog-form.php',
            type:'POST',
            cache:false,
            data:{id:id},
            success:function(response){
              $('#addDogModalBody').append(response);
            }
          });
        });
        $('#addDog').click(function (e) {
          e.preventDefault();
          var id=document.getElementById('addToOwnerID').value;
          var dogName=document.getElementById('addDogName').value;
          var clientRegistration=document.getElementById('addClientRegistration').value;
          var daycareContract=document.getElementById('addDaycareContract').value;
          var vetID=document.getElementById('addVet').value;
          <?php
          $sql_all_vaccines="SELECT vaccineID FROM vaccines ORDER BY vaccineID";
          $result_all_vaccines=$conn->query($sql_all_vaccines);
          $vaccines=array();
          while ($row_all_vaccines=$result_all_vaccines->fetch_assoc()) {
            $vaccineID=$row_all_vaccines['vaccineID'];
            array_push($vaccines, 'vaccine' . $vaccineID);
            echo "var vaccine$vaccineID=document.getElementById('addVaccine$vaccineID').value;";
          }
          ?>
          if (document.getElementById('addMondays').checked==true) {
            var reserveMondays='Yes';
          } else {
            var reserveMondays='No';
          }
          if (document.getElementById('addTuesdays').checked==true) {
            var reserveTuesdays='Yes';
          } else {
            var reserveTuesdays='No';
          }
          if (document.getElementById('addWednesdays').checked==true) {
            var reserveWednesdays='Yes';
          } else {
            var reserveWednesdays='No';
          }
          if (document.getElementById('addThursdays').checked==true) {
            var reserveThursdays='Yes';
          } else {
            var reserveThursdays='No';
          }
          if (document.getElementById('addFridays').checked==true) {
            var reserveFridays='Yes';
          } else {
            var reserveFridays='No';
          }
          if (id!='' && dogName!='' && clientRegistration!='' && daycareContract!='' && vetID!='') {
            $.ajax({
              url:'ajax/add-new-dog.php',
              type:'POST',
              cache:false,
              data:{id:id, dogName:dogName, clientRegistration:clientRegistration, daycareContract:daycareContract, vetID:vetID<?php foreach ($vaccines as $vaccineDate) { echo ", $vaccineDate:$vaccineDate"; }?>, reserveMondays:reserveMondays, reserveTuesdays:reserveTuesdays, reserveWednesdays:reserveWednesdays, reserveThursdays:reserveThursdays, reserveFridays:reserveFridays},
              success:function(response){
                $('#addDogModal').modal('hide');
                $('#addDogModalBody').empty();
                $('#dogs-'+id).empty();
                $('#dogs-'+id).append(loadDogs(id));
              }
            });
          } else {
            loadIncompleteFormAlert('#addDogModalBody');
          }
        });
        $(document).on('click', '#add-dog-notes-button', function() {
          var id=$(this).data('id');
          var owner=$(this).data('owner');
          $.ajax({
            url:'ajax/load-add-dog-notes-form.php',
            type:'POST',
            cache:false,
            data:{id:id, owner:owner},
            success:function(response){
              $('#addDogNotesModalBody').append(response);
            }
          });
        });
        $('#addDogNotes').click(function (e) {
          e.preventDefault();
          var dogID=document.getElementById('addDogNotesID').value;
          var ownerID=document.getElementById('addDogNotesOwnerID').value;
          var dogNotes=document.getElementById('addDogNotesBox').value;
          if (dogID!='' && ownerID!='' && dogNotes!='') {
            $.ajax({
              url:'ajax/add-dog-notes.php',
              type:'POST',
              cache:false,
              data:{dogID:dogID, dogNotes:dogNotes},
              success:function(response){
                $('#addDogNotesModal').modal('hide');
                $('#addDogNotesModalBody').empty();
                $('#dogs-'+ownerID).empty();
                $('#dogs-'+ownerID).append(loadDogs(ownerID));
              }
            });
          } else {
            loadIncompleteFormAlert('#addDogNotesModalBody');
          }
        });
        $(document).on('click', '.add-new-owner', function() {
          $.ajax({
            url:'ajax/load-add-owner-form.php',
            type:'POST',
            cache:false,
            data:{},
            success:function(response){
              $('#addOwnerModalBody').append(response);
            }
          });
        });
        $('#addNewOwner').click(function (e) {
          e.preventDefault();
          var lastName=document.getElementById('newLastName').value;
          var primaryOwner=document.getElementById('newPrimaryOwner').value;
          var secondaryOwner=document.getElementById('newSecondaryOwner').value;
          var primaryCell=document.getElementById('newPrimaryCell').value;
          var secondaryCell=document.getElementById('newSecondaryCell').value;
          var homePhone=document.getElementById('newHomePhone').value;
          var primaryEmail=document.getElementById('newPrimaryEmail').value;
          var secondaryEmail=document.getElementById('newSecondaryEmail').value;
          var tertiaryEmail=document.getElementById('newTertiaryEmail').value;
          if (lastName!='' && primaryOwner!='') {
            $.ajax({
              url:'ajax/add-new-owner.php',
              type:'POST',
              cache:false,
              data:{lastName:lastName, primaryOwner:primaryOwner, secondaryOwner:secondaryOwner, primaryCell:primaryCell, secondaryCell:secondaryCell, homePhone:homePhone, primaryEmail:primaryEmail, secondaryEmail:secondaryEmail, tertiaryEmail:tertiaryEmail},
              success:function(response){
                $('#panel-owners').empty();
                loadOwners();
                $('#addNewOwnerModal').modal('hide');
                $('#addOwnerModalBody').empty();
              }
            });
          } else {
            loadIncompleteFormAlert('#addOwnerModalBody');
          }
        });
        $(document).on('click', '#add-package-button', function() {
          var id=$(this).data('id');
          $.ajax({
            url:'ajax/load-add-package-form.php',
            type:'POST',
            cache:false,
            data:{id:id},
            success:function(response){
              $('#addPackageModalBody').append(response);
            }
          });
        });
        $('#addPackage').click(function (e) {
          e.preventDefault();
          var ownerID=document.getElementById('addPackageToOwnerID').value;
          var packageID=document.getElementById('addDaycarePackage').value;
          var startDate=document.getElementById('addPackageStartDate').value;
          if (ownerID!='' && packageID!='') {
            $.ajax({
              url:'ajax/add-new-package.php',
              type:'POST',
              cache:false,
              data:{ownerID:ownerID, packageID:packageID, startDate:startDate},
              success:function(response){
                $('#addPackageModal').modal('hide');
                $('#addPackageModalBody').empty();
                $('#packages-'+ownerID).empty();
                $('#packages-'+ownerID).append(loadPackages(ownerID));
              }
            });
          } else {
            loadIncompleteFormAlert('#addPackageModalBody');
          }
        });
        $(document).on('click', '#add-package-notes-button', function() {
          var id=$(this).data('id');
          var owner=$(this).data('owner');
          $.ajax({
            url:'ajax/load-add-package-notes-form.php',
            type:'POST',
            cache:false,
            data:{id:id, owner:owner},
            success:function(response){
              $('#addPackageNotesModalBody').append(response);
            }
          });
        });
        $('#addPackageNotes').click(function (e) {
          e.preventDefault();
          var packageID=document.getElementById('addPackageNotesID').value;
          var ownerID=document.getElementById('addPackageNotesForOwnerID').value;
          var packageNotes=document.getElementById('addPackageNotesBox').value;
          if (packageID!='' && ownerID!='' && packageNotes!='') {
            $.ajax({
              url:'ajax/add-package-notes.php',
              type:'POST',
              cache:false,
              data:{packageID:packageID, ownerID:ownerID, packageNotes:packageNotes},
              success:function(response){
                $('#addPackageNotesModal').modal('hide');
                $('#addPackageNotesModalBody').empty();
                $('#packages-'+ownerID).empty();
                $('#packages-'+ownerID).append(loadPackages(ownerID));
              }
            });
          } else {
            loadIncompleteFormAlert('#addPackageNotesModalBody');
          }
        });
        $(document).on('click', '#add-dog-reservation-button', function() {
          var id=$(this).data('id');
          $.ajax({
            url:'ajax/load-add-reservation-form.php',
            type:'POST',
            cache:false,
            data:{id:id},
            success:function(response){
              $('#addReservationModalBody').append(response);
            }
          });
        });
        $('#addReservation').click(function (e) {
          e.preventDefault();
          var dogID=document.getElementById('addReservationID').value;
          var ownerID=document.getElementById('addReservationOwnerID').value;
          var date=document.getElementById('addReservationDate').value;
          if (dogID!='' && ownerID!='' && date!='') {
            $.ajax({
              url:'ajax/add-reservation.php',
              type:'POST',
              cache:false,
              data:{dogID:dogID, date:date},
              success:function(response){
                $('#addReservationModal').modal('hide');
                $('#addReservationModalBody').empty();
                $('#dogs-'+ownerID).empty();
                $('#dogs-'+ownerID).append(loadDogs(ownerID));
              }
            });
          } else {
            loadIncompleteFormAlert('#addReservationModalBody');
          }
        });
        $(document).on('click', '.button-decrease', function() {
          var id=$(this).data('id');
          var daysLeft=$(this).data('days');
          var decreaseDays=daysLeft-1;
          var daysLeftWarning=$(this).data('warning');
          $.ajax({
            url:'ajax/decrease-package-days.php',
            type:'POST',
            cache:false,
            data:{id:id},
            success:function(response){
              $('#days-left-count-'+id).empty();
              $('#days-left-count-'+id).append(decreaseDays);
              $('#decrease-package-days-button-'+id).data('days', decreaseDays);
              if (decreaseDays==0) {
                $('#decrease-package-days-button-'+id).hide();
                $('#package-status-'+id).empty();
                $('#package-status-'+id).append('Out of Days');
                $('#days-left-'+id).removeClass('text-warning').addClass('text-danger');
                $('#panel-package-'+id).removeClass('panel-warning').addClass('panel-danger');
                $('#days-left-plural-'+id).show();
              } else if (decreaseDays<=daysLeftWarning) {
                $('#days-left-'+id).removeClass('text-success').addClass('text-warning');
                $('#panel-package-'+id).removeClass('panel-success').addClass('panel-warning');
                if (decreaseDays==1) {
                  $('#days-left-plural-'+id).hide();
                }
              } else if (decreaseDays==1) {
                $('#days-left-plural-'+id).hide();
              }
            }
          });
        });
        $(document).on('click', '#delete-dog-button', function() {
          var id=$(this).data('id');
          $.ajax({
            url:'ajax/load-delete-dog-form.php',
            type:'POST',
            cache:false,
            data:{id:id},
            success:function(response){
              $('#deleteDogModalBody').append(response);
            }
          });
        });
        $('#deleteDog').click(function (e) {
          e.preventDefault();
          var id=document.getElementById('deleteID').value;
          $.ajax({
            url:'ajax/delete-dog.php',
            type:'POST',
            cache:false,
            data:{id:id},
            success:function(response){
              $('#panel-dog-'+id).remove();
              $('#deleteDogModal').modal('hide');
              $('#deleteDogModalBody').empty();
            }
          });
        });
        $(document).on('click', '#delete-owner-button', function() {
          var id=$(this).data('id');
          $.ajax({
            url:'ajax/load-delete-owner-form.php',
            type:'POST',
            cache:false,
            data:{id:id},
            success:function(response){
              $('#deleteOwnerModalBody').append(response);
            }
          });
        });
        $('#deleteOwner').click(function (e) {
          e.preventDefault();
          var id=document.getElementById('deleteID').value;
          $.ajax({
            url:'ajax/delete-owner.php',
            type:'POST',
            cache:false,
            data:{id:id},
            success:function(response){
              $('#panel-owner-'+id).remove();
              $('#deleteOwnerModal').modal('hide');
              $('#deleteOwnerModalBody').empty();
            }
          });
        });
        $(document).on('click', '#delete-package-button', function() {
          var id=$(this).data('id');
          $.ajax({
            url:'ajax/load-delete-package-form.php',
            type:'POST',
            cache:false,
            data:{id:id},
            success:function(response){
              $('#deletePackageModalBody').append(response);
            }
          });
        });
        $('#deletePackage').click(function (e) {
          e.preventDefault();
          var id=document.getElementById('deletePackageID').value;
          $.ajax({
            url:'ajax/delete-package.php',
            type:'POST',
            cache:false,
            data:{id:id},
            success:function(response){
              $('#panel-package-'+id).remove();
              $('#deletePackageModal').modal('hide');
              $('#deletePackageModalBody').empty();
            }
          });
        });
        $(document).on('click', '#delete-reservation-button', function() {
          var id=$(this).data('id');
          var date=$(this).data('date');
          $.ajax({
            url:'ajax/load-delete-reservation-form.php',
            type:'POST',
            cache:false,
            data:{id:id, date:date},
            success:function(response){
              $('#deleteReservationModalBody').append(response);
            }
          });
        });
        $('#deleteReservation').click(function (e) {
          e.preventDefault();
          var id=document.getElementById('deleteID').value;
          var date=document.getElementById('deleteReservationDate').value;
          $.ajax({
            url:'ajax/delete-reservation.php',
            type:'POST',
            cache:false,
            data:{id:id, date:date},
            success:function(response){
              $('#deleteReservationModal').modal('hide');
              $('#deleteReservationModalBody').empty();
              $('#reservation-'+id+'-'+date).remove();
            }
          });
        });
        $(document).on('click', '#edit-dog-button', function() {
          var id=$(this).data('id');
          var owner=$(this).data('owner');
          $.ajax({
            url:'ajax/load-edit-dog-form.php',
            type:'POST',
            cache:false,
            data:{id:id, owner:owner},
            success:function(response){
              $('#editDogModalBody').append(response);
            }
          });
        });
        $('#editDog').click(function (e) {
          e.preventDefault();
          var id=document.getElementById('editDogID').value;
          var owner=document.getElementById('editDogForOwnerID').value;
          var dogName=document.getElementById('editDogName').value;
          var clientRegistration=document.getElementById('editClientRegistration').value;
          var daycareContract=document.getElementById('editDaycareContract').value;
          var vetID=document.getElementById('editVet').value;
          <?php
          $sql_all_vaccines_edit="SELECT vaccineID FROM vaccines ORDER BY vaccineID";
          $result_all_vaccines_edit=$conn->query($sql_all_vaccines_edit);
          $vaccines_edit=array();
          while ($row_all_vaccines_edit=$result_all_vaccines_edit->fetch_assoc()) {
            $vaccineEditID=$row_all_vaccines_edit['vaccineID'];
            array_push($vaccines_edit, 'vaccine' . $vaccineEditID);
            echo "var vaccine$vaccineEditID=document.getElementById('editVaccine$vaccineEditID').value;";
          }
          ?>
          if (document.getElementById('editMondays').checked==true) {
            var reserveMondays='Yes';
          } else {
            var reserveMondays='No';
          }
          if (document.getElementById('editTuesdays').checked==true) {
            var reserveTuesdays='Yes';
          } else {
            var reserveTuesdays='No';
          }
          if (document.getElementById('editWednesdays').checked==true) {
            var reserveWednesdays='Yes';
          } else {
            var reserveWednesdays='No';
          }
          if (document.getElementById('editThursdays').checked==true) {
            var reserveThursdays='Yes';
          } else {
            var reserveThursdays='No';
          }
          if (document.getElementById('editFridays').checked==true) {
            var reserveFridays='Yes';
          } else {
            var reserveFridays='No';
          }
          if (id!='' && owner!='' && dogName!='' && clientRegistration!='' && daycareContract!='' && vetID!='') {
            $.ajax({
              url:'ajax/edit-dog.php',
              type:'POST',
              cache:false,
              data:{id:id, owner:owner, dogName:dogName, clientRegistration:clientRegistration, daycareContract:daycareContract, vetID:vetID<?php foreach ($vaccines_edit as $vaccineEditDate) { echo ", $vaccineEditDate:$vaccineEditDate"; }?>, reserveMondays:reserveMondays, reserveTuesdays:reserveTuesdays, reserveWednesdays:reserveWednesdays, reserveThursdays:reserveThursdays, reserveFridays:reserveFridays},
              success:function(response){
                $('#editDogModal').modal('hide');
                $('#editDogModalBody').empty();
                $('#dogs-'+owner).empty();
                $('#dogs-'+owner).append(loadDogs(owner));
              }
            });
          } else {
            loadIncompleteFormAlert('#editDogModalBody');
          }
        });
        $(document).on('click', '#edit-owner-button', function() {
          var id=$(this).data('id');
          $.ajax({
            url:'ajax/load-edit-owner-form.php',
            type:'POST',
            cache:false,
            data:{id:id},
            success:function(response){
              $('#editOwnerModalBody').append(response);
            }
          });
        });
        $('#editOwner').click(function (e) {
          e.preventDefault();
          var id=document.getElementById('editID').value;
          var lastName=document.getElementById('editLastName').value;
          var primaryOwner=document.getElementById('editPrimaryOwner').value;
          var secondaryOwner=document.getElementById('editSecondaryOwner').value;
          var primaryCell=document.getElementById('editPrimaryCell').value;
          var secondaryCell=document.getElementById('editSecondaryCell').value;
          var homePhone=document.getElementById('editHomePhone').value;
          var primaryEmail=document.getElementById('editPrimaryEmail').value;
          var secondaryEmail=document.getElementById('editSecondaryEmail').value;
          var tertiaryEmail=document.getElementById('editTertiaryEmail').value;
          if (id!='' && lastName!='' && primaryOwner!='') {
            $.ajax({
              url:'ajax/edit-owner.php',
              type:'POST',
              cache:false,
              data:{id:id, lastName:lastName, primaryOwner:primaryOwner, secondaryOwner:secondaryOwner, primaryCell:primaryCell, secondaryCell:secondaryCell, homePhone:homePhone, primaryEmail:primaryEmail, secondaryEmail:secondaryEmail, tertiaryEmail:tertiaryEmail},
              success:function(response){
                $('#editOwnerModal').modal('hide');
                $('#editOwnerModalBody').empty();
                $('#panel-owners').empty();
                loadOwners();
              }
            });
          } else {
            loadIncompleteFormAlert('#editOwnerModalBody');
          }
        });
        $(document).on('click', '#edit-package-button', function() {
          var id=$(this).data('id');
          var owner=$(this).data('owner');
          $.ajax({
            url:'ajax/load-edit-package-form.php',
            type:'POST',
            cache:false,
            data:{id:id, owner:owner},
            success:function(response){
              $('#editPackageModalBody').append(response);
            }
          });
        });
        $('#editPackage').click(function (e) {
          e.preventDefault();
          var id=document.getElementById('editPackageID').value;
          var owner=document.getElementById('editPackageForOwnerID').value;
          var currentStatus=document.getElementById('editPackageCurrentStatus').value;
          var status=document.getElementById('editPackageStatus').value;
          var daysLeft=document.getElementById('editPackageDaysLeft').value;
          var startDate=document.getElementById('editPackageStartDate').value;
          var expirationDate=document.getElementById('editPackageExpirationDate').value;
          if (id!='' && owner!='' && currentStatus!='' && status!='') {
            $.ajax({
              url:'ajax/edit-package.php',
              type:'POST',
              cache:false,
              data:{id:id, owner:owner, currentStatus:currentStatus, status:status, daysLeft:daysLeft, startDate:startDate, expirationDate:expirationDate},
              success:function(response){
                $('#editPackageModal').modal('hide');
                $('#editPackageModalBody').empty();
                $('#packages-'+owner).empty();
                $('#packages-'+owner).append(loadPackages(owner));
              }
            });
          } else {
            loadIncompleteFormAlert('#editPackageModalBody');
          }
        });
        $('.modal').on('hidden.bs.modal', function(){
          $('#addDogModalBody').empty();
          $('#addDogNotesModalBody').empty();
          $('#addOwnerModalBody').empty();
          $('#addPackageModalBody').empty();
          $('#addPackageNotesModalBody').empty();
          $('#addReservationModalBody').empty();
          $('#deleteDogModalBody').empty();
          $('#deleteOwnerModalBody').empty();
          $('#deletePackageModalBody').empty();
          $('#deleteReservationModalBody').empty();
          $('#editDogModalBody').empty();
          $('#editOwnerModalBody').empty();
          $('#editPackageModalBody').empty();
        });
      });
    </script>
  </head>
  <body>
    <?php include 'assets/navbar.php'; ?>
    <form action='' method='post' spellcheck='false' autocomplete='off' id='addNewOwnerForm'>
      <div class='modal fade' id='addNewOwnerModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'></button>
              <h4 class='modal-title'>Add New Owner</h4>
            </div>
            <div class='modal-body' id='addOwnerModalBody'></div>
            <div class='modal-footer'>
              <button type='submit' class='btn btn-primary' id='addNewOwner'>Submit</button>
              <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </form>
    <div class='nav-footer'>
      <button type='button' class='btn btn-default nav-button add-new-owner' data-toggle='modal' data-target='#addNewOwnerModal' data-backdrop='static' >Add New Owner</button>
    </div>
    <div class='container-fluid'>
      <form action='' spellcheck='false' autocomplete='off'>
        <div class='form-group'>
          <input type='text' name='search' class='form-control search-panel' placeholder='Search'>
        </div>
      </form>
      <div class='panel-group' id='panel-owners'></div>
    </div>
    <form action='' method='post' spellcheck='false' autocomplete='off' id='addDogForm'>
      <div class='modal fade' id='addDogModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'></button>
              <h4 class='modal-title'>Add New Dog</h4>
            </div>
            <div class='modal-body' id='addDogModalBody'></div>
            <div class='modal-footer'>
              <button type='submit' class='btn btn-primary' id='addDog'>Submit</button>
              <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </form>
    <form action='' method='post' spellcheck='false' autocomplete='off' id='addDogNotesForm'>
      <div class='modal fade' id='addDogNotesModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'></button>
              <h4 class='modal-title'>Add Dog Notes</h4>
            </div>
            <div class='modal-body' id='addDogNotesModalBody'></div>
            <div class='modal-footer'>
              <button type='submit' class='btn btn-primary' id='addDogNotes'>Submit</button>
              <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </form>
    <form action='' method='post' spellcheck='false' autocomplete='off' id='addPackageForm'>
      <div class='modal fade' id='addPackageModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'></button>
              <h4 class='modal-title'>Add New Package</h4>
            </div>
            <div class='modal-body' id='addPackageModalBody'></div>
            <div class='modal-footer'>
              <button type='submit' class='btn btn-primary' id='addPackage'>Submit</button>
              <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </form>
    <form action='' method='post' spellcheck='false' autocomplete='off' id='addPackageNotesForm'>
      <div class='modal fade' id='addPackageNotesModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'></button>
              <h4 class='modal-title'>Add Package Notes</h4>
            </div>
            <div class='modal-body' id='addPackageNotesModalBody'></div>
            <div class='modal-footer'>
              <button type='submit' class='btn btn-primary' id='addPackageNotes'>Submit</button>
              <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </form>
    <form action='' method='post' spellcheck='false' autocomplete='off' id='addReservationForm'>
      <div class='modal fade' id='addReservationModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'></button>
              <h4 class='modal-title'>Add Reservation</h4>
            </div>
            <div class='modal-body' id='addReservationModalBody'></div>
            <div class='modal-footer'>
              <button type='submit' class='btn btn-primary' id='addReservation'>Submit</button>
              <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </form>
    <form action='' method='post' spellcheck='false' autocomplete='off' id='deleteDogForm'>
      <div class='modal fade' id='deleteDogModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'></button>
              <h4 class='modal-title'>Delete Dog</h4>
            </div>
            <div class='modal-body' id='deleteDogModalBody'></div>
            <div class='modal-footer'>
              <button type='submit' class='btn btn-danger' id='deleteDog'>Delete</button>
              <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </form>
    <form action='' method='post' spellcheck='false' autocomplete='off' id='deleteOwnerForm'>
      <div class='modal fade' id='deleteOwnerModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'></button>
              <h4 class='modal-title'>Delete Owner</h4>
            </div>
            <div class='modal-body' id='deleteOwnerModalBody'></div>
            <div class='modal-footer'>
              <button type='submit' class='btn btn-danger' id='deleteOwner'>Delete</button>
              <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </form>
    <form action='' method='post' spellcheck='false' autocomplete='off' id='deletePackageForm'>
      <div class='modal fade' id='deletePackageModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'></button>
              <h4 class='modal-title'>Delete Package</h4>
            </div>
            <div class='modal-body' id='deletePackageModalBody'></div>
            <div class='modal-footer'>
              <button type='submit' class='btn btn-danger' id='deletePackage'>Delete</button>
              <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </form>
    <form action='' method='post' spellcheck='false' autocomplete='off' id='deleteReservationForm'>
      <div class='modal fade' id='deleteReservationModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'></button>
              <h4 class='modal-title'>Delete Reservation</h4>
            </div>
            <div class='modal-body' id='deleteReservationModalBody'></div>
            <div class='modal-footer'>
              <button type='submit' class='btn btn-danger' id='deleteReservation'>Delete</button>
              <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </form>
    <form action='' method='post' spellcheck='false' autocomplete='off' id='editDogForm'>
      <div class='modal fade' id='editDogModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'></button>
              <h4 class='modal-title'>Edit Dog</h4>
            </div>
            <div class='modal-body' id='editDogModalBody'></div>
            <div class='modal-footer'>
              <button type='submit' class='btn btn-primary' id='editDog'>Submit</button>
              <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </form>
    <form action='' method='post' spellcheck='false' autocomplete='off' id='editOwnerForm'>
      <div class='modal fade' id='editOwnerModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'></button>
              <h4 class='modal-title'>Edit Owner</h4>
            </div>
            <div class='modal-body' id='editOwnerModalBody'></div>
            <div class='modal-footer'>
              <button type='submit' class='btn btn-primary' id='editOwner'>Submit</button>
              <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </form>
    <form action='' method='post' spellcheck='false' autocomplete='off' id='editPackageForm'>
      <div class='modal fade' id='editPackageModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'></button>
              <h4 class='modal-title'>Edit Package</h4>
            </div>
            <div class='modal-body' id='editPackageModalBody'></div>
            <div class='modal-footer'>
              <button type='submit' class='btn btn-primary' id='editPackage'>Submit</button>
              <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </body>
</html>
