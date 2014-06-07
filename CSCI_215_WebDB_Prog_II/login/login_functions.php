<?php
/** 
 * Login functions for internship team project
 *
 * @author 		Paul Bronson <pabronson@students.highline.edu>
 * @author 		Ryan Harmon <Ryan@Harmonized.net>
 * @author 		Devin McIlnay <devin_mcilnay@students.highline.edu>
 * @copyright 	2014 Team Agile, Highline Community College 
 * @version   	SVN: $Id$
 *
 */

    //require database connections
    require '../includes/mysql_connect.php';
   
    //constants
    define('SALT', 'Thi$ i$ my $up3r $af3 $@lt v@lu3!?');

    /** 
     * Registers a user into database 
     *
     * @author Paul Bronson <faustus101@students.highline.edu>
     * @param string $sid student id number
     * @param string $studentLast student last name
     * @param string $studentFirst student first name
     * @param string $studentMiddle student middle name
     * @param string $studentPhone student phone number
     * @param string $studentEmail student email address
     * @param string $studentStreet student street address
     * @param string $studentCity student city
     * @param string $studentState student state
     * @param string $studentZip student zipcode
     * @param string $userId student `user_id` from `user` table
     * @return bool test for success
     */       
    function registerUser($email, $password, $userFirst, $userMiddle, $userLast, $userPhone, $userType)
    {
        global $connection;
          
        //generate a hash of my password
        $hash = sha1($password.SALT);
          
        //build my query
        $query = 'INSERT INTO users
                 (user_email, user_pass, user_first, user_middle, user_last, user_phone, user_type, registration_date) VALUES
                  (:email, :password, :user_first, :user_middle, :user_last, :user_phone, :user_type, NOW())';
                     
        //prepare it
        $statement = $connection->prepare($query);
          
        //bind our parameters
        $statement->bindParam(':email', $email);
        $statement->bindParam(':password', $hash); //use $hash here, not $password
        $statement->bindParam(':user_first', $userFirst);
        $statement->bindParam(':user_middle', $userMiddle);        
        $statement->bindParam(':user_last', $userLast);
        $statement->bindParam(':user_phone', $userPhone);
        $statement->bindParam(':user_type', $userType);
          
        //execute and return whether this was successful
        $statement->execute();
          
        return $statement->rowCount() == 1;
    }

    /**
    * Check if user has record in database
    *
    * @author Paul Bronson <pbrosnon@students.highline.edu>
    * @param string $email an email address
    * @param string $password a password
    * @return bool test for success
    */         
    function isUser($email, $password)
    {
        global $connection;
          
        //we have to generate a hash to compare against
        //our database values
        $hash = sha1($password.SALT);
          
        $query = "SELECT user_email
                  FROM users
                  WHERE user_email = :email
                  AND user_pass = :password";
                    
        //prepare our query
        $statement = $connection->prepare($query);
          
        //bind parameters
        $statement->bindParam(':email', $email);
        $statement->bindParam(':password', $hash);
          
        //determine whether there was a match
        $statement->execute();
        return sizeof($statement->fetchAll()) > 0;
    }

    /**
    * Function to get user contact information from database
    *
    * @author Paul Bronson <pbrosnon@students.highline.edu>
    * @param string $email an email address
    * @param string $password a password
    * @return string array $a_contactData
    */      
    function getContactInfo($email, $password)
    {
          global $connection;
 
        //we have to generate a hash to compare against
        //our database values
        $hash = sha1($password.SALT);
          
        $query = "SELECT c.org_ID AS orgId, c.user_id AS userId, c.contact_ID AS contactId, u.user_type AS userType
                  FROM users u JOIN contacts c
                        ON u.user_id = c.user_id 
                  WHERE u.user_email = :email
                  AND u.user_pass = :password";
                    
        //prepare our query
        $statement = $connection->prepare($query);
          
        //bind parameters
        $statement->bindParam(':email', $email);
        $statement->bindParam(':password', $hash);
          
        //determine whether there was a match
        $statement->execute();
        
        $a_contactData = $statement->fetch(PDO::FETCH_ASSOC);
        
        if ($a_contactData) {
            return $a_contactData;
        } else {
            $query = "SELECT    user_id AS userId,
                                user_email AS userEmail,
                                user_first AS userFirst,
                                user_last AS userLast,
                                user_phone AS userPhone,
                                user_type AS userType
                      FROM users
                      WHERE user_email = :email
                      AND user_pass = :password";
                        
            //prepare our query
            $statement = $connection->prepare($query);
              
            //bind parameters
            $statement->bindParam(':email', $email);
            $statement->bindParam(':password', $hash);
              
            //determine whether there was a match
            $statement->execute();
            $a_contactData = $statement->fetch(PDO::FETCH_ASSOC);
            return $a_contactData;
        }
    }

    /** 
     * function to check for existing user email
     * 
     * Checks if a given email is in the user table with the user_type 'organization'
     * Used to prevent an admin from creating multiple user IDs for the same org.
     *
     * @author Ryan Harmon <faustus101@students.highline.edu>
     * @param string $email
     * @return string $userId if successful SELECT
     * @return bool FALSE if unsuccessful SELECT
     */    
    function checkUserEmail($email)
    {
        global $connection;
 
        $query = "SELECT user_id AS userId
                      FROM users
                      WHERE user_email = :email
                      AND user_type = 'organization'";
                    
        //prepare our query
        $statement = $connection->prepare($query);
          
        //bind parameters
        $statement->bindParam(':email', $email);
          
        //determine whether there was a match
        $statement->execute();
        
        $success = $statement->fetch(PDO::FETCH_ASSOC);
        
        
        if (!empty($success)) {
            return 'TRUE';
        } else {
            return 'FALSE';
        }
    }

    /** 
     * function for admin users to add users 
     * 
     * Checks if a given email is in the user table with the user_type 'organization'
     * Used to prevent an admin from creating multiple user IDs for the same org.
     *
     * @author Ryan Harmon <faustus101@students.highline.edu>
     * @param string $email user email address
     * @param string $password user password
     * @param string $userFirst user first name 
     * @param string $userLast user last name
     * @param string $userPhone user phone number
     * @param string $userType user type
     * @return bool test for success
     */     
    function adminAddUser($email, $password, $userFirst, $userLast, $userPhone, $userType)
    {
        global $connection;
          
        //generate a hash of my password
        $hash = sha1($password.SALT);
        echo $hash;
          
        //build my query
        $query = 'INSERT INTO users
                 (user_email, user_pass, user_first, user_last, user_phone, user_type, registration_date) VALUES
                  (:email, :password, :user_first, :user_last, :user_phone, :user_type, NOW())';
                     
        //prepare it
        $statement = $connection->prepare($query);
          
        //bind our parameters
        $statement->bindParam(':email', $email);
        $statement->bindParam(':password', $hash); //use $hash here, not $password
        $statement->bindParam(':user_first', $userFirst);       
        $statement->bindParam(':user_last', $userLast);
        $statement->bindParam(':user_phone', $userPhone);       
        $statement->bindParam(':user_type', $userType);
          
        //execute and return whether this was successful
        $statement->execute();
          
        return $statement->rowCount() == 1;
    }
    
    /** 
     * Add student contact info to the database
     *
     * @author Paul Bronson <faustus101@students.highline.edu>
     * @param string $sid student id number
     * @param string $studentLast student last name
     * @param string $studentFirst student first name
     * @param string $studentMiddle student middle name
     * @param string $studentPhone student phone number
     * @param string $studentEmail student email address
     * @param string $studentStreet student street address
     * @param string $studentCity student city
     * @param string $studentState student state
     * @param string $studentZip student zipcode
     * @param string $userId student user id
     * @return bool test for success
     */ 
    function addStudent($sid, $studentLast, $studentFirst, $studentMiddle, $studentPhone, $studentEmail, 
                         $studentStreet, $studentCity, $studentState, $studentZip, $userId)
    {
        global $connection;
       
        //build my query
        $query = 'INSERT INTO students 
                (sid, student_last, student_first, student_middle, student_phone, student_email,
                    student_street, student_city, student_state, student_zip, user_id) VALUES
                (:sid, :student_last, :student_first, :student_middle, :student_phone, :student_email,
                    :student_street, :student_city, :student_state, :student_zip, :user_id)';
                     
        //prepare it
        $statement = $connection->prepare($query);
          
        //bind our parameters
        $statement->bindParam(':sid', $sid);        
        $statement->bindParam(':student_last', $studentLast);
        $statement->bindParam(':student_first', $studentFirst);       
        $statement->bindParam(':student_middle', $studentMiddle);
        $statement->bindParam(':student_phone', $studentPhone);
        $statement->bindParam(':student_email', $studentEmail);
        $statement->bindParam(':student_street', $studentStreet);
        $statement->bindParam(':student_city', $studentCity);
        $statement->bindParam(':student_state', $studentState);
        $statement->bindParam(':student_zip', $studentZip);
        $statement->bindParam(':user_id', $userId);        

        //execute and return whether this was successful
        $statement->execute();
          
        return $statement->rowCount() == 1;
    }
    
    /**
     * Function to get student info from the database
     *
     * @author Ryan Harmon <faustus101@students.highline.edu>
     * @param string $userId student `user_id` from `user` table
     * @return string array $a_contactData
     */
    function getStudentInfo($userId)
    {
        global $connection;
       
        //build my query
        $query = 'SELECT    student_last,
                            student_first,
                            student_middle,
                            student_phone,
                            student_email,
                            student_street,
                            student_city,
                            student_state,
                            student_zip
                FROM students
                WHERE user_id = :userId';
                     
        //prepare it
        $statement = $connection->prepare($query);
          
        //bind our parameters
        $statement->bindParam(':user_id', $userId);        

        //determine whether there was a match
        $statement->execute();
        $a_contactData = $statement->fetch(PDO::FETCH_ASSOC);
        return $a_contactData;
    }

    /**
     * Function to get user id number from users table in database
     *
     * @author Paul Bronson <pbrosnon@students.highline.edu>
     * @param string $email an email address
     * @param string $password a password
     * @return string array $a_user array containing user id
     */   
    function getUserId($email, $password)
    {
        global $connection;
 
        //we have to generate a hash to compare against
        //our database values
        $hash = sha1($password.SALT);
          
        $query = "SELECT user_id AS userId
                  FROM users
                  WHERE user_email = :email
                  AND user_pass = :password";
                    
        //prepare our query
        $statement = $connection->prepare($query);
          
        //bind parameters
        $statement->bindParam(':email', $email);
        $statement->bindParam(':password', $hash);
          
        //determine whether there was a match
        $statement->execute();
        
        $a_user = $statement->fetch(PDO::FETCH_ASSOC);

        return $a_user;
    }    
    
    
    
    
?>