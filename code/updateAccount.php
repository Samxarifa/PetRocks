<?php
    session_start();
    if(!isset($_SESSION['username'])) {
        header('location: ../login.php');   #Login Check 
    }

    require_once('connect.php');
    $db = connectToDB();
    // Gets current details for user
    $sql = "SELECT forename, surname, street, town, postcode, email, username, memberType, password
            FROM eCustomer
            WHERE customerId = :custId";
    $query = $db->prepare($sql);
    $query->bindParam(':custId',$_SESSION['id']);
    $query->execute();
    $row = $query->fetch();

    // If no new data supplied, use old data
    $_POST['input_username'] == null? $newUsername = $row['username']: $newUsername = $_POST['input_username'];
    $_POST['input_email'] == null? $newEmail = $row['email']: $newEmail = $_POST['input_email'];
    $_POST['input_forename'] == null? $newForename = $row['forename']: $newForename = $_POST['input_forename'];
    $_POST['input_surname'] == null? $newSurname = $row['surname']: $newSurname = $_POST['input_surname'];
    $_POST['input_street'] == null? $newStreet = $row['street']: $newStreet = $_POST['input_street'];
    $_POST['input_town'] == null? $newTown = $row['town']: $newTown = $_POST['input_town'];
    $_POST['input_postcode'] == null? $newPostcode = $row['postcode']: $newPostcode = $_POST['input_postcode'];
    $newTier = $_POST['select_tier'];

    if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) { #If email is invalid format
        $_SESSION['updateFeedback'] = 'Error: Invalid Email Format';
        $db = null;
        header('location: ../settings.php');
    }
    
    $oldPassword = $_POST['input_old_password'];
    $password = $_POST['input_password1'];
    $password2 = $_POST['input_password2'];

    // Validates New Password if Present
    if ($oldPassword == null || $password == null || $password2 == null) {
        $hashedPassword = $row['password'];
    } else if (!password_verify($oldPassword,$row['password'])) {
        $_SESSION['updateFeedback'] = 'Error: Old Password Is Invalid';
        $db = null;
        header('location: ../settings.php');
    } else if ($password != $password2) { #If passwords don't match
        $_SESSION['updateFeedback'] = 'Error: New Passwords Don\'t Match';
        $db = null;
        header('location: ../settings.php');
    } else if (strlen($password) < 8 || strlen($password) > 20) { #if password is not between 8 and 20 long
        $_SESSION['updateFeedback'] = 'Error: Please enter a new password with a length between 8 and 20';
        $db = null;
        header('location: ../settings.php');
    } else {
        $hashedPassword = password_hash($password,PASSWORD_DEFAULT); //Hashes Password
    }

    // Validates new Email and Username against pre existing in db (Make sure they are unique)
    if (($_POST['input_email'] != null || $_POST['input_username'] != null) && $db != null) {
        $sql2 = "SELECT email, username FROM eCustomer"; #Gets all emails and usernames from db
        $query2 = $db->prepare($sql2);
        $query2->execute();

        while ($row2=$query2->fetch()) {
            if ($newEmail == $row2['email'] && $_POST['input_email'] != null) { #Checks if new email is taken
                $_SESSION['updateFeedback'] = 'New Email is Taken';
                $db = null;
                header('location: ../settings.php');
            } else if ($newUsername == $row2['username'] && $_POST['input_username'] != null){ #Checks if new username is taken
                $_SESSION['updateFeedback'] = 'New Username is Taken';
                $db = null;
                header('location: ../settings.php');
            }
        }
    }

    // Updates User if Validation Passed (in some cases updates user to the same detail as before if no new data present for certain detail)
    if ($db != null) {
        $sql3 = "UPDATE eCustomer
                SET forename = :forename, surname = :surname, street = :street, town = :town, postcode = :postcode, email = :email, username = :username, memberType = :tier, password = :pass
                WHERE customerId = :custId";
    
        $query3 = $db->prepare($sql3);
        
        $query3->bindParam(':forename',$newForename);
        $query3->bindParam(':surname',$newSurname);
        $query3->bindParam(':street',$newStreet);
        $query3->bindParam(':town',$newTown);
        $query3->bindParam(':postcode',$newPostcode);
        $query3->bindParam(':email',$newEmail);
        $query3->bindParam(':username',$newUsername);
        $query3->bindParam(':tier',$newTier);
        $query3->bindParam(':pass',$hashedPassword);
        $query3->bindParam(':custId',$_SESSION['id']);
        
        $query3->execute();
        $db = null;
    
        $_SESSION['username'] = $newUsername;
        $_SESSION['updateFeedback'] = "Success: Account Updated Successfully";
        header('location: ../settings.php');
    }
    
?>

