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
  <title>Daycare</title>
  <?php include 'assets/header.php'; ?>
  <script type='text/javascript'>
  function loadPackageOwners(page, search='<?php echo $search; ?>'){
    $.ajax({
      url:'/assets/load-package-owners.php',
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
  $(document).ready(function(){
    $('#packages').addClass('active');
    loadPackageOwners();
    $(document).on('click', '.load-more', function(){
      $('.load-more').html('Loading');
      var pId=$(this).data('id');
      loadPackageOwners(pId);
    });
    $('#addNewOwner').click(function (e) {
      e.preventDefault();
      var lastName=document.getElementById('newLastName').value;
      var primaryOwner=document.getElementById('newPrimaryOwner').value;
      var secondaryOwner=document.getElementById('newSecondaryOwner').value;
      var primaryEmail=document.getElementById('newPrimaryEmail').value;
      var secondaryEmail=document.getElementById('newSecondaryEmail').value;
      var tertiaryEmail=document.getElementById('newTertiaryEmail').value;
      $.ajax({
        url:'assets/add-new-owner.php',
        type:'POST',
        cache:false,
        data:{lastName:lastName, primaryOwner:primaryOwner, secondaryOwner:secondaryOwner, primaryEmail:primaryEmail, secondaryEmail:secondaryEmail, tertiaryEmail:tertiaryEmail},
        success:function(response){
          $('#panel-owners').empty();
          loadPackageOwners();
          $('#addNewOwnerModal').modal('hide');
          document.getElementById('addNewOwnerForm').reset();
        }
      });
    });
    $('.modal').on('hidden.bs.modal', function(){
      $('#deleteOwnerModalBody').empty();
      $('#editOwnerModalBody').empty();
      $('#addDogModalBody').empty();
    });
    $(document).on('click', '#delete-owner-button', function() {
      var id=$(this).data('id');
      $.ajax({
        url:'assets/load-delete-owner-form.php',
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
        url:'assets/delete-owner.php',
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
    $(document).on('click', '#edit-owner-button', function() {
      var id=$(this).data('id');
      $.ajax({
        url:'assets/load-edit-owner-form.php',
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
      var primaryEmail=document.getElementById('editPrimaryEmail').value;
      var secondaryEmail=document.getElementById('editSecondaryEmail').value;
      var tertiaryEmail=document.getElementById('editTertiaryEmail').value;
      $.ajax({
        url:'assets/edit-owner.php',
        type:'POST',
        cache:false,
        data:{id:id, lastName:lastName, primaryOwner:primaryOwner, secondaryOwner:secondaryOwner, primaryEmail:primaryEmail, secondaryEmail:secondaryEmail, tertiaryEmail:tertiaryEmail},
        success:function(response){
          $('#editOwnerModal').modal('hide');
          $('#editOwnerModalBody').empty();
          $('#panel-owners').empty();
          loadPackageOwners();
        }
      });
    });
    $(document).on('click', '#add-dog-button', function() {
      var id=$(this).data('id');
      $.ajax({
        url:'assets/load-add-dog-form.php',
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
      var id=document.getElementById('addDogID').value;
      var dogName=document.getElementById('addDogName').value;
      var daycareContract=document.getElementById('addDaycareContract').value;
      <?php
      $sql_all_vaccines="SELECT vaccineID FROM vaccines ORDER BY vaccineTitle";
      $result_all_vaccines=$conn->query($sql_all_vaccines);
      while ($row_all_vaccines=$result_all_vaccines->fetch_assoc()) {
        $vaccineID=$row_all_vaccines['vaccineID'];
        $vaccines=array();
        array_push($vaccines, 'vaccine' . $vaccineID);
        echo "var vaccine$vaccineID=document.getElementById('addVaccine$vaccineID').value;";
      }
      ?>
      $.ajax({
        url:'assets/add-new-dog.php',
        type:'POST',
        cache:false,
        data:{id:id, dogName:dogName, daycareContract:daycareContract<?php foreach ($vaccines as $value) { echo ", $value:$value"; }?>},
        success:function(response){
          $('#addDogModal').modal('hide');
          $('#addDogModalBody').empty();
        }
      });
    });
  });
  </script>
</head>
<body>
  <?php include 'assets/navbar.php'; ?>
  <form action='' method='post' spellcheck='false' id='addNewOwnerForm'>
    <div class='modal fade' id='addNewOwnerModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
      <div class='modal-dialog'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h4 class='modal-title'>Add New Owner</h4>
          </div>
          <div class='modal-body'>
            <div class='input-group'>
              <span class='input-group-addon owner'>Last Name</span>
              <input type='text' class='form-control' name='last-name' maxlength='255' id='newLastName' required>
            </div>
            <div class='input-group'>
              <span class='input-group-addon owner'>Primary Owner</span>
              <input type='text' class='form-control' name='primary-owner' maxlength='255' id='newPrimaryOwner' required>
            </div>
            <div class='input-group'>
              <span class='input-group-addon owner'>Secondary Owner</span>
              <input type='text' class='form-control' name='secondary-owner' maxlength='255' id='newSecondaryOwner'>
            </div>
            <div class='input-group'>
              <span class='input-group-addon email'>Primary Email</span>
              <input type='email' class='form-control' name='primary-email' maxlength='255' id='newPrimaryEmail'>
            </div>
            <div class='input-group'>
              <span class='input-group-addon email'>Secondary Email</span>
              <input type='email' class='form-control' name='secondary-email' maxlength='255' id='newSecondaryEmail'>
            </div>
            <div class='input-group'>
              <span class='input-group-addon email'>Tertiary Email</span>
              <input type='email' class='form-control' name='tertiary-email' maxlength='255' id='newTertiaryEmail'>
            </div>
          </div>
          <div class='modal-footer'>
            <button type='submit' class='btn btn-primary' id='addNewOwner'>Submit</button>
            <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </form>
  <button type='button' class='btn btn-default add-new-owner' data-toggle='modal' data-target='#addNewOwnerModal'>Add New Owner</button>
  <div class='container-fluid'>
    <form action='' spellcheck='false'>
      <div class='form-group'>
        <input type='text' name='search' class='form-control search-panel' placeholder='Search'>
      </div>
    </form>
    <div class='panel-group' id='panel-owners'></div>
  </div>
  <form action='' method='post' id='deleteOwnerForm'>
    <div class='modal fade' id='deleteOwnerModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
      <div class='modal-dialog'>
        <div class='modal-content'>
          <div class='modal-header'>
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
  <form action='' method='post' spellcheck='false' id='editOwnerForm'>
    <div class='modal fade' id='editOwnerModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
      <div class='modal-dialog'>
        <div class='modal-content'>
          <div class='modal-header'>
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
  <form action='' method='post' spellcheck='false' id='addDogForm'>
    <div class='modal fade' id='addDogModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
      <div class='modal-dialog'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h4 class='modal-title'>Add Dog</h4>
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
</body>
</html>
