<?php

/**
 * database connection include for Internship Website  
 * 
 * @author 		Paul Bronson <pabronson@students.highline.edu> 
 * @author 		Ryan Harmon <Ryan@Harmonized.net>
 * @author 		Devin McIlnay <devin_mcilnay@students.highline.edu>
 * @copyright 	2014 Team Agile, Highline Community College 
 * @version   	SVN: $Id$
*/

    // create a connection to mysql database
    define('DSN','mysql:host=localhost;dbname=agile'); //data store name
    define('USERNAME','pabronson');
    define('PASSWORD','pabronson');

    /* try -> catch block
     * used to respond to errors using catch if the try fails
     * PDOException is a variable type to declare $exception which is an object generated by the catch
     * $exception object is called and the error message is diplayed with getMessage
     */    
    try {
        $connection = new PDO(DSN,USERNAME,PASSWORD);
    } catch (PDOException $exception) {
        die('Error connection to MySQL database: '.$exception->getMessage());
    }
?>