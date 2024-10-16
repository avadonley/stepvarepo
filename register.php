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
                'email', 'phone', 'phone_type', 'econtact_first_name',
                'econtact_last_name', 'econtact_phone',
                'econtact_relation', 'tshirt_size',
                'school_affiliation', 'username', 'password',
                'volunteer_or_participant'
            );

            $errors = false;
            if (!wereRequiredFieldsSubmitted($args, $required)) {
                $errors = true;
            }
            $first = $args['first_name'];
            $last = $args['last_name'];
            $dateOfBirth = validateDate($args['birthdate']);
            if (!$dateOfBirth) {
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
            $zipcode = $args['zip'];
            if (!validateZipcode($zipcode)) {
                $errors = true;
                echo 'bad zip';
            }
            $email = strtolower($args['email']);
            $email = validateEmail($email);
            if (!$email) {
                $errors = true;
                echo 'bad email';
            }
            $phone = validateAndFilterPhoneNumber($args['phone']);
            if (!$phone) {
                $errors = true;
                echo 'bad phone';
            }
            $phoneType = $args['phone_type'];
            if (!valueConstrainedTo($phoneType, array('cellphone', 'home', 'work'))) {
                $errors = true;
                echo 'bad phone type';
            }

            $econtactFirstName = $args['econtact_first_name'];
            $econtactLastName = $args['econtact_last_name'];
            $econtactPhone = validateAndFilterPhoneNumber($args['econtact_phone']);
            if (!$econtactPhone) {
                $errors = true;
                echo 'bad e-contact phone';
            }
            $econtactRelation = $args['econtact_relation'];

            $tshirtSize = $args['tshirt_size'];
            $schoolAffiliation = $args['school_affiliation'];
            $volunteerOrParticipant = $args['volunteer_or_participant'];

            $username = $args['username'];
            // May want to enforce password requirements at this step
            //$username = $args['username'];
            $password = password_hash($args['password'], PASSWORD_BCRYPT);

            if ($errors) {
                echo '<p>Your form submission contained unexpected input.</p>';
                die();
            }
            
            $newperson = new Person(
                $username, // id = username
                $first,
                $last,
                $dateOfBirth,
                $email,
                $password,
                $username
            );

            $result = add_person($newperson);
            if (!$result) {
                echo '<p>That username is already in use.</p>';
            } else {
                if ($loggedIn) {
                    echo '<script>document.location = "index.php?registerSuccess";</script>';
                } else {
                    echo '<script>document.location = "login.php?registerSuccess";</script>';
                }
            }
        } else {
            require_once('registrationForm.php'); 
        }
    ?>
</body>
</html>
