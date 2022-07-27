<meta charset='utf-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<link rel='icon' href='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🐾</text></svg>'>
<link rel='stylesheet' href='/css/bootstrap.min.css'>
<script type='text/javascript' src='/js/jquery.min.js'></script>
<script type='text/javascript' src='/js/bootstrap.min.js'></script>
<?php $mainStylesheetTimestamp=filemtime('css/main.css'); ?>
<link rel='stylesheet' href='<?php echo "/css/main.css?v=" . $mainStylesheetTimestamp; ?>'>
<script type='text/javascript'>
function hideLoader() {
  $('#loading').hide();
}
$(window).ready(hideLoader);
</script>
