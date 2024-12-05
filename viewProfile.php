<?php
    // Make session information accessible, allowing us to associate
    // data with the logged-in user.
    session_cache_expire(30);
    session_start();

    $loggedIn = false;
    $accessLevel = 0;
    $userID = null;
    $isAdmin = false;
    if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] < 1) {
        header('Location: login.php');
        die();
    }
    if (isset($_SESSION['_id'])) {
        $loggedIn = true;
        // 0 = not logged in, 1 = standard user, 2 = manager (Admin), 3 super admin (TBI)
        $accessLevel = $_SESSION['access_level'];
        $isAdmin = $accessLevel >= 2;
        $userID = $_SESSION['_id'];
    } else {
        header('Location: login.php');
        die();
    }
    if ($isAdmin && isset($_GET['id'])) {
        require_once('include/input-validation.php');
        $args = sanitize($_GET);
        $id = strtolower($args['id']);
    } else {
        $id = $userID;
    }
    require_once('database/dbPersons.php');
    if (isset($_GET['removePic'])) {
      if ($_GET['removePic'] === 'true') {
        remove_profile_picture($id);
      }
    }

    $user = retrieve_person($id);
    $viewingOwnProfile = $id == $userID;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (isset($_POST['url'])) {
        if (!update_profile_pic($id, $_POST['url'])) {
          header('Location: viewProfile.php?id='.$id.'&picsuccess=False');
        } else {
          header('Location: viewProfile.php?id='.$id.'&picsuccess=True');
        }
      }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <link rel="stylesheet" href="css/editprofile.css" type="text/css" />
        <title>Step VA | View User</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </head>
    <body>
        <?php 
            require_once('header.php'); 
            require_once('include/output.php');
        ?>
        <h1>View Profile</h1>
        <main class="general">
        <?php
            if (isset($_GET['picsuccess'])) {
              $picsuccess = $_GET['picsuccess'];
              if ($picsuccess === 'True') {
                echo '<div class="happy-toast">Profile Picture Updated Successfully!</div>';
              } else if ($picsuccess === 'False') {
                echo '<div class="error-toast">There was an error updating the Profile Picture!</div>';
              }
            }
        ?>
            <?php if ($id == 'vmsroot'): ?>
                <div class="error-toast">The root user does not have a profile.</div>
                </main></body></html>
                <?php die() ?>
            <?php elseif (!$user): ?>
                <div class="error-toast">User does not exist!</div>
                </main></body></html>
                <?php die() ?>
            <?php endif ?>
            <?php if (isset($_GET['editSuccess'])): ?>
                <div class="happy-toast">Profile updated successfully!</div>
            <?php endif ?>
            <?php if (isset($_GET['rscSuccess'])): ?>
                <div class="happy-toast">User's role and/or status updated successfully!</div>
            <?php endif ?>
            <?php if ($viewingOwnProfile): ?>
                <h2>Your Profile 
                    <a href="editProfile.php" title="Edit Profile" class="edit-icon">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                </h2>
            <?php else: ?>
                <h2>Viewing <?php echo $user->get_first_name() . ' ' . $user->get_last_name() ?></h2>
            <?php endif ?>
            
            <?php if ($accessLevel < 2) : ?>
                <p>Click <a href="volunteerReport.php?id=<?php echo $user->get_id() ?>">here</a> to view your volunteering report.</p>
            <?php else : ?>
                <p>Click <a href="volunteerReport.php?id=<?php echo $user->get_id() ?>">here</a> to view <?php echo $user->get_first_name() ?> <?php echo $user->get_last_name() ?>'s volunteering report.</p>
            <?php endif ?>

            <fieldset class="section-box">
                <legend>Personal Information</legend>
                
                <div class="field-pair">
                    <label>Username</label>
                    <p><?php echo $user->get_id() ?></p>
                </div>

                <div class="field-pair">
                    <label>Name</label>
                    <p><?php echo $user->get_first_name() ?> <?php echo $user->get_last_name() ?></p>
                </div>

                <div class="field-pair">
                    <label>Date of Birth</label>
                    <p><?php echo date('d/m/Y', strtotime($user->get_birthday())) ?></p>
                </div>

                <div class="field-pair">
                    <label>Address</label>
                    <p><?php echo $user->get_street_address() . ', ' . $user->get_city() . ', ' . $user->get_state() . ' ' . $user->get_zip_code() ?></p>
                </div>

            </fieldset>

            <fieldset class="section-box">
                <legend>Contact Information</legend>

                <div class="field-pair">
                    <label>E-mail</label>
                    <p><a href="mailto:<?php echo $user->get_email() ?>"><?php echo $user->get_email() ?></a></p>
                </div>

                <div class="field-pair">
                    <label>Phone Number</label>
                    <p><a href="tel:<?php echo $user->get_phone1() ?>"><?php echo formatPhoneNumber($user->get_phone1()) ?></a> (<?php echo ucfirst($user->get_phone1type()) ?>)</p>
                </div>

                <div class="field-pair">
                    <label>Preferred Feedback Method</label>
                    <p><?php echo ucfirst($user->get_preferred_feedback_method()) ?></p>
                </div>
            </fieldset>

            <fieldset class="section-box">
                <legend>Emergency Contact</legend>

                <div class="field-pair">
                    <label>Name</label>
                    <p><?php echo $user->get_emergency_contact_first_name() . ' ' . $user->get_emergency_contact_last_name() ?></p>
                </div>

                <div class="field-pair">
                    <label>Relation</label>
                    <p><?php echo $user->get_emergency_contact_relation() ?></p>
                </div>

                <div class="field-pair">
                    <label>Phone Number</label>
                    <p><a href="tel:<?php echo $user->get_emergency_contact_phone() ?>"><?php echo formatPhoneNumber($user->get_emergency_contact_phone()) ?></a> (<?php echo ucfirst($user->get_emergency_contact_phone_type()) ?>)</p>
                </div>
            </fieldset>

            <fieldset class="section-box">
                <legend>Volunteer Training</legend>

                <p>Details about the volunteer's training status.</p>

                <div class="field-pair">
                    <label>Training Completed</label>
                    <p>
                        <?php 
                            $trainingComplete = $user->get_training_complete();
                            echo ($trainingComplete == '1') ? 'Yes' : 'No'; 
                        ?>
                    </p>
                </div>

                <?php if ($trainingComplete == '1'): ?>
                    <div class="field-pair" id="training-date-container">
                        <label>Training Date</label>
                        <p>
                            <?php 
                                $trainingDate = $user->get_training_date();
                                echo $trainingDate ? date('d/m/Y', strtotime($trainingDate)) : 'Not Provided';
                            ?>
                        </p>
                    </div>
                <?php endif; ?>
            </fieldset>

            <fieldset class="section-box">
                <legend>Volunteer Orientation</legend>

                <p>Details about the volunteer's orientation status.</p>

                <div class="field-pair">
                    <label>Orientation Completed</label>
                    <p>
                        <?php 
                            $orientationComplete = $user->get_orientation_complete();
                            echo ($orientationComplete == '1') ? 'Yes' : 'No'; 
                        ?>
                    </p>
                </div>

                <?php if ($orientationComplete == '1'): ?>
                    <div class="field-pair" id="orientation-date-container">
                        <label>Orientation Date</label>
                        <p>
                            <?php 
                                $orientationDate = $user->get_orientation_date();
                                echo $orientationDate ? date('d/m/Y', strtotime($orientationDate)) : 'Not Provided';
                            ?>
                        </p>
                    </div>
                <?php endif; ?>
            </fieldset>

            <fieldset class="section-box">
                <legend>Volunteer Background Check</legend>

                <p>Details about the volunteer's background check status.</p>

                <div class="field-pair">
                    <label>Background Check Completed</label>
                    <p>
                        <?php 
                            $backgroundComplete = $user->get_background_complete();
                            echo ($backgroundComplete == '1') ? 'Yes' : 'No'; 
                        ?>
                    </p>
                </div>

                <?php if ($backgroundComplete == '1'): ?>
                    <div class="field-pair" id="background-date-container">
                        <label>Background Check Date</label>
                        <p>
                            <?php 
                                $backgroundDate = $user->get_background_date();
                                echo $backgroundDate ? date('d/m/Y', strtotime($backgroundDate)) : 'Not Provided';
                            ?>
                        </p>
                    </div>
                <?php endif; ?>
            </fieldset>


            <fieldset class="section-box">
                <legend>Volunteer Information</legend>
                
                <!--
                Volunteer or participant
                <div class="field-pair">
                    <label>Role</label>
                    <p><?php echo ucfirst($user->get_type()) ?></p>
                </div>
                -->

                <div class="field-pair">
                    <label>School Affiliation</label>
                    <p><?php echo $user->get_school_affiliation() ?></p>
                </div>

                <div class="field-pair">
                    <label>T-Shirt Size</label>
                    <p><?php echo ucfirst($user->get_tshirt_size() ?: "Not specified") ?></p>
                </div>

                <div class="field-pair">
                    <label>Accommodations</label>
                    <p><?php echo ucfirst($user->get_disability_accomodation_needs() ?: "Not specified") ?></p>
                </div>
                
                <div class="field-pair">
                    <label>Professional Experience</label>
                    <p><?php echo ucfirst($user->get_professional_experience() ?: "Not specified") ?></p>
                </div>

                <div class="field-pair">
                    <label>Hobbies</label>
                    <p><?php echo ucfirst($user->get_hobbies() ?: "Not specified") ?></p>
                </div>

                <div class="field-pair">
                    <label>How You Heard of StepVA</label>
                    <p><?php echo ucfirst($user->get_how_you_heard_of_stepva() ?: "Not specified") ?></p>
                </div>

                <!-- Adding Photo Release Information -->
                <div class="field-pair">
                    <label>Photo Release Restrictions</label>
                    <p><?php echo ucfirst($user->get_photo_release()) ?></p>
                </div>

                <div class="field-pair">
                    <label>Photo Release Notes</label>
                    <p><?php echo ucfirst($user->get_photo_release_notes() ?: "Not specified") ?></p>
                </div>
            </fieldset>

            <fieldset class="section-box">
                <legend>Emergency Contact Information</legend>

                <div class="field-pair">
                    <label>Emergency Contact First Name</label>
                    <p><?php echo ucfirst($user->get_emergency_contact_first_name()) ?></p>
                </div>

                <div class="field-pair">
                    <label>Emergency Contact Last Name</label>
                    <p><?php echo ucfirst($user->get_emergency_contact_last_name()) ?></p>
                </div>

                <div class="field-pair">
                    <label>Emergency Contact Phone</label>
                    <p><?php echo $user->get_emergency_contact_phone() ?></p>
                </div>

                <div class="field-pair">
                    <label>Emergency Contact Phone Type</label>
                    <p><?php echo ucfirst($user->get_emergency_contact_phone_type()) ?></p>
                </div>

                <div class="field-pair">
                    <label>Emergency Contact Relation</label>
                    <p><?php echo ucfirst($user->get_emergency_contact_relation()) ?></p>
                </div>
            </fieldset>



            <a class="button" href="editProfile.php<?php if ($id != $userID) echo '?id=' . $id ?>">Edit Profile</a>
            <?php if ($id != $userID): ?>
                <?php if (($accessLevel == 2 && $user->get_access_level() == 1) || $accessLevel >= 3): ?>
                    <a class="button" href="resetPassword.php?id=<?php echo htmlspecialchars($_GET['id']) ?>">Reset Password</a>
                <?php endif ?>
                <a class="button" href="volunteerReport.php?id=<?php echo htmlspecialchars($_GET['id']) ?>">View Volunteer Hours</a>
                <a class="button cancel" href="personSearch.php">Return to User Search</a>
            <?php else: ?>
                <a class="button" href="changePassword.php">Change Password</a>
                <a class="button" href="volunteerReport.php">View Volunteer Hours</a>
                <a class="button cancel" href="index.php">Return to Dashboard</a>
            <?php endif ?>
        </main>
    </body>
</html>