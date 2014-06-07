<?php
    session_start();
    
    //check whether the user is logged in
    if (!isset($_SESSION['user_id'])) {
        //display a login link
        echo '<h3><a href="login.php">login</a></h3>';
        exit();
        
    } else {
        //display a logout link
        echo '<h3><a href="logout.php">logout</a></h3>';
        
    }
    
    //show the content of the page
    echo 'View all movies here!';
?>