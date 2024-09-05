<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
</head>
<body>

<h2>User Registration</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    Company: <input type="text" name="company" required><br>
    <input type="submit" value="Register">
</form>

<?php
// Display registration status
if (isset($_SESSION['message'])) {
    echo "<p>{$_SESSION['message']}</p>";
    unset($_SESSION['message']);
}
?>

</body>
</html>
