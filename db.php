<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "assessment_beginner";
$port = 3307;

try {
    $conn = mysqli_connect($host, $user, $pass, $dbname, $port);
} catch (mysqli_sql_exception $e) {
    die("Database connection failed: " . $e->getMessage());
}
 
if (!$conn) {
  die("Database connection failed: " . mysqli_connect_error());
}
?>