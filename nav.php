
<!DOCTYPE html>
            <head>
            <script src="scripts/jquery.js"></script></head>
            <body>
                <nav class="navtop">
            <div>
                <a href="home.php"> <img class="nav-hero" style="width:140pt; height:25pt" src="../fortnadocportal/img/FORTNA-hero.png"></img>        
                <?php if ($_SESSION['role'] === 'admin'){echo "<a href='adminpanel.php'>Admin Panel</a>";}?>
                <a href="techdocs.php">Documentation</a>
                <a href="logout.php">Logout</a>

               
            </div>
        </nav>
    </body>
        </html>