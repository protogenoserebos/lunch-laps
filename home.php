<?php

// Include Functions and Status

require 'functions.php';
require 'portalstatus.php';

// Functions

sessionstart();
roleassigncustomer();
$userInfo = getUser();
$documents = getDocuments();
$useranddocuments = getUserAndDocumentsByID();

// Include Navigation after sessionstart function is called

require 'nav.php';

// Create variables from functions

list($company, $sites, $id, $email, $role, $lastvisits) = getUser();
$documents = getDocuments();


// Session Logging for Analytics

$currentUrl = $_SERVER['REQUEST_URI'];
$sessionId = $_SESSION['id'];
logUrl($currentUrl, $sessionId);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>FORTNA Documentation Portal</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="loggedin">
    <div id="nav"></div>
    <div class="content">
        <h1 id="welcome">Welcome, <?php echo " " . $company . ".<br>";?></h1>
        <div class="body-subsect">
            <h2>My Sites and Related Documentation</h2>
            <?php
            foreach ($useranddocuments as $row) {
                echo "<h3>{$row['project']}</h3>";
                echo "<ul>";
                echo "<li>{$row['company']}, {$row['productguide']}, {$row['onlineloc']}, <b>PDF</b>: {$row['pdfloc']}, ProjectID: {$row['projectid']} Published: {$row['published']}</li>";
                echo "</ul>";
            }
            ?>
        </div>
    </div>

    <div id="footer">
        <footer>
            <p>©2024 FORTNA Inc. All rights reserved.</p>
            <p><a href="copyright.php">Copyright Information</a>
            <a href="mailto:fortna.doclibrary@fortna.com">Contact FORTNA Technical Communications</a></p>
        </footer>
    </div>
</body>
</html>
