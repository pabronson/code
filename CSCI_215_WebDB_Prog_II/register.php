
<?php

/** 
 * Student self-registration form for internship team project
 *
 * @author 		Paul Bronson <pabronson@students.highline.edu>
 * @author 		Ryan Harmon <Ryan@Harmonized.net>
 * @author 		Devin McIlnay <devin_mcilnay@students.highline.edu>
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
?>

<h1 class="sub">New Student Registration Form</h1>

<?php
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        //validate the data
        $valid = TRUE;
        
         //check student id   
        if (!empty($_POST['studentId'])){
            $sid = validateSID($_POST['studentId']); // to model/functions.php
        } else {
            echo "<p>Please enter a <strong>student ID number!</strong></p>";
            $valid = FALSE;
        }  

         //check email 
         if (!empty($_POST['studentEmail'])){
           $email = validateEmail($_POST['studentEmail']);  //to model/functions.php         
	    } else {
            echo "<p>Please enter an <strong>email address!</strong></p>";
            $valid = FALSE;
        }

        //check student last name     
        if (!empty($_POST['studentLast'])) {
			$studentLast = validateText($_POST['studentLast']); //model/functions.php 
        } else {
			echo '<p>Please enter your <strong>last name.</strong></p>';
			$valid = FALSE;
		}	
                
        
        //check student first name 
        if (!empty($_POST['studentFirst'])) {
			$studentFirst = validateText($_POST['studentFirst']); //model/functions.php 
        } else {
			echo '<p>Please enter <strong>first name.</strong></p>';
			$valid = FALSE;
		}
        
        //check student middle name 
        if (!empty($_POST['studentMiddle'])) {
			$studentMiddle = validateText($_POST['studentMiddle']); //model/functions.php 
        } elseif (empty($_POST['studentMiddle'])) {
			$studentMiddle = '';
	} else {
			echo '<p>Please enter <strong>middle name.</strong></p>';
			$valid = FALSE;
		}

        //check student phone  
        if (!empty($_POST['studentPhone'])) {
			$studentPhone = validatePhone($_POST['studentPhone']); //model/functions.php 
        } else {
			echo '<p>Enter the your <strong>phone number.</strong></p>';
			$valid = FALSE;
		}
 
        if (!empty($_POST['studentStreet'])) {
             $studentStreet = validateText($_POST['studentStreet']); //model/functions.php 
         } else {
             echo '<p>Please enter your <strong>street.</strong></p>';
             $valid = FALSE;
         }
         if (!empty($_POST['studentCity'])) {
             $studentCity = validateText($_POST['studentCity']); //model/functions.php 
         } else {
             echo '<p>Please enter your <strong>city.</strong></p>';
             $valid = FALSE;
         }
         if (!empty($_POST['studentState'])) {
             $studentState = validateState($_POST['studentState']); //model/functions.php 
         } else {
             echo '<p>Please enter your <strong>state.</strong></p>';
             $valid = FALSE;
         }
         if (!empty($_POST['studentZip'])) {
             $studentZip = validateZip($_POST['studentZip']); //model/functions.php 
         } else {
             echo '<p>Please enter your <strong>zipcode.</strong></p>';
             $valid = FALSE;
         }

        //validate password   
        if (!empty($_POST['studentPassword'])){
  
            if (!empty($_POST['passwordMatch'])){
                if ($_POST['passwordMatch'] == $_POST['studentPassword']){
                    $password = $_POST['studentPassword'];    
                    $match = $_POST['passwordMatch'];   
                } else {
                    echo "<p>Please retype password - <strong>passwords do not match!</strong></p>";
                    $valid = FALSE;
                } 

            } else {
            echo "<p>Please retype your <strong>password!</strong></p>";
            $valid = FALSE;
            }   

        } else {
            echo "<p>Please enter a <strong>password!</strong></p>";
            $valid = FALSE;
        }   

         //if data valid
        if ($valid) {
            
            //add student data to `user` table of database
            $register = registerUser($email, $password, $studentFirst, $studentMiddle, $studentLast, $studentPhone, 'student');
            echo isUser($email, $password);  //login_functions.php 
            
            //if successful user registration            
            if ($register) {
                
                //get user id created when student was added
                $a_user = getUserId($email, $password); //login_functions.php 
              
                //if successful userId selection 
                if ($a_user){
                    
                    //add student data to `student` table of database
                    $test = addStudent($sid, $studentLast, $studentFirst, $studentMiddle, $studentPhone, $email, 
                         $studentStreet, $studentCity, $studentState, $studentZip, $a_user['userId']); //login_functions.php 
                    
                    if ($test) {
                        //forward to login page
                        header("location: index.php");
                    } else {
                        echo "<p>There was problem entering your student data! Please try again or contact the administrator.</p>";
                    }
                } else {
                    echo "<p>There was problem registering your user data! Please try again or contact the administrator.</p>";
                    
                }
                
    		}
        } 
    }
?>

<form action="register.php" method="post">
     
	<fieldset id="sUser" class="name"><legend>Student Information</legend>

        <label for="studentId">Student ID:</label>&nbsp; <input type="text" name="studentId" id="studentId" size="9" maxlength="9"
			value="<?php if (isset($_POST['studentId'])) {echo $_POST['studentId'];
                    }elseif (isset($a_studentData['studentId'])){echo $a_studentData['studentId'];}?>">
	    <label for="studentEmail">Email:</label> <input type="email" id="studentEmail" name="studentEmail" placeholder="studentname@address.com" size="50"
			value="<?php if (isset($_SESSION['studentEmail'])){echo $_SESSION['studentEmail'];
				}elseif (isset($_POST['studentEmail'])){echo $_POST['studentEmail'];
                                }elseif (isset($a_studentData['student_Email'])){echo $a_studentData['student_Email'];}?>">                   
	   <label for="studentPhone">Phone:</label> <input type="tel" id="studentPhone" name="studentPhone" placeholder="2065551212" size="12"
			value="<?php if (isset($_SESSION['studentPhone'])){echo $_SESSION['studentPhone'];
				}elseif (isset($_POST['studentPhone'])){echo $_POST['studentPhone'];
                                }elseif (isset($a_studentData['student_Phone'])){echo $a_studentData['student_Phone'];}?>"><br>                    
	    <label for="studentFirst">First Name:</label>&nbsp; <input type="text" name="studentFirst" id="studentFirst" size="20"
			value="<?php if (isset($_POST['studentFirst'])) {echo $_POST['studentFirst'];
                    }elseif (isset($a_studentData['studentFirst'])){echo $a_studentData['studentFirst'];}?>">
	    <label for="studentMiddle">Middle Name:</label>&nbsp; <input type="text" name="studentMiddle" id="studentMiddle" size="20"
                        value="<?php if (isset($_POST['studentMiddle'])){echo $_POST['studentMiddle'];
                                }elseif (isset($a_studentData['student_Middle'])){echo $a_studentData['student_Middle'];}?>">
        <label for="studentLast">Last Name:</label>&nbsp; <input type="text" name="studentLast" id="studentLast" size="30"
			value="<?php if (isset($_POST['studentLast'])){echo $_POST['studentLast'];
                                }elseif (isset($a_studentData['student_Last'])){echo $a_studentData['student_Last'];}?>">   
	</fieldset>
	
    <fieldset id="studentAddress" class="name"><legend>Student Address:</legend>
	
	    <label for="studentStreet">Street Address:</label>&nbsp; <input type="text" name="studentStreet" id="studentStreet" size="60"
                        value="<?php if (isset($_POST['studentStreet'])){echo $_POST['studentStreet'];
                                }elseif (isset($a_studentData['student_Street'])){echo $a_studentData['student_Street'];}?>"><br>
	    <label for="studentCity">City:</label>&nbsp; <input type="text" name="studentCity" id="studentCity" size="30"
                        value="<?php if (isset($_POST['studentCity'])){echo $_POST['studentCity'];
                                }elseif (isset($a_studentData['student_City'])){echo $a_studentData['student_City'];}?>"> &nbsp; 
	    <label for="studentState">State:</label>&nbsp; <input type="text" name="studentState" id="studentState" size="2"
                        value="<?php if (isset($_POST['studentState'])){echo $_POST['studentState'];
                                }elseif (isset($a_studentData['student_State'])){echo $a_studentData['student_State'];}?>"> &nbsp; 
	    <label for="studentZip">5 Digit Zipcode:</label>&nbsp; <input type="text" name="studentZip" id="studentZip" size="5" maxlength = "5"
                        value="<?php if (isset($_POST['studentZip'])){echo $_POST['studentZip'];
                                }elseif (isset($a_studentData['student_Zip'])){echo $a_studentData['student_Zip'];}?>"><br>
	</fieldset>
    
    <fieldset id="studentPassword" class="name"><legend>Choose a Password</legend>
        <label for="studentPassword">Password:</label> <input type="password" id="studentPassword" name="studentPassword" size="58"><br>      
        <label for="passwordMatch">Retype Password:</label> <input type="password" id="passwordMatch" name="passwordMatch" size="58"><br>      
        </fieldset>
    
     <input type="Submit" value="Enter User Information"> 
   
</form>

<?php
    include 'includes/footer.inc.htm';
?>
