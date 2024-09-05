
<?php
 // Includes

 require 'functions.php';


// Functions

sessionstart();
adminonly();
$useranddocuments = getUserAndDocumentsByUser();

// Create variables from functions

list($company, $sites, $id, $email, $role, $lastvisits) = getUser();
$documents = getDocuments();

// Create unique page variables

$uniqueemails = array(); // To store unique emails

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
    <div id="adminbuttons">
   
    <button href="#" onclick="loadPage('addnewcustomer.php')"class="admin-button">Add New Customer</button>
    <button href="#" onclick="loadPage('uploaddocumentation.php')"class="admin-button">Upload Documentation</button>
    <button class="admin-button" input type='New'>Reset Customer Password</button>  
    <button class="admin-button" input type='Remove'>Remove Customer</button>   </div>
            <?php
           
$uniqueEmails = array(); // To store unique emails

foreach ($useranddocuments as $row) {
    $email = $row['email'];
    $company = $row['company'];

    // Check if the email and company combination is already processed
    if (!in_array("$email-$company", $uniqueEmails)) {
        $uniqueEmails[] = "$email-$company"; // Add the email and company combination to the list of processed entries
        $matchingRows = array_filter($useranddocuments, function ($r) use ($email, $company) {
            return $r['email'] == $email && $r['company'] == $company;
        });

        // Output the merged cell for email and company as h3
        echo "<div class='admin-table-peruser'>";
        echo "<table class='admintable'>";
        echo "<tr><th>Email/User</th><th>Company</th></tr>";
        echo "<tr><td><b>$email</b></td><td><b>$company</b></td></tr>";
        echo "</table>";

        // Output the associated values in an HTML table
        echo "<table class='admintable'>";
        echo "<tr><th>Product Guide</th><th>Online Version</th><th>PDF Version</th><th>Project</th><th>Published Date</th><th>Latest?</th></tr>";

        foreach ($matchingRows as $matchingRow) {
            echo "<tr>";
            echo "<td>{$matchingRow['productguide']}</td>";
            echo "<td>{$matchingRow['onlineloc']}</td>";
            echo "<td>{$matchingRow['pdfloc']}</td>";
            echo "<td>{$matchingRow['projectid']}</td>";
            echo "<td>{$matchingRow['published']}</td>";
            echo "<td>{$matchingRow['latest']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    }
}
?>

</div>
</div>    
</body>
</html>
