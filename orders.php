<?php
    session_start();
    if(!isset($_SESSION['username'])) {
        header('location:login.php'); #Login Check
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
    <script src="js/script.js"></script>
    <link rel="stylesheet" href="css/rules.css">
    <link rel="icon" href="img/icon.png">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,200" />
</head>

<body>
    <?php include('../../code/xarifaNav.php')?>
    <?php include('code/header.php')?>
    <?php include('code/nav.php')?>
    <main>
        <h1 id='page_heading'>Orders</h1>
        <section id='order_items'>
        <?php 
            require_once('code/connect.php');
            $db = connectToDB();
            $sql = "SELECT orderId, orderDate, price
                    FROM eOrders
                    WHERE customerId = :custId
                    ORDER BY orderDate DESC, orderId DESC"; #Gets all orders made by customer
            $query = $db->prepare($sql);
            $query->bindParam(':custId',$_SESSION['id']);
            $query->execute();
            $db = null;

            $rowCount = $query->rowCount();
            
            while($row = $query->fetch()) { #Displays each order

                echo "<div class='item'>
                        <div class='item_content'>
                            <div class='item_text'>
                                <h1>Order Ref: {$row['orderId']}</h1>
                                <h1>Date Ordered: {$row['orderDate']}</h1>
                            </div>
                            <div class='item_options'>
                            <form action='orderConfirm.php' method='post'>
                                <input type='hidden' id='orderId' name='orderId' value='{$row['orderId']}'>
                                <button type='submit'>View</button>
                            </form>
                            </div>
                        </div>
                        <div class='item_price'>
                            <p>£{$row['price']}</p>
                        </div>
                    </div>";
            }
            
            
            if ($rowCount == 0) { #Shows message if no orders have been made
                echo "<h1>You Haven't Ordered Yet...</h1>";
            }
        ?>
        </section>
    </main>
    <?php include('code/footer.php')?>
</body>

</html>