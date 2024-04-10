<?php include 'assets/config.php'; ?>
<!DOCTYPE html>
<html lang='en'>
  <head>
    <title>Daycare Reminders</title>
    <?php include 'assets/header.php'; ?>
    <script type='text/javascript'>
      function loadReminders(){
        $.ajax({
          url:'/ajax/load-reminders.php',
          type:'POST',
          cache:false,
          data:{},
          success:function(data){
            if (data) {
              $('#table-reminders').empty();
              $('#table-reminders').append(data);
            }
          }
        });
      }
      $(document).ready(function(){
        $('#reminders').addClass('active');
        loadReminders();
        $(document).on('click', '.button-email', function() {
          var email=$(this).data('email');
          const textarea=document.createElement('textarea');
          textarea.textContent=email;
          document.body.appendChild(textarea);
          textarea.select();
          document.execCommand('copy');
          document.body.removeChild(textarea);
          alert("Copied owner emails to clipboard: " + email);
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
                loadReminders();
              }
            });
          } else {
            loadIncompleteFormAlert('#addPackageNotesModalBody');
          }
        });
        $(document).on('click', '#add-vaccine-notes-button', function() {
          var id=$(this).data('id');
          var owner=$(this).data('owner');
          $.ajax({
            url:'ajax/load-add-dog-notes-form.php',
            type:'POST',
            cache:false,
            data:{id:id, owner:owner},
            success:function(response){
              $('#addVaccineNotesModalBody').append(response);
            }
          });
        });
        $('#addVaccineNotes').click(function (e) {
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
                $('#addVaccineNotesModal').modal('hide');
                $('#addVaccineNotesModalBody').empty();
                loadReminders();
              }
            });
          } else {
            loadIncompleteFormAlert('#addVaccineNotesModalBody');
          }
        });
        $('.modal').on('hidden.bs.modal', function(){
          $('#addPackageNotesModalBody').empty();
          $('#addVaccineNotesModalBody').empty();
        });
      });
    </script>
  </head>
  <body>
    <?php include 'assets/navbar.php'; ?>
    <div class='container-fluid'>
      <div class='table-container'>
        <table class='table table-hover table-condensed'>
          <thead>
            <tr>
              <th>Owners & Dogs</th>
              <th>Package Reminders</th>
              <th>Vaccine Reminders</th>
            </tr>
          </thead>
          <tbody id='table-reminders'></tbody>
        </table>
      </div>
    </div>
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
    <form action='' method='post' spellcheck='false' autocomplete='off' id='addVaccineNotesForm'>
      <div class='modal fade' id='addVaccineNotesModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'></button>
              <h4 class='modal-title'>Add Vaccine Notes</h4>
            </div>
            <div class='modal-body' id='addVaccineNotesModalBody'></div>
            <div class='modal-footer'>
              <button type='submit' class='btn btn-primary' id='addVaccineNotes'>Submit</button>
              <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </body>
</html>
