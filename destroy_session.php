<?php


// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Respond with a success message
echo "Session destroyed successfully";
?>
