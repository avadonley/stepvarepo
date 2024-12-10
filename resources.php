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
        <body>
            <form action="upload.php" method="post" enctype="multipart/form-data">
                Select PDF to upload:
                <input type="file" name="pdf" id="fileToUpload">
                
                <input type="submit" value="Upload PDF">
            </form>
            
        </body>
    </html>



