
<?php

/** 
 * Request new organizational user login for internship team project
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
    include '../model/adminFunctions.php';
    
    require 'login_functions.php';

    session_start();
    
?>

<h1 class="sub">Request New Registration for Posting Internship Opportunitites</h1>

<?php
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        //validate the data
        $valid = TRUE;
        

         //check email 
        if (!empty($_POST['orgEmail'])){
           $orgEmail = validateEmail($_POST['orgEmail']); //model/functions.php           
	} else {
            echo "<p>Please enter an <strong>email address!</strong></p>";
            $valid = FALSE;
        }

        //check org last name     
        if (!empty($_POST['orgLast'])) {
			$orgLast = validateText($_POST['orgLast']); //model/functions.php 
        } else {
			echo '<p>Please enter your <strong>last name.</strong></p>';
			$valid = FALSE;
	}	
                
        
        //check org first name 
        if (!empty($_POST['orgFirst'])) {
			$orgFirst = validateText($_POST['orgFirst']); //model/functions.php 
        } else {
			echo '<p>Please enter <strong>first name.</strong></p>';
			$valid = FALSE;
	}

        //check org phone  
        if (!empty($_POST['orgPhone'])) {
			$orgPhone = validatePhone($_POST['orgPhone']); //model/functions.php 
        } else {
			echo '<p>Enter the your <strong>phone number.</strong></p>';
			$valid = FALSE;
	}
            
         //if data valid
        if ($valid) {
	    $a_coordinators = getAllCoordinators(); //model/adminFunctions.php
	    foreach ($a_coordinators as $coordinator) {
		$a_coordinatorData = getAdminbyEmail($coordinator['coord_Email']); //model/adminFunctions.php
		//email generation
		// recipient
		$to = $a_coordinatorData['user_email'];
		
		//subject
		$subject = 'Highline Cooperative: Please add '.$orgFirst.' '.$orgLast.' as a user.';
		
		//email Body
		$body  = 'To: '.$a_coordinatorData['user_first'].' '.$a_coordinatorData['user_last'].'<br><br>';
		$body .= $orgFirst. ' '.$orgLast.' has requested a <b>NEW</b> login to begin subitting Cooperative Opportunities.<br>';
		$body .= 'First Name: '.$orgFirst.'<br>';
		$body .= 'Last Name: '.$orgLast.'<br>';
		$body .= 'Submitted Email: '.$orgEmail.'<br>';
		$body .= 'Contact Phone Number: '.$orgPhone.'<br><br>';
		$body .= 'If you are logged in to the <a href="http://ned.highline.edu/~faustus101/215/internship/agile/index.php">Cooperative Website</a> as an admin you can click below to create a user ID. <br><br>';
		$body .= '<form action="http://ned.highline.edu/~faustus101/215/internship/agile/login/adminAddUser.php" method="POST">';
		$body .= '<input type="hidden" name="userFirst" value="'.$orgFirst.'">';
		$body .= '<input type="hidden" name="userLast" value="'.$orgLast.'">';
		$body .= '<input type="hidden" name="userEmail" value="'.$orgEmail.'">';
		$body .= '<input type="hidden" name="userPhone" value="'.$orgPhone.'">';
		$body .= '<input type="hidden" name="userType" value="organization">';
		$body .= '<input type="Submit" value="Create login"><br>';
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
	    if ($success) {
		echo "<hr><strong>Your login has been requested. You will recieve an e-mail once activated.</strong><hr>";
	    }
	}
    }
?>
        <!-- Instructions -->
        <fieldset class="intro" id="register-instructions"><legend>Instructions</legend>
			<h3>Highline Cooperative Education</h3>
			<p>Thank you for your interest in supporting the Highline Community College Cooperative Education Program!<br> 
			Submitting this form requests a login for you to post Cooperative Educational Opportunities to the CIS / CSCI department.<br> 
			When your login is approved you will receive an email with login instructions. <u>Please complete all fields.</u></p>
        </fieldset>
	
	<fieldset id="orgUser" class="name"><legend>User Information</legend>
	    <form action="requestOrgLogin.php" method="post">
	    <label for="orgEmail">Email:</label> <input type="email" id="orgEmail" name="orgEmail" placeholder="orgname@address.com" size="50"
			value="<?php if (isset($_POST['orgEmail']))             {echo $_POST['orgEmail'];}?>">                   
	    <label for="orgPhone">Phone:</label> <input type="tel" id="orgPhone" name="orgPhone" placeholder="2065551212" size="12"
			value="<?php if (isset($_POST['orgPhone']))             {echo $_POST['orgPhone'];}?>"><br>                    
	    <label for="orgFirst">First Name:</label>&nbsp; <input type="text" name="orgFirst" id="orgFirst" size="20"
			value="<?php if (isset($_POST['orgFirst'])) {echo $_POST['orgFirst'];}?>">
	    <label for="orgLast">Last Name:</label>&nbsp; <input type="text" name="orgLast" id="orgLast" size="30"
			value="<?php if (isset($_POST['orgLast']))              {echo $_POST['orgLast'];}?>">   
	    <br><br>
	    <input type="Submit" value="Request Employer Login">
	</fieldset>

   
</form>

<?php
    include '../includes/sub-footer.inc.htm';
?>
