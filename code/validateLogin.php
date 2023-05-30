<?php
    function fail() { #Returns to login page with fail warning
        $_SESSION['loginFail'] = true;
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

    $sql = "SELECT customerId, username, password FROM eCustomer WHERE BINARY username = :username"; #Gets username and hashed password from db

    $query = $db->prepare($sql);

    $query->bindParam(':username',$username);

    $query->execute();
    $db = null;

    if ($query->rowCount() == 1) { #Checks if acount exists
        $row = $query->fetch();
        
        if (password_verify($password,$row['password'])) { #Checks if password is valid against hashed password
            $_SESSION['username'] = $row['username']; #Sets session var for login check
            $_SESSION['id'] = $row['customerId']; #Sets session var for future db entries
            header('location: ../index.php');
        } else {
            fail(); #If Password Fail
        } 
    } else {
        fail(); #If Username Fail
    }
?>