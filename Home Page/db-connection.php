<?php
$conn = mysqli_connect('localhost:3306', 'root', 'fahad1306', 'RailwaySystemWebsite');

if (!$conn) {
  echo 'Connection error: ' . mysqli_connect_error();
}
?>
