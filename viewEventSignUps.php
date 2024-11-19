<?php

session_cache_expire(30);
session_start();

// Ensure user is logged in
if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] < 1) {
    header('Location: login.php');
    die();
}

require_once('include/input-validation.php');
$args = sanitize($_GET);

if (isset($args['id'])) {
    $id = $args['id'];
} else {
    header('Location: dashboard.php'); // Redirect to a general page if ID is missing
    die();
}

include_once('database/dbEvents.php');

// Validate the ID by querying the database
$event_info = fetch_event_by_id($id);
if ($event_info === null) {
    echo 'Invalid event ID';
    die();
}

include_once('database/dbPersons.php');
$access_level = $_SESSION['access_level'];
$user = retrieve_person($_SESSION['_id']);
$active = $user->get_status() === 'Active';

// Fetch signups for the event (user IDs and positions)
$signups = fetch_event_signups($id);

ini_set('display_errors', 1);
error_reporting(E_ALL);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once('universal.inc'); ?>
    <title>View Event Details | <?php echo htmlspecialchars($event_info['name']); ?></title>
    <link rel="stylesheet" href="css/messages.css" /> <!-- Ensure you are using the same stylesheet -->
</head>

<body>
    <?php require_once('header.php'); ?>
    <main class="general">
        <h1><?php echo htmlspecialchars($event_info['name']); ?></h1>
        <p>Date: <?php echo date('l, F j, Y', strtotime($event_info['date'])); ?></p>
        <p>Description: <?php echo htmlspecialchars($event_info['description']); ?></p>

        <!-- Table displaying signed up users (User IDs and Positions) -->
        <h2>Signed Up Users</h2>
        <?php if (count($signups) > 0): ?>
            <div class="table-wrapper">
                <table class="general">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Position</th>
                        </tr>
                    </thead>
                    <tbody class="standout">
                        <?php foreach ($signups as $signup): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($signup['userID']); ?></td>
                                <td><?php echo htmlspecialchars($signup['position']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No users have signed up for this event yet.</p>
        <?php endif; ?>
        
        <!-- Button to return to dashboard -->
        <a class="button cancel" href="dashboard.php">Return to Dashboard</a>
    </main>
</body>

</html>
