<?php
include 'config.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli('','root','', 'test');
// Check connection
if ($conn->connect_error) {
  echo "Connection failed: " . $conn->connect_error;
  exit;
};
print_r("Successfully Connected! \n");
/*
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
};
echo "Success! \n";
*/
?>
