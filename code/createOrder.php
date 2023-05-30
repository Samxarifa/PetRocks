<?php
    session_start();
    if (!isset($_SESSION['username'])) {
        header('location: ../login.php'); #Login Check
    }


    require_once('connect.php');

    $db = connectToDB();

    $sql = "SELECT SUM(price*quantity) AS 'TOTAL', memberType  #Gets total cart price and customer member type
            FROM eStock, eCart, eCustomer
            WHERE eCart.stockId = eStock.stockId
            AND eCart.customerId = eCustomer.customerId
            AND eCustomer.customerId = :custId";

    $query = $db->prepare($sql);
    $query->bindParam(':custId',$_SESSION['id']);
    $query->execute();
    $data = $query->fetch();

    $price = $data['TOTAL'];
    $memberType = $data['memberType'];

    if ($memberType == 1) {     #Applies discount depending on memeber type
        $price = $price - $price*0.15;
    } else if ($memberType == 2) {
        $price = $price - $price*0.25;
    }

    $sql = "BEGIN;
            INSERT INTO eOrders (customerId, orderDate, price)
            VALUES (:custId, curdate(), :price);
            
            INSERT INTO eOrderStock (orderId, stockId, quantity)
            SELECT LAST_INSERT_ID(), stockId, quantity
            FROM eCart
            WHERE customerId = :custId;
            COMMIT;"; #Creates Order and Inserts stock in order

    $query = $db->prepare($sql);
    $query->bindParam(':custId',$_SESSION['id']);
    $query->bindParam(':price',$price);
    $query->execute();

    $sql = "SELECT stockId, quantity FROM eCart WHERE customerId = :custId"; #Gets quantities for items in cart
    $query = $db->prepare($sql);
    $query->bindParam(':custId',$_SESSION['id']);
    $query->execute();
    
    while($row = $query->fetch()) { # Updates Quantity in stock by removing the stock in the new order
        $sql = "UPDATE eStock
                SET qtyInStock = qtyInStock - :qty
                WHERE stockId = :stockId";
        $query1 = $db->prepare($sql);
        $query1->bindParam(':qty',$row['quantity']);
        $query1->bindParam(':stockId',$row['stockId']);
        $query1->execute();
    }
    
    $sql = "DELETE FROM eCart WHERE customerId = :custId"; #Deletes cart
    $query = $db->prepare($sql);
    $query->bindParam(':custId',$_SESSION['id']);
    $query->execute();  
    
    $db = null;

    header('location: ../orderConfirm.php');


?>