<?php

/** 
 * Logout for internship team project
 *
 * @author 		Josh Archer <jarcher@highline.edu>
 * @author 		Paul Bronson <pabronson@students.highline.edu>
 * @author 		Ryan Harmon <Ryan@Harmonized.net>
 * @author 		Devin McIlnay <devin_mcilnay@students.highline.edu>
 * @copyright 	2014 Team Agile, Highline Community College 
 * @version   	SVN: $Id$
 *
 */

    //have access to your session
    session_start();
    
    //remove all entries in your session array
    $_SESSION = array();
    
    //destroy the session cookie
    setcookie(session_name(), '', time() - 3600);
    
    //destroy the session itself
    session_destroy();
    
    header('location: ../index.php');
?>