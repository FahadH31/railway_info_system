<?php
session_start();

// Load environment variables
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Database connection using .env variables
$host = $_ENV['MYSQLHOST'];
$port = $_ENV['MYSQLPORT'];
$user = $_ENV['MYSQLUSER'];
$password = $_ENV['MYSQLPASSWORD'];
$db_name = $_ENV['MYSQLDATABASE'];

$conn = mysqli_connect("$host:$port", $user, $password, $db_name);

if (!$conn) {
    echo 'Connection error: ' . mysqli_connect_error();
}
?>
