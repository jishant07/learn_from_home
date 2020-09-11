<?php 
date_default_timezone_set('Asia/Kolkata');

$host = "localhost";
$user = "flowrtid_newui";
$pass = "flow@6508#";
$database = "flowrtid_new";

 
$conn = new mysqli($host, $user, $pass, $database);
$conn->set_charset("utf8");
return $conn;
?>


