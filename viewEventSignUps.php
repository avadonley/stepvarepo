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
    <?php require_once('header.php') ?>
    <?php require_once('database/dbEvents.php');?>
    <h1>View Sign-Up List</h1>
    <main class="general">
        
        <!-- Table displaying signed up users (First Name, Last Name, User ID, and Position) -->
        <h2><?php echo htmlspecialchars($event_info['name']); ?></h2>
        <?php if (count($signups) > 0): ?>
            <div class="table-wrapper">
                <table class="general">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>User ID</th>
                            <th>Position</th>
                        </tr>
                    </thead>
                    <tbody class="standout">
                        <?php foreach ($signups as $signup): 
                            // Fetch the user's first and last name based on userID
                            $user_info = retrieve_person($signup['userID']);
                            
                            // Determine the position label based on the position value
                            $position_label = '';
                            if ($signup['position'] === 'p') {
                                $position_label = 'Participant';
                            } elseif ($signup['position'] === 'v') {
                                $position_label = 'Volunteer';
                            } else {
                                $position_label = 'Unknown'; // Default if position is not recognized
                            }
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user_info->get_first_name()); ?></td>
                                <td><?php echo htmlspecialchars($user_info->get_last_name()); ?></td>
                                <td><a href="viewProfile.php?id=<?php echo urlencode($signup['userID']); ?>"><?php echo htmlspecialchars($signup['userID']); ?></a></td>
                                <td><?php echo htmlspecialchars($position_label); ?></td>
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
