<?php include 'assets/config.php'; ?>
<!DOCTYPE html>
<html lang='en'>
  <head>
    <title>Vet Requests</title>
    <?php include 'assets/header.php'; ?>
    <script type='text/javascript'>
      function loadRequests(vetID){
        $.ajax({
          url:'/ajax/load-requests.php',
          type:'POST',
          cache:false,
          data:{vetID:vetID},
          success:function(data){
            if (data) {
              $('#vet-'+vetID).empty();
              const trim=data.trim();
              $('#vet-'+vetID).append(trim);
            }
          }
        });
      }
      function loadVets(){
        $.ajax({
          url:'/ajax/load-vets.php',
          type:'POST',
          cache:false,
          data:{},
          success:function(data){
            if (data) {
              $('#panel-vets').empty();
              $('#panel-vets').append(data);
            }
          }
        });
      }
      $(document).ready(function(){
        $('#requests').addClass('active');
        loadVets();
        $(document).on('click', '.button-email', function() {
          var email=$(this).data('email');
          const textarea = document.createElement('textarea');
          textarea.textContent = email;
          document.body.appendChild(textarea);
          textarea.select();
          document.execCommand('copy');
          document.body.removeChild(textarea);
          alert("Copied vet email to clipboard: " + email);
        });
        $(document).on('click', '#add-vaccine-notes-button', function() {
          var id=$(this).data('id');
          var owner=$(this).data('owner');
          var vetID=$(this).data('vet');
          $.ajax({
            url:'ajax/load-add-dog-notes-form.php',
            type:'POST',
            cache:false,
            data:{id:id, owner:owner, vetID:vetID},
            success:function(response){
              $('#addVaccineNotesModalBody').append(response);
            }
          });
        });
        $('#addVaccineNotes').click(function (e) {
          e.preventDefault();
          var dogID=document.getElementById('addDogNotesID').value;
          var ownerID=document.getElementById('addDogNotesOwnerID').value;
          var vetID=document.getElementById('addDogNotesVetID').value;
          var dogNotes=document.getElementById('addDogNotesBox').value;
          if (dogID!='' && ownerID!='' && vetID!='' && dogNotes!='') {
            $.ajax({
              url:'ajax/add-dog-notes.php',
              type:'POST',
              cache:false,
              data:{dogID:dogID, dogNotes:dogNotes},
              success:function(response){
                $('#addVaccineNotesModal').modal('hide');
                $('#addVaccineNotesModalBody').empty();
                loadRequests(vetID);
              }
            });
          } else {
            loadIncompleteFormAlert('#addVaccineNotesModalBody');
          }
        });
        $('.modal').on('hidden.bs.modal', function(){
          $('#addVaccineNotesModalBody').empty();
        });
      });
    </script>
  </head>
  <body>
    <?php include 'assets/navbar.php'; ?>
    <div class='container-fluid'>
      <div class='panel-group' id='panel-vets'></div>
    </div>
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
