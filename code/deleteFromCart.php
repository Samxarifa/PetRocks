<?php
    session_start();
    if(!isset($_SESSION['username'])) { 
        header('location: ../login.php'); #Login Check
    }

    $stockId = $_POST['stockId']; #Gets id of stock to be deleted from cart

    require_once('connect.php');
    $db = connectToDB();
    $sql = "DELETE FROM eCart WHERE customerId = :custId AND stockId = :stockId"; #Deletes stock from customers cart
    $query = $db->prepare($sql);
    $query->bindParam(':custId',$_SESSION['id']);
    $query->bindParam('stockId',$stockId);
    $query->execute();
    $db = null;
    header('location:../cart.php');

?>