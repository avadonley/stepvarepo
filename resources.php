<?php
    session_cache_expire(30);
    session_start();
    $loggedin = false;
    $accesslevel = 0;
    $userID = null;

    if (isset($_SESSION['_id'])) {
        $loggedIn = true;
        $accessLevel = $_SESSION['access_level'];
        $userID = $_SESSION['_id'];
    }

    if (!$loggedIn) {
        header('Location: login.php');
        die();
    }
    if (isset($_FILES['pdfFile'])) {
    
        $file = $_FILES['pdfFile'];
    
        if ($file['error'] === 0 && mime_content_type($file['tmp_name']) === 'application/pdf') {
    
            $fileName = $file['name'];
    
            $uploadPath = "uploads/".$fileName;

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
    
                echo "PDF file uploaded successfully!";
    
            } else {
    
                echo "Error uploading file!";
    
            }
    
        } else {
    
            echo "Invalid file type or error uploading!";
    
        }
    
    }
    

    
?>
<!DOCTYPE html>
    <html>
    <head>
        <?php require_once('universal.inc') ?>
        <link rel="stylesheet" href="css/messages.css"></link>
        <script src="js/messages.js"></script>
        <title>Upload PDF</title>
    </head>
        <body>
            <?php require_once('header.php') ?>
            <?php require_once('database/dbEvents.php');?>
            <h1>Upload Volunteer Resource</h1>
                <form action="resources.php" method="post" enctype="multipart/form-data">
                    <br></br>

                    <center>Select PDF to upload:
                    <input type="file" name="pdf" id="fileToUpload">
                    <p>
                    </p>
                    <div id="calendar-footer">
                        <input type="submit" value="Upload PDF"></center>
                    </div>
                </form>
            <p>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            </p>
            <div id="calendar-footer">
                <a class="button cancel" href="index.php">Return to Dashboard</a>
            </div>
    
        </body>
        
    </html>


