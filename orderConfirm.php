<?php
    session_start();
    if (!isset($_SESSION['username'])) { #Login Check
        header('location: login.php');
    }
    require_once('code/connect.php');

    if (isset($_POST['orderId'])) { #Checks if being posted an orderId (Determines whether to show last order or specific order)
        $orderId = $_POST['orderId'];
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta name="viewport" content="minimum-scale=1"/> -->
    <title>Pet Rocks</title>
    <script src="js/hammer.js"></script>
    <script src="js/script.js"></script>
    <link rel="stylesheet" href="css/rules.css">
    <link rel="icon" href="img/icon.png">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,200" />
</head>

<body>
    <?php include('code/header.php')?>
    <?php include('code/nav.php')?>
    <main>
        <h1 id='page_heading'>Order Confirmed</h1>
        <section id='order_items'>
        <?php 
            require_once('code/connect.php');
            $db = connectToDB();
            
            if (isset($orderId)) { #If specific order
                $sql = "SELECT name, quantity, (eStock.price*quantity) AS 'price'
                        FROM eStock, eOrderStock, eOrders
                        WHERE eStock.stockId = eOrderStock.stockId
                        AND eOrderStock.orderId = eOrders.orderId
                        AND eOrders.orderId = :orderId
                        AND eOrders.customerId = :custId"; #Gets item details for order
                $query = $db->prepare($sql);
                $query->bindParam(':orderId',$orderId);
            } else { #If last order
                $sql = "SELECT name, quantity, (eStock.price*quantity) AS 'price'
                        FROM eStock, eOrderStock, eOrders
                        WHERE eStock.stockId = eOrderStock.stockId
                        AND eOrderStock.orderId = eOrders.orderId
                        AND eOrders.orderId = (SELECT MAX(orderId)
                                                FROM eOrders
                                                WHERE customerId = :custId
                                                GROUP BY customerId)"; #Gets item details for last order
                $query = $db->prepare($sql);
            }
            
            $query->bindParam(':custId',$_SESSION['id']);
            $query->execute();
            

            $rowCount = $query->rowCount(); #Returns row count of data
            
            while($row = $query->fetch()) { #Displays each item returned

                echo "<div class='item'>
                        <div class='item_content'>
                            <div class='item_text'>
                                <p>Item: {$row['name']}</p>
                                <p>Quantity: {$row['quantity']}</p>
                            </div>
                        </div>
                        <div class='item_price'>
                            <p>£{$row['price']}</p>
                        </div>
                    </div>";
            }
            
            if ($rowCount > 0) { #If data returned
                if (isset($orderId)) { #If specific order
                    $sql = "SELECT price
                            FROM eOrders
                            WHERE orderId = :orderId
                            AND customerId = :custId"; #Gets total price from order
                    $query = $db->prepare($sql);
                    $query->bindParam(':orderId',$orderId);
                } else { #If last order
                    $sql = "SELECT price
                            FROM eOrders
                            WHERE orderId = (SELECT MAX(orderId)
                                            FROM eOrders
                                            WHERE customerId = :custId
                                            GROUP BY customerId)"; #Gets total price from last order
                    $query = $db->prepare($sql);
                }

                $query->bindParam(':custId',$_SESSION['id']);
                $query->execute();
                $db = null;
                $row = $query->fetch();
                $price = $row['price'];

                echo "<div class='item total'>
                    <div class='item_content'>
                        <div class='item_text'>
                            <p>Total:</p>
                        </div>
                    </div>
                    <div class='item_price'>
                        <p>£$price</p>
                        <a id='printButton' onclick='window.print();return false;'>Print</a>
                    </div>
                </div>"; #Shows price

            } else {
                header('location: cart.php');
            }
        ?>
        </section>
    </main>
    <?php include('code/footer.php')?>
</body>

</html>