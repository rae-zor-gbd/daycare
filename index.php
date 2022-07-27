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
              <span class='input-group-addon owner'></span>
              <input type='text' class='form-control' name='last-name' maxlength='255' id='newLastName' placeholder='Last Name' required>
            </div>
            <div class='input-group'>
              <span class='input-group-addon owner'></span>
              <input type='text' class='form-control' name='primary-owner' maxlength='255' id='newPrimaryOwner' placeholder='Primary Owner' required>
            </div>
            <div class='input-group'>
              <span class='input-group-addon owner'></span>
              <input type='text' class='form-control' name='secondary-owner' maxlength='255' placeholder='Secondary Owner' id='newSecondaryOwner'>
            </div>
            <div class='input-group'>
              <span class='input-group-addon email'></span>
              <input type='email' class='form-control' name='email' maxlength='255' placeholder='Email Address' id='newEmail'>
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
</body>
</html>
