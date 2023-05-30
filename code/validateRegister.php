<?php
    session_start();
    if (isset($_SESSION['username'])) { #Checks if user is logged in already
        header('location: ../index.php');
    }

    $incompleteForm = false;
    foreach ($_POST as $i) { #Checks if Any field is empty
        if ($i == '') {
            $incompleteForm = true;
        }
    }

    $forename = $_POST['forename']; 
    $surname = $_POST['surname']; 
    $street = $_POST['street']; 
    $town = $_POST['town']; 
    $postcode = $_POST['postcode']; 
    $email = $_POST['email']; 
    $username = $_POST['username']; 
    $memberType = $_POST['memberType']; 
    $password = $_POST['password'];
    $password2 = $_POST['password2'];

    if ($incompleteForm) { #If empty field
        $_SESSION['regFail'] = 'Please fill out all Fields';
        header('location: ../register.php');
        
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { #If email is invalid format
        $_SESSION['regFail'] = 'Invalid Email Format';
        header('location: ../register.php');

    } else if (strlen($password) < 8 || strlen($password) > 20) { #if password is not between 8 and 20 long
        $_SESSION['regFail'] = 'Please enter a password with a length between 8 and 20';
        header('location: ../register.php');
        
    } else if ($password != $password2) { #If passwords don't match
        $_SESSION['regFail'] = 'Passwords Don\'t Match';
        header('location: ../register.php');
    
    } else { #If no validation issues
  
        $passwordHashed = password_hash($password,PASSWORD_DEFAULT);

        require_once('connect.php');

        $db = connectToDB();

        $sql = "SELECT email, username FROM eCustomer"; #Gets all emails and usernames from db
        $query = $db->prepare($sql);
        $query->execute();

        while ($row=$query->fetch()) {
            if ($email == $row['email']) { #Checks if email is taken
                $_SESSION['regFail'] = 'Email is Taken';
                $db = null;
                header('location: ../register.php');
            } else if ($username == $row['username']){ #Checks if username is taken
                $_SESSION['regFail'] = 'Username is Taken';
                $db = null;
                header('location: ../register.php');
            }
        }
        
        if ($db != null) { #If still no validation issues
            $sql = "INSERT INTO eCustomer (forename,surname,street,town,postcode,memberType,email,username,password)
                    VALUES (:fore,:sur,:street,:town,:post,:memType,:email,:user,:pwd)"; #Insert new user into db
        
            $query = $db->prepare($sql);
        
            $query->bindParam(':fore',$forename);
            $query->bindParam(':sur',$surname);
            $query->bindParam(':street',$street);
            $query->bindParam(':town',$town);
            $query->bindParam(':post',$postcode);
            $query->bindParam(':memType',$memberType);
            $query->bindParam(':email',$email);
            $query->bindParam(':user',$username);
            $query->bindParam(':pwd',$passwordHashed);
        
            if ($query->execute()) { #Go to login page
                $db = null;
                header('location: ../login.php');
            } else {
                echo 'Error: Something Went Wrong, Please Contact an Admin';
            }
        }
    }
    



?>