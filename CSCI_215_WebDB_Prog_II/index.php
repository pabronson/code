
<?php

/** 
 * login proces internship team project
 *
 * @author      Paul Bronson (pabronson@students.highline.edu)
 * @author 		Devin McIlnay <devin_mcilnay@students.highline.edu>
 * @author 		Ryan Harmon <Ryan@Harmonized.net>
 * @copyright 	2014 Team Agile, Highline Community College 
 * @version   	SVN: $Id$
 *
 */

    ini_set ('display_errors', 1);
    error_reporting (E_ALL);

// Include header on ALL VIEWS
    include 'includes/header.inc.htm';
 
    require 'login_functions.php';

    session_start();
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            //echo "email =".$email."<br>";
            //echo "password =".$password."<br>";
            
           
            //call our db function
            if (isUser($email, $password)) {
                //save a token to recognize that the user is logged in
                
                $a_contactData = getContactInfo($email, $password);
                if (isset($a_contactData['contactId'])) {             
                
                    $_SESSION['contactId']  = $a_contactData['contactId'];
                    $_SESSION['userId']     = $a_contactData['userId'];
                    $_SESSION['orgId']      = $a_contactData['orgId'];
                    $_SESSION['userType']   = $a_contactData['userType'];
                    $_SESSION['userEmail']  = $email;
            
                    if ($a_contactData['userType'] == 'organization') {
                        header("location: ../index.php");
                    } else {
                        //send the user to our content page
                        header("location: ../index.php");
                    }
                } elseif ($a_contactData['userType'] == 'organization') {
                    $_SESSION['userId']     = $a_contactData['userId'];
                    $_SESSION['orgId']      = $a_contactData['orgId'];
                    $_SESSION['userType']   = $a_contactData['userType'];
                    $_SESSION['userFirst']  = $a_contactData['userFirst'];
                    $_SESSION['userLast']   = $a_contactData['userLast'];
                    $_SESSION['userPhone']  = $a_contactData['userPhone'];
                    $_SESSION['userEmail']  = $a_contactData['userEmail'];
                    header("location: ../index.php?page=addContact");
                } elseif ($a_contactData['userType'] == 'student') {
                    $_SESSION['userId']     = $a_contactData['userId'];
                    $_SESSION['userType']   = $a_contactData['userType'];
                    $_SESSION['userFirst']  = $a_contactData['userFirst'];
                    $_SESSION['userLast']   = $a_contactData['userLast'];
                    $_SESSION['userPhone']  = $a_contactData['userPhone'];
                    $_SESSION['userEmail']  = $a_contactData['userEmail'];
                    header("location: ../index.php");
                } else {
                    //send the user to our content page
                    header("location: ../index.php");
                }
                
            } else {
                $error = 'Email/password not recognized.';
                
            }
        } else {
            echo 'Please enter an email and password!';
        }
    }
?>

<h1 class="sub">Login Form</h1>

<div id="login">
    <form action="index.php" class="login" method="post">
                 <?php if (isset($error)){ echo "<span class='warning'>$error</span>"; }; ?>
                 <fieldset>
                   <p>
                        <label for="email">Email:</label>
                        <input type="text" maxlength="50" id="email" name="email"><br>
        
                        <label for="password">Password:</label>
                        <input type="password" maxlength="50" id="password" name="password">
                   </p>
                   
                
                </fieldset>
           <input type="Submit" value="Log in!"> 
     
    </form>
</div>


<div id="login-instructions">
    <h2 id="atn-students">Attention Students</h2>
    <p>In order to apply for an internship opportunity students <u>must register</u> here:</p>
    <p><a href="register.php" class="button">Student Opportunity Registration</a></p>
    <br>

    <h2 id="atn-employers">Attention Employers</h2>
    <p>Register now and learn how our talented<br> students can help your business:</p>
    <p><a href="requestOrgLogin.php" class="button">Post Internship Opportunities</a></p>
    
</div>
 

<?php
    include 'includes/footer.inc.htm';
?>
