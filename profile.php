<?php

    require 'sessionstart.php';
    require 'nav.html';
    require 'footer.html';

// Prepare SQL Statement
    $stmt = $con->prepare('SELECT email, company, sites, techdocs FROM customers WHERE id = ?');

// Retrieve customer info from db via session id
    $stmt->bind_param('i', $_SESSION['id']);
    $stmt->execute();
    $stmt->bind_result($email, $company, $sites, $techdocs);
    $stmt->fetch();
    $stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>My Profile</title>
    <link rel="stylesheet" href="styles.css">
    <script src="scripts/jquery.js"></script>
</head>    
<body class="loggedin">
    <div id="nav"></div>
        <div class="content">
            <h2>My Profile</h2>
            <div>
                <p>Your FORTNA Documentation Portal user details are below:</p>
                <table>
                    <tr>
                        <td>Email:</td>
                        <td><?=$email?></td>
                    </tr>
                    <tr>
                        <td>Company:</td>
                        <td><?=$company?></td>
                    </tr>
                    <tr>
                        <td>Site(s):</td>
                        <td><?=$sites?></td>
                    </tr>
                    <tr>
                        <td>Documentation:</td>
                        <td><?=$techdocs?></td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>
