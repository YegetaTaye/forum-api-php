<?php

$host = 'localhost';
$db = 'forum';
$user = 'root';
$pass = "asdf1234";

// Create a MySQLi connection
$mysqli = new mysqli($host, $user, $pass, $db);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
