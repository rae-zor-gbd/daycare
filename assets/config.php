<?php
$servername='localhost';
$server_username='root';
$server_password='root';
$dbname='raezor_gbd';
$conn=new mysqli($servername, $server_username, $server_password, $dbname);
$conn->set_charset('utf8mb4');
if ($conn->connect_error) {
  die('Connection failed: ' . $conn->connect_error);
}
date_default_timezone_set('America/New_York');
?>
