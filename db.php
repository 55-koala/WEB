<?php
$servername = "shinkansen.proxy.rlwy.net";
$username = "root";
$password = "QtoMRLjUvunJXkRPjBVjtHxFnNynVCcl";
$dbname = "railway";
$port = 19411;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// echo "Connected successfully";  // 可開啟來測試
echo "Connected! 😎";
?>
