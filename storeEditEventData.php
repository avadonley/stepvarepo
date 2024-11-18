<?php
// Start the session
session_start();

// Store the data from POST in session variables
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['eventID'] = $_POST['eventID'];
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['start_time'] = $_POST['start_time'];
    $_SESSION['end_time'] = $_POST['end_time'];

    // Redirect to editTimes.php
    header("Location: editTimes.php");
    exit();
}
?>
