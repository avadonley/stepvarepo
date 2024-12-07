<?php
    // Template for new VMS pages. Base your new page on this one

    // Make session information accessible, allowing us to associate
    // data with the logged-in user.
    session_cache_expire(30);
    session_start();
    date_default_timezone_set("America/New_York");

    $loggedIn = false;
    $accessLevel = 0;
    $userID = null;
    if (isset($_SESSION['_id'])) {
        $loggedIn = true;
        // 0 = not logged in, 1 = standard user, 2 = manager (Admin), 3 super admin (TBI)
        $accessLevel = $_SESSION['access_level'];
        $userID = $_SESSION['_id'];
    }
    if ($accessLevel < 1) {
        header('Location: login.php');
        die();
    }
    // if (!isset($_GET['date'])) {
    //     header('Location: calendar.php');
    //     die();
    // }
    require_once('include/input-validation.php');
    $get = sanitize($_GET);
    $eventIDGiven = $get['id'];
    
    // Split the string by "?"
    //$parts = explode('?', $eventIDGiven);

    // Assign each part to a variable
    //$id = $parts[0];
    //parse_str($parts[1], $userArray);  // Extracts "user" key-value pair
    //$user = $userArray['user'];
    //parse_str($parts[2], $timeArray);  // Extracts "start_time" key-value pair
    //$old_start_time = $timeArray['old_start_time'];

    // Output the variables
    //echo "ID: $id\n";
    //echo "User: $user\n";
    //echo "Start Time: $old_start_time\n";

    // Split the string by "?"
    $parts = explode('?', $eventIDGiven);

    // Assign each part to a variable
    $id = $parts[0];

    // Check if $parts[1] exists before trying to parse it
    if (isset($parts[1])) {
        parse_str($parts[1], $userArray);
        $user = isset($userArray['user']) ? $userArray['user'] : 'Unknown User';
    } else {
        $user = 'Unknown User';
    }

    // Check if $parts[2] exists before trying to parse it
    if (isset($parts[2])) {
        parse_str($parts[2], $timeArray);
        $old_start_time = isset($timeArray['old_start_time']) ? $timeArray['old_start_time'] : 'No Start Time';
    } else {
        $old_start_time = 'No Start Time';
    }

    // Output the variables
    echo "ID: $id\n";
    echo "User: $user\n";
    echo "Start Time: $old_start_time\n";


    // $datePattern = '/[0-9]{4}-[0-9]{2}-[0-9]{2}/';
    $timeStamp = strtotime($old_start_time);
    // if (!preg_match($datePattern, $date) || !$timeStamp) {
    //     header('Location: calendar.php');
    //     die();
    // }
?>
<?php
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the submitted form data and sanitize inputs
    $startTime = trim($_POST['start-time']);
    $endTime = trim($_POST['end-time']);

    // Check if both start time and end time are not empty
    if (!empty($startTime) && !empty($endTime)) {
        // Convert the times to a standard format if needed
    // Convert the times to a standard format with date
    $startDateTime = date("Y-m-d H:i:s", strtotime($eventDate . ' ' . $startTime));
    $endDateTime = date("Y-m-d H:i:s", strtotime($eventDate . ' ' . $endTime));

    $formattedStartDateTime = $startDateTime->format('Y-m-d H:i:s');
    $formattedEndDateTime = $endDateTime->format('Y-m-d H:i:s');

    echo $formattedStartDateTime;
    echo "<br>";
    echo $formattedEndDateTime;
    echo "<br>";

        // Connect to the database
        $connection = connect(); // Assumes you have a connect() function for database connection

        // Prepare the SQL query to update the hours
        $query = "UPDATE dbpersonhours SET start_time = ?, end_time = ? WHERE personID = ? AND eventID = ?";

        // Prepare statement to avoid SQL injection
        $stmt = mysqli_prepare($connection, $query);

        // Replace these with actual values for personID and eventID
        $eventID = $id; // e.g., from session or other source
        $personID = $user; // e.g., passed to this page or session
        //$oldStartTime = $old_start_time;

        // Bind parameters to the SQL query
        mysqli_stmt_bind_param($stmt, "ssss", $startDateTime, $endDateTime, $personID, $eventID);

        // Execute the query
        if (mysqli_stmt_execute($stmt)) {
            echo "Volunteer hours updated successfully.";
        } else {
            echo "Error updating hours: " . mysqli_error($connection);
        }

        // Close statement and connection
        mysqli_stmt_close($stmt);
        mysqli_close($connection);

    } else {
        echo "Please fill in both start and end times.";
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>Step VA | View Date</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Edit Event</h1>
        <main class="date">
            <h2>Edit this time: <?php echo date('l, F j, Y, H:i:s', $timeStamp) ?></h2>            <form id="new-event-form" method="post">
              <!--  <label for="name">* Event Name </label>
                <input type="text" id="name" name="name" required placeholder="Enter name"> 
                <!-- <label for="name">* Abbreviated Event Name</label> 
                <input type="text" id="abbrev-name" name="abbrev-name" maxlength="11" required placeholder="Enter name that will appear on calendar">
-->
                <label for="name">* New Start Time </label>
                <!--- add pattern -->
                <input type="text" id="start-time" name="start-time" required placeholder="Enter new start time">
                <label for="name">* New End Time </label>
                 <!--- add pattern -->
                <input type="text" id="end-time" name="end-time" required placeholder="Enter new end time">
                
                <!--
                <label for="name">* What Time Did You Arrive For This Event? </label>
                <input type="text" id="start-time" name="start-time" pattern="([1-9]|10|11|12):[0-5][0-9] ?([aApP][mM])" required placeholder="Enter arrival time. Ex. 12:00 PM">
                <label for="name">* What Time Did You Leave For This Event? </label>
                <input type="text" id="departure-time" name="departure-time" pattern="([1-9]|10|11|12):[0-5][0-9] ?([aApP][mM])" required placeholder="Enter departure time. Ex. 3:00 PM">
-->

                <p></p>
                <br/>
                <p></p>
                <input type="submit" value="Change Volunteer Hours">
            </form>

            <a class="button cancel" href="index.php" style="margin-top: -.5rem">Return to Dashboard</a>
  
                <!--
                <label for="name">* Animal</label>
                <select for="name" id="animal" name="animal" required>
                    <?php 
                        // fetch data from the $all_animals variable
                        // and individually display as an option
                        while ($animal = mysqli_fetch_array(
                                $all_animals, MYSQLI_ASSOC)):; 
                    ?>
                    <option value="<?php echo $animal['id'];?>">
                        <?php echo $animal['name'];?>
                    </option>
                    <?php 
                        endwhile; 
                        // terminate while loop
                    ?>
                </select>
                <br/>
                <p></p>
                <input type="submit" value="Create Event">
            </form>
                <?php if ($date): ?>
                    <a class="button cancel" href="calendar.php?month=<?php echo substr($date, 0, 7) ?>" style="margin-top: -.5rem">Return to Calendar</a>
                <?php else: ?>
                    <a class="button cancel" href="index.php" style="margin-top: -.5rem">Return to Dashboard</a>
                <?php endif ?>

                <!-- Require at least one checkbox be checked -->
                <script type="text/javascript">
                    $(document).ready(function(){
                        var checkboxes = $('.checkboxes');
                        checkboxes.change(function(){
                            if($('.checkboxes:checked').length>0) {
                                checkboxes.removeAttr('required');
                            } else {
                                checkboxes.attr('required', 'required');
                            }
                        });
                    });

                </script>
        </main>
    </body>
</html>