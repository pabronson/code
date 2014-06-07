<?php

/** 
 * Admin user registration
 *
 * @author 		Ryan Harmon <Ryan@Harmonized.net>
 * @author 		Devin McIlnay <devin_mcilnay@students.highline.edu>
 * @author 		Paul Bronson <pabronson@students.highline.edu>
 * @copyright 	2014 Team Agile, Highline Community College 
 * @version   	SVN: $Id$
 *
 */

    ini_set ('display_errors', 1);
    error_reporting (E_ALL);

	// Include header on ALL VIEWS
    include '../includes/sub-header.inc.htm';
    include '../model/functions.php';
    require 'login_functions.php';

    session_start();
	if ($_SESSION['userType'] == 'admin') {    
?>

<h1 class="sub">New User Registration Form</h1>

<?php
    $a_userType = array(
	'organization' 	=> 'organization',
	'admin' 	=> 'admin',
	'student'	=> 'student');
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        //validate the data
        $valid = TRUE;
        if (in_array($_POST['userType'], $a_userType)) {
		$userType = $_POST['userType'];
        } else {
		$valid = FALSE;
		}
	
		//check email
		if (!empty($_POST['userEmail'])){
			$email = validateEmail($_POST['userEmail']); //model/functions.php           
		} else {
			echo "<p><strong>Please enter an email address!</strong></p>";
			$valid = FALSE;
			}
		if (!empty($_POST['userFirst'])) {
			$userFirst = validateText($_POST['userFirst']); //model/functions.php 
		} else {
			echo '<p><strong>Enter First Name.</strong></p>';
			$valid = FALSE;
		}
		if (!empty($_POST['userLast'])) {
			//call to validation in functions.php
			$userLast = validateText($_POST['userLast']); //model/functions.php 
		} else {
			echo '<p><strong>Enter Last Name.</strong></p>';
			$valid = FALSE;
		}	
		if (!empty($_POST['userPhone'])) {
			$userPhone = validatePhone($_POST['userPhone']); //model/functions.php 
		} else {
			echo '<p><strong>Enter the users Phone Number.</strong></p>';
			$valid = FALSE;
		}

            
    //if data valid
		if ($valid) {
			$existing = checkUserEmail($email);
			if ($existing == 'FALSE') {
				
				$password = $email;
				
				//administrator add of user
				//call to login_functions.php 
				$test = adminAddUser($email, $password, $userFirst, $userLast, $userPhone, $userType);
				  
				if ($test) {
					if (isUser($email, $password)) {
					//forward to login page
					echo '<hr><strong class="name">'.$userFirst.' has been added with email: '.$email.' !<strong><hr>';
							$to = $email;
							//subject
							$subject = 'Highline Cooperative: Your user login has been generated.';
							
							//email Body
							$body  = 'To: '.$userFirst.' '.$userLast.'<br><br>';
							$body .= 'Your login has been created using your email '.$email.' as both your username and password.<br>';
							$body .= 'Please log in at <a href="http://ned.highline.edu/~faustus101/215/internship/agile/index.php">Cooperative Website</a> to complete your registration.<br><br>';
							$body .= 'Once completed you will be able to submit cooperative opportunities.<br>';
							$body .= '<br><br>';
							$body .= 'Thank you for supporting the Highline Cooperative Education experience.<br><br>';
							$body .= 'Signed,<br>';
							$body .= 'Highline CIS /CSCI Department';
							//headers
							$headers =  "From: faustus101@students.highline.edu\r\n".
									"Reply-To: faustus101@students.highline.edu\r\n";
							
							//set MIME type for data transfer type
							$headers .= "MIME-Version: 1.0\r\n";
							
							//set content type to ISO standard using charset standard for most browsers
							$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
							//send the email, how easy!
							$success = mail($to,$subject,$body,$headers);
					}
				} else {
					echo 'There was a problem!';
				}
			} else {
				echo '<hr><strong class="name">That user has already been added!<br>Do <b>NOT</b> resubmit the request!<strong><hr>';
			}
		} 
    }
?>



<form action="adminAddUser.php" method="post">
        <fieldset id="userType" class="name"><legend>Set User Permissions:</legend>
	<label>Select User Type: </label>
		<select name="userType">
		    <?php foreach ($a_userType as $user=>$type) {
				echo'<option value="'.$user.'">'.$type.'</option>';
			  } ?>
		</select>
	</fieldset>
	<fieldset id="internshipUser" class="name"><legend>Enter User Information</legend>
	    <label for="userFirst">First Name:</label>&nbsp; <input type="text" name="userFirst" id="userFirst" size="20"
			value="<?php if (isset($_POST['userFirst'])) {echo $_POST['userFirst'];
                    }elseif (isset($a_userData['userFirst']))    {echo $a_userData['userFirst'];}?>">
	    <label for="userLast">Last Name:</label>&nbsp; <input type="text" name="userLast" id="userLast" size="30"
			value="<?php if (isset($_POST['userLast']))              {echo $_POST['userLast'];
                                }elseif (isset($a_userData['user_Last']))     {echo $a_userData['user_Last'];}?>"><br>
	    <label for="userPhone">Phone:</label> <input type="tel" id="userPhone" name="userPhone" placeholder="2065551212" size="12"
			value="<?php if (isset($_POST['userPhone']))             {echo $_POST['userPhone'];
                                }elseif (isset($a_userData['user_Phone']))    {echo $a_userData['user_Phone'];}?>">
	    <label for="userEmail">Email:</label> <input type="email" id="userEmail" name="userEmail" placeholder="username@address.com" size="58"
			value="<?php if (isset($_POST['userEmail']))             {echo $_POST['userEmail'];
                                }elseif (isset($a_userData['user_Email']))    {echo $a_userData['user_Email'];}?>"><br>
 
    </fieldset>

    
     <input type="Submit" value="Enter User Information"> 
                                

   
</form>

<?php
    include '../includes/sub-footer.inc.htm';
} else {
    header("location: ../index.php");
}
?>
