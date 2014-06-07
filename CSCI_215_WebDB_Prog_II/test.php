<?php

    include 'login_functions.php';
    
    registerUser('pabronson@students.highline.edu', 'pabronson', 'Paul', 'Bronson', '206-248-8894', 'admin');

    echo isUser('pabronson@students.highline.edu', 'pabronson');             
?>