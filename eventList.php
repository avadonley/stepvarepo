<?php
session_start();

ini_set("display_errors", 1);
error_reporting(E_ALL);

// Check access levels and initialize user data
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

// Handle admin username selection
if ($accessLevel >= 2) {
    if (isset($_GET['username'])) {
        $username = $_GET['username'];
    } else {
        header('Location: editHours.php');
        die();
    }
} elseif ($accessLevel == 1) {
    if (isset($_GET['username'])) {
        header('Location: eventList.php');
        die();
    }
    $username = $_SESSION['_id'];
}

require_once('include/input-validation.php');
require_once('database/dbEvents.php');
require_once('database/dbPersons.php');

// Fetch events attended by the user
$events = get_events_attended_by_2($username);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once('universal.inc'); ?>
    <link rel="stylesheet" href="css/editprofile.css" type="text/css" />
    <title>Step VA | User Events</title>
</head>
<body>
    <?php require_once('header.php'); ?>
    <div class="container">
        <h1>Events attended by <?php echo htmlspecialchars($username); ?></h1>
        <main class="general">
            <?php if (!empty($events)): ?>
                <ul class="event-list">
                    <?php foreach ($events as $event): ?>
                        <li class="event-item">
                            <form method="GET" action="editTimes.php">
                                <!-- Hidden inputs to pass data -->
                                <input type="hidden" name="eventId" value="<?php echo htmlspecialchars($event['eventID']); ?>" />
                                <input type="hidden" name="user" value="<?php echo htmlspecialchars($username); ?>" />
                                <input type="hidden" name="start_time" value="<?php echo htmlspecialchars($event['start_time']); ?>" />
                                <input type="hidden" name="end_time" value="<?php echo htmlspecialchars($event['end_time']); ?>" />
                                
                                <!-- Event details display -->
                                <div class="event-details">
                                    <p><strong>Event ID:</strong> <?php echo htmlspecialchars($event['eventID']); ?></p>
                                    <p><strong>Event Name:</strong> <?php echo htmlspecialchars(get_event_from_id($event['eventID'])); ?></p>
                                    <p><strong>Start Time:</strong> <?php echo htmlspecialchars($event['start_time']); ?></p>
                                    <p><strong>End Time:</strong> <?php echo htmlspecialchars($event['end_time']); ?></p>
                                </div>
                                
                                <!-- Submit button for editing -->
                                <button type="submit" class="button edit-button">Edit Event</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="no-events-message">No events attended by <?php echo htmlspecialchars($username); ?>.</p>
            <?php endif; ?>
            <a class="button cancel" href="index.php" style="margin-top: -.5rem">Return to Dashboard</a>
        </main>
    </div>
</body>
</html>
