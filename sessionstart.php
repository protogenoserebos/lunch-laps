<?php

require "functions.php";

// Start session
session_start();

// Route to Login Screen if user isn't logged in

if (!isset($_SESSION['loggedin'])) {
    header('Location: login.html');
    exit;
}

$currentUrl = $_SERVER['REQUEST_URI'];
$sessionId = session_id();
?>