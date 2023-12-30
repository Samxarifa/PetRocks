<?php
    session_start();
    if(!isset($_SESSION['username'])) {
        header('location: ../login.php');   #Login Check 
    }
    
    require_once('connect.php');
    $db = connectToDB();

    // Checks password supplied
    $sql = "SELECT password
            FROM eCustomer
            WHERE customerId = :custId";
    $query = $db->prepare($sql);
    $query->bindParam(':custId',$_SESSION['id']);
    $query->execute();
    $row = $query->fetch();

    $password = $_POST['input_delete_password'];

    // If password wrong, do nothing
    if (!password_verify($password,$row['password'])) {
        $db = null;
        $_SESSION['updateFeedback'] = 'Error: Password is Incorrect';
        header('location: ../settings.php');
    } else {
        // If password right, call stored procedure to delete account from all relevent tables
        $sql2 = "CALL DELETE_E_ACCOUNT(:custId);";
        $query2 = $db->prepare($sql2);
        $query2->bindParam(':custId',$_SESSION['id']);
        $query2->execute();
        $db = null;

        header('location: logout.php');
    }

    

?>