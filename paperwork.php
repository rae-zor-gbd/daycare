<?php
include 'assets/config.php';
$sql_registrations_emails="SELECT email FROM dogs d JOIN owners o USING (ownerID) JOIN emails e USING (ownerID) WHERE clientRegistration='Incomplete' AND daycareContract IN ('Complete', 'Exempt') GROUP BY lastName, primaryOwner, email ORDER BY lastName, primaryOwner, email";
$result_registrations_emails=$conn->query($sql_registrations_emails);
if ($result_registrations_emails->num_rows>0) {
  $registrationsEmails=array();
  while ($row_registrations_emails=$result_registrations_emails->fetch_assoc()) {
    $email=htmlspecialchars($row_registrations_emails['email'], ENT_QUOTES);
    array_push($registrationsEmails, $email);
    $registrationsEmailsList=implode(', ', $registrationsEmails);
  }
}
$sql_contracts_emails="SELECT email FROM dogs d JOIN owners o USING (ownerID) JOIN emails e USING (ownerID) WHERE daycareContract='Incomplete' GROUP BY lastName, primaryOwner, email ORDER BY lastName, primaryOwner, email";
$result_contracts_emails=$conn->query($sql_contracts_emails);
if ($result_contracts_emails->num_rows>0) {
  $contractsEmails=array();
  while ($row_contracts_emails=$result_contracts_emails->fetch_assoc()) {
    $email=htmlspecialchars($row_contracts_emails['email'], ENT_QUOTES);
    array_push($contractsEmails, $email);
    $contractsEmailsList=implode(', ', $contractsEmails);
  }
}
?>
<!DOCTYPE html>
<html lang='en'>
  <head>
    <title>Incomplete Paperwork</title>
    <?php include 'assets/header.php'; ?>
    <script type='text/javascript'>
      function loadContracts(){
        $.ajax({
          url:'/ajax/load-contracts.php',
          type:'POST',
          cache:false,
          data:{},
          success:function(data){
            if (data) {
              $('#contracts-panels').empty();
              $('#contracts-panels').append(data);
              loadCounts();
            }
          }
        });
      }
      function loadCounts() {
        $('#registrations-count').empty();
        var registrationsCount=$('#registrations-panels').find('.panel').length;
        $('#registrations-count').append(registrationsCount);
        $('#contracts-count').empty();
        var contractsCount=$('#contracts-panels').find('.panel').length;
        $('#contracts-count').append(contractsCount);
      }
      function loadRegistrations(){
        $.ajax({
          url:'/ajax/load-registrations.php',
          type:'POST',
          cache:false,
          data:{},
          success:function(data){
            if (data) {
              $('#registrations-panels').empty();
              $('#registrations-panels').append(data);
              loadCounts();
            }
          }
        });
      }
      $(document).ready(function(){
        $('#paperwork').addClass('active');
        loadRegistrations();
        loadContracts();
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
        $(document).on('click', '#complete-contract-button', function() {
          var id=$(this).data('id');
          $.ajax({
            url:'ajax/complete-daycare-contract.php',
            type:'POST',
            cache:false,
            data:{id:id},
            success:function(response){
              $('#panel-contract-'+id).remove();
              loadCounts();
            }
          });
        });
        $(document).on('click', '#complete-registration-button', function() {
          var id=$(this).data('id');
          $.ajax({
            url:'ajax/complete-client-registration.php',
            type:'POST',
            cache:false,
            data:{id:id},
            success:function(response){
              $('#panel-registration-'+id).remove();
              loadCounts();
            }
          });
        });
      });
    </script>
  </head>
  <body>
    <?php include 'assets/navbar.php'; ?>
    <div class='container-fluid paperwork-container'>
      <h3 class='paperwork-header'>Incomplete Client Registrations
        <span class='paperwork-count' id='registrations-count'>0</span>
        <?php
        if ($result_registrations_emails->num_rows>0) {
          echo "<button type='button' class='button-email' id='registrations-email-button' data-email='$registrationsEmailsList' title='Copy All Email Addresses to Clipboard'></button>";
        } else {
          echo "<button type='button' class='button-email disabled' id='registrations-email-button' data-email='' title='No Emails' disabled></button>";
        }
        ?>
      </h3>
      <div class='paperwork-panels' id='registrations-panels'></div>
    </div>
    <div class='container-fluid paperwork-container'>
      <h3 class='paperwork-header'>Incomplete Daycare Contracts
        <span class='paperwork-count' id='contracts-count'>0</span>
        <?php
        if ($result_contracts_emails->num_rows>0) {
          echo "<button type='button' class='button-email' id='contracts-email-button' data-email='$contractsEmailsList' title='Copy All Email Addresses to Clipboard'></button>";
        } else {
          echo "<button type='button' class='button-email disabled' id='contracts-email-button' data-email='' title='No Emails' disabled></button>";
        }
        ?>
      </h3>
      <div class='paperwork-panels' id='contracts-panels'></div>
    </div>
  </body>
</html>
