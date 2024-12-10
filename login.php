<?php
    // Comment for assignment -Madi
    // Template for new VMS pages. Base your new page on this one

    // Make session information accessible, allowing us to associate
    // data with the logged-in user.
    session_cache_expire(30);
    session_start();
    
    ini_set("display_errors",1);
    error_reporting(E_ALL);

    // redirect to index if already logged in
    if (isset($_SESSION['_id'])) {
        header('Location: index.php');
        die();
    }
    $badLogin = false;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require_once('include/input-validation.php');
        $ignoreList = array('password');
        $args = sanitize($_POST, $ignoreList);
        $required = array('username', 'password');
        if (wereRequiredFieldsSubmitted($args, $required)) {
            require_once('domain/Person.php');
            require_once('database/dbPersons.php');
            /*@require_once('database/dbMessages.php');*/
            /*@dateChecker();*/
            $username = strtolower($args['username']);
            $password = $args['password'];
            $user = retrieve_person($username);
            if (!$user) {
                $badLogin = true;
            } else if (password_verify($password, $user->get_password())) {
                $_SESSION['logged_in'] = true;

                $_SESSION['access_level'] = $user->get_access_level();
                $_SESSION['f_name'] = $user->get_first_name();
                $_SESSION['l_name'] = $user->get_last_name();

                
                $_SESSION['type'] = 'admin';
                $_SESSION['_id'] = $user->get_id();
                
                // hard code root privileges
                if ($user->get_id() == 'vmsroot') {
                    $_SESSION['access_level'] = 3;
                    header('Location: index.php');
                }
                //if ($changePassword) {
                //    $_SESSION['access_level'] = 0;
                //    $_SESSION['change-password'] = true;
                //    header('Location: changePassword.php');
                //    die();
                //} 
                else {
                    header('Location: index.php');
                    die();
                }
                die();
            } else {
                $badLogin = true;
            }
        }
    }
    //<p>Or <a href="register.php">register as a new volunteer</a>!</p>
    //Had this line under login button, took user to register page
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>Step VA Volunteer System | Log In</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <main class="login">
            <h1>Step VA Volunteer System Login</h1>
            <?php if (isset($_GET['registerSuccess'])): ?>
                <div class="happy-toast">
                    Your registration was successful! Please log in below.
                </div>
            <?php else: ?>
            <p>Welcome! Please log in below.</p>
            <?php endif ?>
            <form method="post">
                <?php
                    if ($badLogin) {
                        echo '<span class="error">No login with that e-mail and password combination currently exists.</span>';
                    }
                ?>
                <label for="username">Username</label>
        		<input type="text" name="username" placeholder="Enter your username" required>
        		<label for="password">Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>
                <input type="submit" name="login" value="Log in">

            </form>
            <p></p>
            <p>Don't have an account? <a href = "/stepvarepo/register.php">Sign Up</a>!</p>
            <p>Looking for <a href="https://www.stepva.org/">Step VA</a>?</p>
        </main>
    </body>
</html>
