<?php

// *Security* - Starting/restarting sessions that are handled via server instead of browser cookies. Standard throughout site.

    session_start();

// Connect to DB instance.

    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'bikebuilds';

// Try and connect using the info above.

    $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    if ( mysqli_connect_errno() ) {
   
// If there is an error with the connection, stop the script and display the error.

    exit('A connection error has occurred: ' . mysqli_connect_error());
}

// Check headers to see if email/pw POST info exists.

if ( !isset($_POST['email'], $_POST['password']) ) {
   
// If not, error message.

    exit('Please complete the required fields.');
}

// *Security* - Prepared SQL statements as standard.

if ($stmt = $con->prepare('SELECT id, password FROM loggedinusers WHERE email = ?')) {
    $stmt->bind_param('s', $_POST['email']);
    $stmt->execute();
    $stmt->store_result();

// Query DB for account

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();
       
// Account exists; Password verification  
     
        if (password_verify($_POST['password'], $password)) {

// Login Success - *Security* - Regenerate Session ID post-login.
           
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['id'] = $id;
            header('Location: home.php');
        } else {
           
            // Incorrect password
            echo 'Incorrect email and/or password!';
        }
    } else {
       
        // Incorrect email
        echo 'Incorrect email and/or password!';
    }

    $stmt->close();
}
?>
