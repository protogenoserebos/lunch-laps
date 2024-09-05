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
    } }


   // Initialize variables
$token = $_GET['token'];
$email = "";
$password = "";
$reenteredPassword = "";
$error = "";
$retryCount = 0;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $reenteredPassword = $_POST['reenter_password'];

    // Validate that new password and re-entered password match
    if ($password !== $reenteredPassword) {
        $error = "Passwords do not match.";
        $retryCount++;
    } else {
        // Validate email and token
        $validateQuery = "SELECT email FROM customers WHERE email = ? AND token = ?";
        $validateStmt = $con->prepare($validateQuery);
        $validateStmt->bind_param("ss", $email, $token);
        $validateStmt->execute();
        $validateStmt->store_result();

        if ($validateStmt->num_rows > 0) {
            // Email and token are valid, update the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $updateQuery = "UPDATE customers SET password = ? WHERE email = ?";
            $updateStmt = $con->prepare($updateQuery);
            $updateStmt->bind_param("ss", $hashedPassword, $email);
            $updateStmt->execute();

            echo "Password reset successfully.";
        } else {
            $error = "Invalid email.";
            $retryCount++;
        }

        // Close statements
        $validateStmt->close();
 
    }

    // Disable submit button after 3 retries
    if ($retryCount >= 3) {
        $disableSubmit = true;
        $error = "You have reached your maximum number of attempts. Please contact FORTNA Technical Communications at: fortna.doclibrary@fortna.com.";
    } else {
        $disableSubmit = false;
    }
    $con->close();
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <script src="scripts/jquery.js"></script>
</head>
<body>
<script>
    // Use jQuery to send an AJAX request when the page is unloaded (beforeunload event)
    $(window).on('beforeunload', function(){
        $.ajax({
            url: 'destroy_session.php',
            type: 'POST',
            async: false, // Set to false to wait for the request to complete before unloading the page
            success: function(data) {
            }
        });
    });
</script>
<h2>Password Reset</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?token=' . urlencode($token); ?>">
    Email: <input type="email" name="email" value="<?php echo $email; ?>" required><br>
    New Password: <input type="password" name="password" required><br>
    Re-enter Password: <input type="password" name="reenter_password" required><br>
    <input type="submit" value="Reset Password">
</form>

<?php
if ($error) {
    echo "<p>$error</p>";
}
?>

</body>
</html>
