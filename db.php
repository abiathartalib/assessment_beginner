<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "assessment_beginner";
$port = 3307;

try {
    $conn = mysqli_connect($host, $user, $pass, null, $port);
    if (!$conn) {
        throw new mysqli_sql_exception(mysqli_connect_error());
    }
    mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS `$dbname`");
    mysqli_select_db($conn, $dbname);
} catch (mysqli_sql_exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

if (!$conn) {
  die("Database connection failed: " . mysqli_connect_error());
}

$create_users = "CREATE TABLE IF NOT EXISTS users (
    user_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($conn, $create_users);

$check_admin = mysqli_query($conn, "SELECT * FROM users WHERE username = 'admin'");
if (mysqli_num_rows($check_admin) == 0) {
    $password = password_hash("admin123", PASSWORD_DEFAULT);
    $insert_admin = "INSERT INTO users (username, password) VALUES ('admin', '$password')";
    mysqli_query($conn, $insert_admin);
}

$create_clients = "CREATE TABLE IF NOT EXISTS clients (
    client_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    phone VARCHAR(50),
    address VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($conn, $create_clients);
mysqli_query($conn, "ALTER TABLE clients ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");

$create_services = "CREATE TABLE IF NOT EXISTS services (
    service_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) DEFAULT 0.00,
    description VARCHAR(255)
)";
mysqli_query($conn, $create_services);

$create_bookings = "CREATE TABLE IF NOT EXISTS bookings (
    booking_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    client_id INT(11) UNSIGNED NOT NULL,
    service_id INT(11) UNSIGNED NOT NULL,
    booking_date DATE NOT NULL,
    notes VARCHAR(255),
    total_amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(50) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($conn, $create_bookings);
mysqli_query($conn, "ALTER TABLE bookings ADD COLUMN IF NOT EXISTS total_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00");
mysqli_query($conn, "ALTER TABLE bookings ADD COLUMN IF NOT EXISTS status VARCHAR(50) DEFAULT 'Pending'");
mysqli_query($conn, "ALTER TABLE bookings ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");

$create_tools = "CREATE TABLE IF NOT EXISTS tools (
    tool_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tool_name VARCHAR(255) NOT NULL,
    quantity INT(11) UNSIGNED DEFAULT 0,
    location VARCHAR(255),
    status VARCHAR(50) DEFAULT 'Available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($conn, $create_tools);
mysqli_query($conn, "ALTER TABLE tools ADD COLUMN IF NOT EXISTS quantity INT(11) UNSIGNED DEFAULT 0");
mysqli_query($conn, "ALTER TABLE tools ADD COLUMN IF NOT EXISTS location VARCHAR(255)");
mysqli_query($conn, "ALTER TABLE tools ADD COLUMN IF NOT EXISTS status VARCHAR(50) DEFAULT 'Available'");
mysqli_query($conn, "ALTER TABLE tools ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");

$create_payments = "CREATE TABLE IF NOT EXISTS payments (
    payment_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_id INT(11) UNSIGNED,
    amount DECIMAL(10,2) NOT NULL,
    payment_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    method VARCHAR(50),
    status VARCHAR(50) DEFAULT 'Completed'
)";
mysqli_query($conn, $create_payments);
mysqli_query($conn, "ALTER TABLE payments ADD COLUMN IF NOT EXISTS method VARCHAR(50)");
mysqli_query($conn, "ALTER TABLE payments ADD COLUMN IF NOT EXISTS status VARCHAR(50) DEFAULT 'Completed'");
mysqli_query($conn, "ALTER TABLE payments ADD COLUMN IF NOT EXISTS payment_date DATETIME DEFAULT CURRENT_TIMESTAMP");
?>
