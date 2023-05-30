<?php
    session_start();
    if(!isset($_SESSION['username'])) {
        header('location: ../login.php'); #Login Check
    }
    $stockId = $_POST['stockId']; #Gets id of stock to update
    $newQuantity = $_POST['sel_quantity']; #Gets new quantity
    if ($newQuantity < 1 || $newQuantity > 5) { #Only updates quantity if between 0 and 6
        header('location:../cart.php'); 
    }  else {
        require_once('connect.php');
        $db = connectToDB();
        $sql = "UPDATE eCart SET quantity = :quantity WHERE customerId = :custId AND stockId = :stockId"; #Updates quantity to new quantity
        $query = $db->prepare($sql);
        $query->bindParam(':custId',$_SESSION['id']);
        $query->bindParam(':stockId',$stockId);
        $query->bindParam(':quantity',$newQuantity);
        $query->execute();
        $db = null;
        header('location:../cart.php');
    }

?>
