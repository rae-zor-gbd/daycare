<?php
include '../assets/config.php';
echo "<div class='input-group'>
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
</div>";
?>