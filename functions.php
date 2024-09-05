<?php

// Start Session - If not logged in, return to login screen

        function sessionstart(){session_start();

            if (!isset($_SESSION['loggedin'])) {
                header('Location: login.html');
                exit;
            }}

// Check page privileges against role assignment
    // adminonly privileges (e.g. AdminPanel)

            function adminonly(){
            if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
                header('Location: permission.php');
                exit();
            }}
    // customer privileges (all pages but prevents anonymous browsing by referring to the session role value assigned during login authentication. Hope to circumvent traversing anonymous URL traversal)
            function roleassigncustomer() {
                $allowedRoles = ['admin', 'super', 'customer'];
           
                if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowedRoles)) {
                    header('Location: permission.php');
                    exit();
                }
            }

// Database Connection

        function connectdb(){
           
            $DATABASE_HOST = 'localhost';
            $DATABASE_USER = 'root';
            $DATABASE_PASS = '';
            $DATABASE_NAME = 'fortnadocportal';
            $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
           
            if (mysqli_connect_errno()) {
                exit('Failed to connect to MySQL: ' . mysqli_connect_error());
            }

      return $con;
    }

// * Security * - Prepare SQL Statements

    function executePrepStmt($con, $query, $params = null) {
        $stmt = $con->prepare($query);
   
        if ($params) {
            $stmt->bind_param(...$params);
        }
   
        $stmt->execute();
   
        return $stmt;
    }

   
// Get User and/or Docs Info by ID  

    function getUser() {
        $con = connectdb();
        $query = 'SELECT company, sites, id, email, role, lastvisits FROM customers WHERE id = ?';
        $params = array("i", $_SESSION['id']);
        $stmt = executePrepStmt($con, $query, $params);
        $stmt->bind_result($company, $sites, $id, $email, $role, $lastvisits);
        $stmt->fetch();
        $stmt->close();
        $con->close();
        return array ($company, $sites, $id, $email, $role, $lastvisits);
            }

       
    function getDocuments(){

        $con = connectdb();
        $query = 'SELECT project, productguide, onlineloc, pdfloc, projectid, published FROM techdocs WHERE customerid = ? ORDER BY project';
        $params = array("i", $_SESSION['id']);
        $stmt = executePrepStmt($con, $query, $params);
        $stmt->bind_result($project, $productguide, $onlineloc, $pdfloc, $projectid, $published);
        $stmt->fetch();
        $stmt->close();
        $con->close();
        return array ($project, $productguide, $onlineloc, $pdfloc, $projectid, $published);}


    function getUserAndDocumentsByID() {
            $con = connectdb();
           
            // Query to join the two tables using INNER JOIN
            $query = 'SELECT c.company, c.sites, c.id, c.email, c.role, c.lastvisits, t.project, t.productguide, t.onlineloc, t.pdfloc, t.projectid, t.published, t.latest
                      FROM customers c
                      INNER JOIN techdocs t ON c.id = t.customerid
                      WHERE c.id = ?';
       
            $params = array("i", $_SESSION['id']);
            $stmt = executePrepStmt($con, $query, $params);
            $stmt->bind_result($company, $sites, $id, $email, $role, $lastvisits, $project, $productguide, $onlineloc, $pdfloc, $projectid, $published, $latest);
       
            $results = array();
       
            // Fetch results into an associative array
            while ($stmt->fetch()) {
                $results[] = array(
                    'company' => $company,
                    'sites' => $sites,
                    'id' => $id,
                    'email' => $email,
                    'role' => $role,
                    'lastvisits' => $lastvisits,
                    'project' => $project,
                    'productguide' => $productguide,
                    'onlineloc' => $onlineloc,
                    'pdfloc' => $pdfloc,
                    'projectid' => $projectid,
                    'published' => $published,
                    'latest' => $latest,
                );
            }
            $stmt->close();
            $con->close();
            return $results;
        }

        function getUserAndDocumentsByUser() {
            $con = connectdb();
           
            // Query to join the two tables using INNER JOIN
            $query = 'SELECT c.company, c.sites, c.id, c.email, c.role, c.lastvisits, c.adminlookup, t.project, t.productguide, t.onlineloc, t.pdfloc, t.projectid, t.published, t.latest
                      FROM customers c
                      INNER JOIN techdocs t ON c.id = t.customerid
                      WHERE c.adminlookup = ?
                      ORDER BY email';
       
            $params = array("s", $_SESSION['role']);
            $stmt = executePrepStmt($con, $query, $params);
            $stmt->bind_result($company, $sites, $id, $email, $role, $lastvisits, $project, $productguide, $onlineloc, $pdfloc, $projectid, $published, $latest, $adminlookup);
       
            $results = array();
       
            // Fetch results into an associative array
            while ($stmt->fetch()) {
                $results[] = array(
                    'company' => $company,
                    'sites' => $sites,
                    'id' => $id,
                    'email' => $email,
                    'role' => $role,
                    'lastvisits' => $lastvisits,
                    'project' => $project,
                    'productguide' => $productguide,
                    'onlineloc' => $onlineloc,
                    'pdfloc' => $pdfloc,
                    'projectid' => $projectid,
                    'published' => $published,
                    'latest' => $latest,
                   
                );
            }
       
            $stmt->close();
            $con->close();
       
            return $results;
        }
   
// User Session Analytics Functions

    function logUrl($pagevisited, $sessionId) {
        $con = connectdb();
        $timestamp = date("Y-m-d H:i:s");
        $sql = "INSERT INTO visit_logs (pagevisited, timestamp, logid) VALUES (?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssi", $pagevisited, $timestamp, $_SESSION["id"]);
        $stmt->execute();
        $stmt->close();
    }
?>