<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "127.0.0.1";
$dbname = "shopping_hurb";
$username = "shopping_hurb";
$password = "zYLtr#DSk+3*3+AK";

echo "<pre>";

try {

    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection Failed: " . $conn->connect_error);
    }

    echo "Database Connected Successfully ✅";

} catch (Exception $e) {

    echo "ERROR: " . $e->getMessage();

}