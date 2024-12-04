<?php
    // In this section, I've removed code that ensures the user is already logged in.
    // This is because we want users without accounts to be able to create new accounts.

    // Author: Lauren Knight
    // Description: Registration page for new volunteers

    require_once('include/input-validation.php');
?>

<!DOCTYPE html>
<html>
<head>
    <?php require_once('universal.inc'); ?>
    <title>Step VA | Register</title>
</head>
<body>
    <?php
        require_once('header.php');
        require_once('domain/Person.php');
        require_once('database/dbPersons.php');
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // make every submitted field SQL-safe except for password
            $ignoreList = array('password');
            $args = sanitize($_POST, $ignoreList);

            // echo "<p>The form was submitted:</p>";
            // foreach ($args as $key => $value) {
            //     echo "<p>$key: $value</p>";
            // }

            // required fields
            $required = array(
                'first_name', 'last_name', 'birthdate',
                'street_address', 'city', 'state', 'zip', 
                'email', 'phone', 'phone_type', 'emergency_contact_first_name',
                'emergency_contact_last_name',
                'emergency_contact_relation', 'emergency_contact_phone', 'tshirt_size',
                'school_affiliation', 'username', 'password',
                'volunteer_or_participant', 'photo_release', 'photo_release_notes'
            );
            
            // Capture the volunteer_or_participant value from the form
            $volunteer_or_participant = isset($_POST['volunteer_or_participant']) ? $_POST['volunteer_or_participant'] : null;  

            
            if ($volunteer_or_participant == 'v') {
                // Check for the training_complete and training_date fields
                if (empty($_POST['training_complete']) || empty($_POST['training_date'])) {
                    $errors[] = "Training complete and training date are required for volunteers.";
                }
            }
            

            $optional = array(
                'how_you_heard_of_stepva', 'preferred_feedback_method', 'hobbies',
                'skills', 'professional_experience', 'disability_accomodation_needs'
            );

            $errors = false;
            if (!wereRequiredFieldsSubmitted($args, $required)) {
                $errors = true;
            }
            $first_name = $args['first_name'];
            $last_name = $args['last_name'];
            $birthday = validateDate($args['birthdate']);
            if (!$birthday) {
                $errors = true;
                echo 'bad dob';
            }

            $street_address = $args['street_address'];
            $city = $args['city'];
            $state = $args['state'];
            if (!valueConstrainedTo($state, array('AK', 'AL', 'AR', 'AZ', 'CA', 'CO', 'CT', 'DC', 'DE', 'FL', 'GA',
                    'HI', 'IA', 'ID', 'IL', 'IN', 'KS', 'KY', 'LA', 'MA', 'MD', 'ME',
                    'MI', 'MN', 'MO', 'MS', 'MT', 'NC', 'ND', 'NE', 'NH', 'NJ', 'NM',
                    'NV', 'NY', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD', 'TN', 'TX',
                    'UT', 'VA', 'VT', 'WA', 'WI', 'WV', 'WY'))) {
                $errors = true;
            }
            $zip_code = $args['zip'];
            if (!validateZipcode($zip_code)) {
                $errors = true;
                echo 'bad zip';
            }
            $email = strtolower($args['email']);
            $email = validateEmail($email);
            if (!$email) {
                $errors = true;
                echo 'bad email';
            }
            $phone1 = validateAndFilterPhoneNumber($args['phone']);
            if (!$phone1) {
                $errors = true;
                echo 'bad phone';
            }
            $phone1type = $args['phone_type'];
            if (!valueConstrainedTo($phone1type, array('cellphone', 'home', 'work'))) {
                $errors = true;
                echo 'bad phone type';
            }

            $emergency_contact_first_name = $args['emergency_contact_first_name'];
            $emergency_contact_last_name = $args['emergency_contact_last_name'];
            $emergency_contact_relation = $args['emergency_contact_relation'];
            $emergency_contact_phone = validateAndFilterPhoneNumber($args['emergency_contact_phone']);
            if (!$emergency_contact_phone) {
                $errors = true;
                echo 'bad e-contact phone';
            }
            $emergency_contact_phone_type = $args['emergency_contact_phone_type'];
            if (!valueConstrainedTo($emergency_contact_phone_type, array('cellphone', 'home', 'work'))) {
                $errors = true;
                echo 'bad phone type';
            }

            $tshirt_size = $args['tshirt_size'];
            $school_affiliation = $args['school_affiliation'];
            $photo_release = $args['photo_release'];
            if (!valueConstrainedTo($photo_release, array('Restricted', 'Not Restricted'))) {
                $errors = true;
                echo 'bad photo release type';
            }
            $photo_release_notes = $args['photo_release_notes'];

            $volunteer_or_participant = $args['volunteer_or_participant'];
            if ($volunteer_or_participant == 'v') {
                $type = 'volunteer';
            } else {
                $type = 'participant';
            }

            $archived = 0;

            $id = $args['username'];
            // May want to enforce password requirements at this step
            //$username = $args['username'];
            $password = password_hash($args['password'], PASSWORD_BCRYPT);

            $how_you_heard_of_stepva = $args['how_you_heard_of_stepva'];
            // Safely access preferred_feedback_method
            $preferred_feedback_method = $args['preferred_feedback_method'];

            $hobbies = $args['hobbies'];
            $professional_experience = $args['professional_experience'];
            $disability_accomodation_needs = $args['disability_accomodation_needs'];

            $training_complete = isset($args['training_complete']) ? (int)$args['training_complete'] : 0;
            $training_date = isset($args['training_date']) ? $args['training_date'] : null;

            if ($errors) {
                echo '<p>Your form submission contained unexpected input.</p>';
                die();
            }

            $status = "Active";
            
            
            $newperson = new Person(
                $id, // (id = username)
                $password,
                date("Y-m-d"),
                $first_name,
                $last_name,
                $birthday,
                $street_address,
                $city,
                $state,
                $zip_code,
                $phone1,
                $phone1type,
                $email,
                $emergency_contact_first_name,
                $emergency_contact_last_name,
                $emergency_contact_phone,
                $emergency_contact_phone_type,
                $emergency_contact_relation,
                $tshirt_size,
                $school_affiliation,
                $photo_release,
                $photo_release_notes,
                $type, // admin or volunteer or participant...
                $status,
                $archived,
                $how_you_heard_of_stepva,
                $preferred_feedback_method,
                $hobbies,
                $professional_experience,
                $disability_accomodation_needs,
                $training_complete,
                $training_date
            );

            $result = add_person($newperson);
            if (!$result) {
                echo '<p>That username is already in use.</p>';
            } else {
                /*if ($loggedIn) {
                    echo '<script>document.location = "index.php?registerSuccess";</script>';
                } else {*/
                    echo '<script>document.location = "login.php?registerSuccess";</script>';
                /*}*/
            }
        } else {
            require_once('registrationForm.php'); 
        }
    ?>
</body>
</html>
