<?php
    session_cache_expire(30);
    session_start();
?>
    <!DOCTYPE html>
    <html>
        <head>
            <?php require_once('universal.inc') ?>
            <title>Step VA | Sign-Up for Event</title>
        </head>
        <body>
            <?php require_once('header.php') ?>
            <h1>Oops! You are already on the sign-up waitlist for this event.</h1>
        </body>
    </html>

    <?php
    header("refresh:2; url=viewAllEvents.php");
    exit();