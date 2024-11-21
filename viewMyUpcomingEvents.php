<?php
session_cache_expire(30);
session_start();  // Start the session

// Check if the user is logged in
if (!isset($_SESSION['_id']) || empty($_SESSION['_id'])) {
    // User not logged in, redirect to login page
    header('Location: login.php');
    exit();  // Ensure no further code execution
}

// Check for appropriate access level (e.g., level 1 or above)
if ($_SESSION['access_level'] < 1) {
    // Redirect to dashboard or another appropriate page if access level is insufficient
    header('Location: index.php');
    exit();  // Ensure no further code execution
}

require_once('include/input-validation.php');
require_once('database/dbEvents.php');
require_once('database/dbPersons.php');

ini_set('display_errors', 1);
error_reporting(E_ALL);

$user_id = $_SESSION['_id']; // Store user ID from session

// Handle cancellation of events
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = $_POST['event_id'] ?? null;

    if (!$event_id) {
        echo "Event ID is missing.";
        die();
    }

    // Fetch the event name before canceling
    $event_name = fetch_event_name($event_id);

    if (remove_user_from_event($event_id, $user_id)) {
        $cancel_success = "Successfully canceled your registration for event: $event_name.";
    } else {
        $cancel_error = "Failed to cancel registration for event $event_id.";
    }
}

// Fetch events the user is signed up for
function fetch_user_events($user_id) {
    $connection = connect();
    $query = "SELECT e.id, e.name, e.date 
              FROM dbevents e
              INNER JOIN dbeventpersons ep ON e.id = ep.eventID
              WHERE ep.userID = '$user_id'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die('Query failed: ' . mysqli_error($connection));
    }

    $events = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $events[] = $row;
    }

    mysqli_close($connection);
    return $events;
}

// Fetch event name by ID
function fetch_event_name($event_id) {
    $connection = connect();
    $query = "SELECT name FROM dbevents WHERE id = '$event_id'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die('Query failed: ' . mysqli_error($connection));
    }

    $event = mysqli_fetch_assoc($result);
    mysqli_close($connection);

    return $event['name'] ?? 'Unknown Event';
}

$upcoming_events = fetch_user_events($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once('universal.inc'); ?>
    <title>My Upcoming Events</title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
    <?php require_once('header.php'); ?>

    <h1>My Upcoming Events</h1>
    <main class="general">
        <?php if (isset($cancel_success)): ?>
            <p class="success"><?php echo htmlspecialchars($cancel_success); ?></p>
        <?php elseif (isset($cancel_error)): ?>
            <p class="error"><?php echo htmlspecialchars($cancel_error); ?></p>
        <?php endif; ?>

        <?php if (count($upcoming_events) > 0): ?>
            <div class="table-wrapper">
                <table class="general">
                    <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($upcoming_events as $event): ?>
                            <tr>
                                <td>
                                    <!-- Link the event name to the event.php page with event ID as query parameter -->
                                    <a href="event.php?id=<?php echo htmlspecialchars($event['id']); ?>">
                                        <?php echo htmlspecialchars($event['name']); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($event['date']); ?></td>
                                <td>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event['id']); ?>">
                                        <button type="submit" class="button danger" onclick="return confirm('Are you sure you want to cancel this event?');">
                                            Cancel
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>You have no upcoming events.</p>
        <?php endif; ?>

        <a class="button cancel" href="index.php">Return to Dashboard</a>
    </main>
</body>
</html>
