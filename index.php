<?php
    session_cache_expire(30);
    session_start();

    date_default_timezone_set("America/New_York");
    
    if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] < 1) {
        if (isset($_SESSION['change-password'])) {
            header('Location: changePassword.php');
        } else {
            header('Location: login.php');
        }
        die();
    }
        
    include_once('database/dbPersons.php');
    include_once('domain/Person.php');
    // Get date?
    if (isset($_SESSION['_id'])) {
        $person = retrieve_person($_SESSION['_id']);
    }
    $notRoot = $person->get_id() != 'vmsroot';
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require('universal.inc'); ?>
        <title>Step VA Volunteer System | Dashboard</title>
    </head>
    <body>
        <?php require('header.php'); ?>
        <h1>Dashboard</h1>
        <main class='dashboard'>
            <?php if (isset($_GET['pcSuccess'])): ?>
                <div class="happy-toast">Password changed successfully!</div>
            <?php elseif (isset($_GET['deleteService'])): ?>
                <div class="happy-toast">Service successfully removed!</div>
            <?php elseif (isset($_GET['serviceAdded'])): ?>
                <div class="happy-toast">Service successfully added!</div>
            <?php elseif (isset($_GET['animalRemoved'])): ?>
                <div class="happy-toast">Animal successfully removed!</div>
            <?php elseif (isset($_GET['locationAdded'])): ?>
                <div class="happy-toast">Location successfully added!</div>
            <?php elseif (isset($_GET['deleteLocation'])): ?>
                <div class="happy-toast">Location successfully removed!</div>
            <?php elseif (isset($_GET['registerSuccess'])): ?>
                <div class="happy-toast">Volunteer registered successfully!</div>
            <?php endif ?>
            <p>Welcome back, <?php echo $person->get_first_name() ?>!</p>
            <p>Today is <?php echo date('l, F j, Y'); ?>.</p>
            <div id="dashboard">
                <?php
                    //require_once('database/dbMessages.php');
                    $unreadMessageCount = 0; //get_user_unread_count($person->get_id());
                    $inboxIcon = 'inbox.svg';
                    if ($unreadMessageCount) {
                        $inboxIcon = 'inbox-unread.svg';
                    }
                    
                ?>
                
                <div class="dashboard-item" data-link="inbox.php">
                    <img src="images/<?php echo $inboxIcon ?>">
                    <span>Notifications<?php 
                        if ($unreadMessageCount > 0) {
                            echo ' (' . $unreadMessageCount . ')';
                        }
                    ?></span>
                </div>
                
                <div class="dashboard-item" data-link="calendar.php">
                    <img src="images/view-calendar.svg">
                    <span>View Calendar</span>
                </div>

                <div class="dashboard-item" data-link="viewAllEvents.php">
                    <img src="images/new-event.svg">
                    <span>Sign-Up for Event</span>
                </div>
                
                <!-- ADMIN ONLY -->
                <?php if ($_SESSION['access_level'] >= 2): ?>
                    <div class="dashboard-item" data-link="addEvent.php">
                        <img src="images/new-event.svg">
                        <span>Create Event</span>
                    </div>
                    
                    <div class="dashboard-item" data-link="personSearch.php">
                        <img src="images/person-search.svg">
                        <span>Find Volunteer</span>
                    </div>
                    <div class="dashboard-item" data-link="adminViewingEvents.php">
                        <img src="images/new-event.svg">
                        <span>View Events</span>
                    </div>
                    <div class="dashboard-item" data-link="register.php">
                        <img src="images/add-person.svg">
                        <span>Register Volunteer</span>
                    </div>
                    <div class="dashboard-item" data-link="editHours.php">
                        <img src="images/add-person.svg">
                        <span>View & Change Event Hours</span>
                    </div>
                <?php endif ?>

                <!-- FOR VOLUNTEERS AND PARTICIPANTS ONLY -->
                <?php if ($notRoot) : ?>
                    <div class="dashboard-item" data-link="viewProfile.php">
                        <img src="images/view-profile.svg">
                        <span>View Profile</span>
                    </div>
                    <div class="dashboard-item" data-link="editProfile.php">
                        <img src="images/manage-account.svg">
                        <span>Edit Profile</span>
                    </div>
                <?php endif ?>
                <?php if ($notRoot) : ?>
                    <div class="dashboard-item" data-link="volunteerReport.php">
                        <img src="images/volunteer-history.svg">
                        <span>View My Hours</span>
                    </div>
                <div class="dashboard-item" data-link="editHours.php">
                        <img src="images/add-person.svg">
                        <span>View & Change My Event Hours</span>
                    </div>
                <?php endif ?>
                <div class="dashboard-item" data-link="changePassword.php">
                    <img src="images/change-password.svg">
                    <span>Change Password</span>
                </div>
                <div class="dashboard-item" data-link="logout.php">
                    <img src="images/logout.svg">
                    <span>Log out</span>
                </div>
                <!-- autoredirects home as volunteer currently -->
                <!-- <div class="dashboard-item" data-link="editHours.php">
                        <img src="images/add-person.svg">
                        <span>View & Change Event Hours</span>
                </div> -->
            </div>
        </main>
    </body>
</html>