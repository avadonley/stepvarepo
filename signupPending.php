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
            <style>
            .centered {
            text-align: center;
            }
            </style>

            <?php require_once('header.php') ?>
            <h1>Sign-Up Request Sent!</h1>
            <p class="centered">The administrator will review your request shortly</p>
        </body>
    </html>

    <?php
    header("refresh:2; url=viewAllEvents.php");
    exit();