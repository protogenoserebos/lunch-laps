<?php

// Includes

require 'functions.php';
require 'portalstatus.php';

// Functions

sessionstart();
adminonly();
$userInfo = getUser();
$documents = getDocuments();
$useranddocuments = getUserAndDocumentsByID();

require 'nav.php';

// Session Logging for Analytics

$currentUrl = $_SERVER['REQUEST_URI'];
$sessionId = $_SESSION['id'];
logUrl($currentUrl, $sessionId);

// Create variables from functions

list($company, $sites, $id, $email, $role, $lastvisits) = getUser();
$documents = getDocuments();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" admincontent="width=device-width, initial-scale=1.0">
    <link href="styles.css" rel="stylesheet" type="text/css">
    <link href="scripts/sidenavload.js" rel="script">
    <title>Admin Panel</title>

</head>
<body class="loggedin">

<div class="adminnav">
    <a href="#" onclick="loadPage('manageUsers.php')">Manage Users</a>
    <a href="#" onclick="loadPage('viewUserAnalytics.php')">View User Analytics</a>
    <a href="#" onclick="loadPage('siteAdministration.php')">Site Administration</a>
    <a href="#" onclick="loadPage('auditadminactions.php')">Audit Admin Actions</a>
</div>

<div id="adminbody"></div>

<script src="scripts/sidenavload.js"></script>

<div id="footer">
        <footer>
            <p>©2024 FORTNA Inc. All rights reserved.</p>
            <p><a href="copyright.php">Copyright Information</a>
            <a href="mailto:fortna.doclibrary@fortna.com">Contact FORTNA Technical Communications</a></p>
        </footer>
    </div>
</body>
</html>
