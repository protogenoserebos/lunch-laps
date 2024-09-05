<?php
 
 // Includes

 require 'functions.php';


// Functions

sessionstart();
adminonly();

// Session Logging for Analytics

$currentUrl = $_SERVER['REQUEST_URI'];
$sessionId = $_SESSION['id'];
logUrl($currentUrl, $sessionId);


// Check if the form is submitted

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection (replace with your actual connection details)
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'fortnadocportal';
    $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    if (mysqli_connect_errno()) {
        exit('Failed to connect to MySQL: ' . mysqli_connect_error());
    }

    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $company = $_POST['company'];
    $sites = $_POST['sites'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into the database
    $sql = "INSERT INTO customers (email, password, role, company, sites) VALUES (?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssss", $email, $hashedPassword, $role, $company, $sites);

    if ($stmt->execute()) {
        // Registration successful
        $_SESSION['message'] = "Customer successfully added. Please upload relevant project information and associated documentation.";
        header("Location: addnewdocs.php");
        exit();
    } else {
        // Registration failed
        $_SESSION['message'] = "Registration failed. Please try again.";
    }

    // Close the statement and connection
    $stmt->close();
    $mysqli->close();
}
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
    <h2>Add New Customers</h2>  
    <button href="#" onclick="loadPage('manageUsers.php')">Back</button>

<h3>Customer Details</h3>
<div class="admin-forms">
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Email: <input type="email" name="Email" required><br>
    Password: <input type="password" name="password" required><br>
    Role: <input type="text" name="role" required><br>
    Company: <input type="text" name="company" required><br>
    Site: <input type="text" name="sites" required><br>
    <input type="submit" value="Add Customer">
</div>
</form>


<?php
// Display registration status
if (isset($_SESSION['message'])) {
    echo "<p>{$_SESSION['message']}</p>";
    unset($_SESSION['message']);
}
?>
</div>
</body>
</html>
