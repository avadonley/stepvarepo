<?php
session_start();

ini_set("display_errors", 1);
error_reporting(E_ALL);

$loggedIn = false;
$accessLevel = 0;
$userID = null;
if (isset($_SESSION['_id'])) {
    $loggedIn = true;
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
}

// Require admin privileges
if ($accessLevel < 1) {
    header('Location: login.php');
    die();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once('include/input-validation.php');
    
    $args = sanitize($_POST, null);
    $required = array("username");

    if (!wereRequiredFieldsSubmitted($args, $required)) {
        echo '<p class="error-message">Bad form data.</p>';
        die();
    } else {
        $username = $args['username'];
        
        if (!$username) {
            echo '<p class="error-message">Bad username.</p>';
            die();
        } else {
            // Redirect to the event list page
            header("Location: eventList.php?username=" . urlencode($username));
            die();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once('universal.inc') ?>
    <link rel="stylesheet" href="css/editprofile.css" type="text/css" />
    <title>Step VA | Edit Volunteer Hours</title>
</head>
<body>
    <?php require_once('header.php') ?>
    <div class="container">
        <h1>Change Hours Within an Event</h1>
        <main class="general">
            <h2>Change Hours for Event</h2>
            <form id="new-event-form" method="post" class="styled-form">
                <label for="username">* Your Account Name </label>
                <input type="text" id="username" name="username" required placeholder="Enter account name"> 
                <input type="submit" value="Change Volunteer Hours" class="button primary-button">
            </form>
            <a class="button cancel" href="index.php" style="margin-top: -.5rem">Return to Dashboard</a>
        </main>
    </div>
</body>
</html>
