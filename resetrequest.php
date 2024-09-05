<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection (replace with your actual connection details)
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'fortnadocportal';
    $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
   
    if (mysqli_connect_errno()) {
        exit('Failed to connect to MySQL: ' . mysqli_connect_error());
    }}

// Initialize variables
$email = "";
$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Validate email existence in the "customers" table
    $query = "SELECT email FROM customers WHERE email = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Generate unique token
        $tokenQuery = "SELECT UUID() AS unique_token";
        $result = $con->query($tokenQuery);
        $row = $result->fetch_assoc();
        $uniqueToken = $row['unique_token'];

        // Update token in the "customers" table
        $updateQuery = "UPDATE customers SET token = ? WHERE email = ?";
        $updateStmt = $con->prepare($updateQuery);
        $updateStmt->bind_param("ss", $uniqueToken, $email);
        $updateStmt->execute();

        // Generate reset link
        $resetLink = "reset-password.php?token=" . urlencode($uniqueToken);
       

        echo "Password reset link generated: $resetLink.";
    } else {
        $error = "Email not found.";
    }

    // Close statements
    $stmt->close();
    $updateStmt->close();
   
// Close the database connection
$con->close();

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Request</title>
</head>
<body>

<h2>Password Reset Request</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Email: <input type="email" name="email" value="<?php echo $email; ?>" required>
    <input type="submit" value="Submit">
</form>

<?php
if ($error) {
    echo "<p>$error</p>";
}
?>

</body>
</html>