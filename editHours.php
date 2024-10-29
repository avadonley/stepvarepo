<?php
    // Make session information accessible, allowing us to associate
    // data with the logged-in user.
    session_cache_expire(30);
    session_start();

    ini_set("display_errors",1);
    error_reporting(E_ALL);

    $loggedIn = false;
    $accessLevel = 0;
    $userID = null;
    if (isset($_SESSION['_id'])) {
        $loggedIn = true;
        // 0 = not logged in, 1 = standard user, 2 = manager (Admin), 3 super admin (TBI)
        $accessLevel = $_SESSION['access_level'];
        $userID = $_SESSION['_id'];
    } 
    // Require admin privileges
    if ($accessLevel < 1) {
        header('Location: login.php');
        echo 'bad access level';
        die();
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require_once('include/input-validation.php');
        require_once('database/dbEvents.php');
        require_once('database/dbPersons.php');
        $args = sanitize($_POST, null);
        $required = array(
            "username"//, "name"
        );
        if (!wereRequiredFieldsSubmitted($args, $required)) {
            echo 'bad form data';
            die();
        } else {
           // $eventname = $args['name'];
            $username = validateEmail($args['username']);
           // echo $eventname;
            if (!$username) {
                echo 'bad username';
                die();
            }
            else {
                echo $username;
                $events = get_events_attended_by_2($username);
                // Check if the array is not empty
                if (!empty($events)) {
                    echo "<h3>Events attended by $username:</h3>";
                    echo "<ul>";

                    // Loop through each event and display it
                    foreach ($events as $event) {
                        echo "<li>";
                        
                        // Assuming $event is an associative array with keys like 'event_name' and 'date'
                        echo "Event ID: " . htmlspecialchars($event['eventID']) . "<br>";
                        $eventName = get_event_from_id($event['eventID']);
                        echo "Event Name: " . htmlspecialchars($eventName) . "<br>";
                        echo "Start Time: " . htmlspecialchars($event['start_time']) . "<br>";
                        echo "End Time: " . htmlspecialchars($event['end_time']) . "<br>";
                        echo '
                        <a class="button" href="editTimes.php?id=' . $event['eventID'] . '?user=' . $username . '?old_start_time=' . $event['start_time'] . '">
                            Edit Event
                        </a>';
                        echo "</li>";
                    }
                    
                    echo "</ul>";
                    //$tot_hours = get_tot_vol_hours("total_vol_hours", 'Active',NULL,NULL,NULL,NULL);
                    //echo $tot_hours;


                } else {
                    echo "<p>No events attended by $username to edit hours for.</p>";
                    die();
                }
            }
            die();
            // $validated = validate12hTimeRangeAndConvertTo24h($args["start-time"], "11:59 PM");
            // if (!$validated) {
            //     echo 'bad time range';
            //     die();
            // }
            // $startTime = $args['start-time'] = $validated[0];
            // $date = $args['date'] = validateDate($args["date"]);
            // //$capacity = intval($args["capacity"]);
            // $abbrevLength = strlen($args['abbrev-name']);
            // if (!$startTime || !$date || $abbrevLength > 11){
            //     echo 'bad args';
            //     die();
            // }
            // $id = create_event($args);
            // if(!$id){
            //     echo "Oopsy!";
            //     die();
            // }
            // require_once('include/output.php');
            
            // $name = htmlspecialchars_decode($args['name']);
            // $startTime = time24hto12h($startTime);
            // $date = date('l, F j, Y', strtotime($date));
            // require_once('database/dbMessages.php');
            // system_message_all_users_except($userID, "Your hours have been changed", "\r\n\r\nThe [$name](event: $id) event time at $startTime on $date was changed for your hours!\r\nSign up today!");
            // header("Location: event.php?id=$id&createSuccess");
            // die();
        }
    }
    $date = null;
    if (isset($_GET['date'])) {
        $date = $_GET['date'];
        $datePattern = '/[0-9]{4}-[0-9]{2}-[0-9]{2}/';
        $timeStamp = strtotime($date);
        if (!preg_match($datePattern, $date) || !$timeStamp) {
            header('Location: calendar.php');
            die();
        }
    }

    // get animal data from database for form
    // Connect to database
    include_once('database/dbinfo.php'); 
    $con=connect();  
    // Get all the animals from animal table
    $sql = "SELECT * FROM `dbeventpersons`";
    $all_animals = mysqli_query($con,$sql);

?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>ODHS Medicine Tracker | Create Event</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Change Hours Within an Event</h1>
        <main class="date">
            <h2>Change Hours for Event</h2>
            <form id="new-event-form" method="post">
              <!--  <label for="name">* Event Name </label>
                <input type="text" id="name" name="name" required placeholder="Enter name"> 
                <!-- <label for="name">* Abbreviated Event Name</label> 
                <input type="text" id="abbrev-name" name="abbrev-name" maxlength="11" required placeholder="Enter name that will appear on calendar">
-->
                <label for="name">* Your Account Name </label>
                <input type="text" id="username" name="username" required placeholder="Enter account name"> 
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