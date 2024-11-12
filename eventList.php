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

# security: if admin access, use ?username from link
if ($accessLevel >= 2) {
if (isset($_GET['username'])) {
    $username = $_GET['username'];
}
# if admin does not provide username, redirect to editHours.php
else {
    // Redirect back to the form if username is not provided
    header('Location: editHours.php'); // Change this to your form page name
    die();
}
}
# security: force user to stay as their username regardless of whats in the link
else if ($accessLevel == 1){ 
    // security: force user to not have ?user in link if its there
    if (isset($_GET['username'])) {
        header('Location: eventList.php');
        die();
    }
    $username = $_SESSION['_id'];
}


    
    // Include necessary files and sanitize input
    require_once('include/input-validation.php');
    require_once('database/dbEvents.php');
    require_once('database/dbPersons.php');

    // Fetch events attended by the user
    $events = get_events_attended_by_2($username);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <?php require_once('universal.inc') ?>
        <link rel="stylesheet" href="css/editprofile.css" type="text/css" />
        <title>Step VA | User Events</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <div class="container">
            <h1>Events attended by <?php echo htmlspecialchars($username); ?></h1>
            <main class="general">
                <?php
                if (!empty($events)) {
                    echo "<ul class='event-list'>";
                    
                    foreach ($events as $event) {
                        echo "<li class='event-item'>";
                        echo "<div class='event-details'>";
                        echo "<strong>Event ID:</strong> " . htmlspecialchars($event['eventID']) . "<br>";
                        
                        $eventName = get_event_from_id($event['eventID']);
                        echo "<strong>Event Name:</strong> " . htmlspecialchars($eventName) . "<br>";
                        echo "<strong>Start Time:</strong> " . htmlspecialchars($event['start_time']) . "<br>";
                        echo "<strong>End Time:</strong> " . $event['end_time'] . "<br>";
                        echo "</div>";
                        
                        echo '<a class="button edit-button" href="editTimes.php?id=' . $event['eventID'] . '&user=' . $username . '&old_start_time=' . $event['start_time'] . '">Edit Event</a>';
                        echo "</li>";
                    }
                    
                    echo "</ul>";
                } else {
                    echo "<p class='no-events-message'>No events attended by $username.</p>";
                }
                ?>
                <a class="button cancel" href="index.php" style="margin-top: -.5rem">Return to Dashboard</a>
            </main>
        </div>
    </body>
    </html>
    <?php
?>
