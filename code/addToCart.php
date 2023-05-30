<?php
    session_start();
    if(!isset($_SESSION['username'])) {
        header('location: ../login.php');   #Login Check 
    }
    
    $id = $_POST['id'];
    $quantity = 1;
    
    require_once('connect.php');
    $db = connectToDB();

    $sql = "SELECT qtyInStock FROM eStock WHERE stockId = :stockId"; 
    $query = $db->prepare($sql);
    $query->bindParam(':stockId',$id);
    $query->execute();
    if ($query->rowCount() == 0) { #If Stock doesn't exist then don't add anything
        $db = null;
        header('location: ../cart.php');
    } else {
        $row = $query->fetch();
        if ($row['qtyInStock'] == 0) { #If product out of stock then don't add it
            $db = null;
            header('location: ../cart.php');
        }
    }


    $sql = "SELECT quantity, qtyInStock FROM eCart, eStock WHERE eStock.stockId = eCart.stockId AND customerId = :custId AND eCart.stockId = :stockId";
    $query = $db->prepare($sql);
    $query->bindParam(':custId',$_SESSION['id']);
    $query->bindParam(':stockId',$id);
    $query->execute();

    if ($query->rowCount()==0) { #If item isn't in cart then add it
        $sql = "INSERT INTO eCart (customerId,stockId,quantity) VALUES (:custId,:stockId,:quantity)";
    } else { #If in cart already then update quantity
        $row = $query->fetch();
        if ($row['quantity'] > 4 || $row['quantity'] == $row['qtyInStock']) { #If item is in cart, is less than quantity in stock and is less than 5 then update cart
            $db = null;
            header('location: ../cart.php');
        }
        $quantity += $row['quantity']; #Add new quantity to current (Right now only adds 1)
        $sql = "UPDATE eCart SET quantity = :quantity WHERE customerId = :custId AND stockId = :stockId";
    }
    
    $query = $db->prepare($sql);
    $query->bindParam(':custId',$_SESSION['id']);
    $query->bindParam(':stockId',$id);
    $query->bindParam(':quantity',$quantity);
    $query->execute();
    $db=null;
    header('location: ../cart.php');
?>