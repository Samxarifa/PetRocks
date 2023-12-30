<?php
    function fail($failType) { #Returns to login page with fail warning
        if ($failType == 0) {
            $_SESSION['loginFail'] = "Incorrect Username or Password";
        } else if ($failType == 1) {
            $_SESSION['loginFail'] = "Account is Currently Suspended";
        }
        header('location:../login.php');
    }
    
    session_start();
    if (isset($_SESSION['username'])) {
        header('location: ../index.php'); #Checks if user is not logged in already
    }

    $username = $_POST['username']; 
    $password = $_POST['password']; 

    require_once('connect.php');

    $db = connectToDB();

    $sql = "SELECT customerId, username, password, isAdmin, isSuspended FROM eCustomer WHERE BINARY username = :username"; #Gets username and hashed password from db

    $query = $db->prepare($sql);

    $query->bindParam(':username',$username);

    $query->execute();
    $db = null;

    if ($query->rowCount() == 1) { #Checks if acount exists
        $row = $query->fetch();
        
        if (password_verify($password,$row['password'])) { #Checks if password is valid against hashed password
            if ($row['isSuspended'] == 1) {
                fail(1);
            } else {
                $_SESSION['username'] = $row['username']; #Sets session var for login check
                $_SESSION['id'] = $row['customerId']; #Sets session var for future db entries
                $_SESSION['isAdmin'] = $row['isAdmin'];
                header('location: ../index.php');
            }
        } else {
            fail(0); #If Password Fail
        } 
    } else {
        fail(0); #If Username Fail
    }
?>