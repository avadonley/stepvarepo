<?php
    // Template for new VMS pages. Base your new page on this one

    // Make session information accessible, allowing us to associate
    // data with the logged-in user.
    session_cache_expire(30);
    session_start();

    $loggedIn = false;
    $accessLevel = 0;
    $userID = null;
    if (isset($_SESSION['_id'])) {
        $loggedIn = true;
        // 0 = not logged in, 1 = standard user, 2 = manager (Admin), 3 super admin (TBI)
        $accessLevel = $_SESSION['access_level'];
        $userID = $_SESSION['_id'];
    }  
    include 'database/dbEvents.php';
    //include 'domain/Event.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $eventID = $_POST['event_id'] ?? null;
        if ($eventID && remove_user_from_event($eventID, $userID)) {
            echo "Successfully canceled your registration for the event.";
        } else {
            echo "Failed to cancel the registration.";
        }
    }
    
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <link rel="stylesheet" href="css/messages.css"></link>
        <script src="js/messages.js"></script>
        <title>StepVA Volunteer System | Events</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <?php require_once('database/dbEvents.php');?>
        <h1>Events</h1>
        <main class="general">
            <?php 
                //require_once('database/dbMessages.php');
                //$messages = get_user_messages($userID);
                //require_once('database/dbevents.php');
                //require_once('domain/Event.php');
                $events = get_all_events();
                $today = new DateTime(); // Current date
                
                // Filter out expired events
                $upcomingEvents = array_filter($events, function($event) use ($today) {
                    $eventDate = new DateTime($event->getDate());
                    return $eventDate >= $today; // Only include events on or after today
                });

                if (sizeof($upcomingEvents) > 0): ?>
                <div class="table-wrapper">
                    <table class="general">
                        <thead>
                            <tr>
                                <th style="width:1px">Restricted</th>
                                <th>Title</th>
                                <th style="width:1px">Date</th>
                                <th style="width:1px"></th>
                            </tr>
                        </thead>
                        <tbody class="standout">
                            <?php 
                                foreach ($upcomingEvents as $event) {
                                    $eventID = $event->getID();
                                    $title = $event->getName();
                                    $date = $event->getDate();
                                    $startTime = $event->getStartTime();
                                    $endTime = $event->getEndTime();
                                    $description = $event->getDescription();
                                    $capacity = $event->getCapacity();
                                    $completed = $event->getCompleted();
                                    $event_type = $event->getEventType();
                                    $restricted_signup = $event->getRestrictedSignup();
                                    $restricted_signup = $restricted_signup == 0 ? "No" : "Yes";

                                    // Check if the user is signed up
                                    $isSignedUp = check_if_signed_up($eventID, $userID);

                                    echo "
                                    <tr data-event-id='$eventID'>
                                        <td>$restricted_signup</td>
                                        <td><a href='event.php?id=$eventID'>$title</a></td>
                                        <td>$date</td>
                                        <td>";
                                    
                                    // Show appropriate button
                                    if ($isSignedUp) {
                                        echo "
                                        <form method='POST' style='display:inline;'>
                                            <input type='hidden' name='event_id' value='$eventID'>
                                            <button type='submit' class='button danger' onclick='return confirm(\"Are you sure you want to cancel this event?\");'>Cancel</button>
                                        </form>";
                                    } else {
                                        echo "<a class='button sign-up' href='eventSignUp.php?event_name=" . urlencode($title) . "&restricted=" . urlencode($restricted_signup) . "'>Sign Up</a>";
                                    }

                                    echo "</td>
                                    </tr>";
                                }
                            ?>
                        </tbody>


                    </table>
                </div>
                <?php else: ?>
                <p class="no-events standout">There are currently no events available to view.<a class="button add" href="addEvent.php">Create a New Event</a> </p>
            <?php endif ?>
            <a class="button cancel" href="index.php">Return to Dashboard</a>
        </main>
    </body>
</html>