echo "Here is the login page";
<?php


    require 'login_functions.php';
    
    session_start();
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            if (isUser($email, $password)) {
                
                //have a token to recognize the user is logged in
                $_SESSION['user_id'] = $email;
                
                //send the user to our content page
                header('Location: content.php');
                
            } else {
                echo 'Email/password not recognized.';
                
            }
            
            
        } else {
            echo 'Please enter a password!';
        }
        
    }

?>
<form action="" method="post">
    <h2></h2
             
             <p>
                <label for="email">Email</label>
                <input type="text" maxlength="50" name="email">
            </p>

             <p>            
                <label for="password">Password</label>
                <input type="password" maxlength="50" name="password">
            </p>
             
            <input type="Submit" value="Log in!"> 
   

</form>