<?php
    session_start();

    if (!isset($_SESSION['username'])) {
        header('location: login.php'); #Login Check
    }
    require_once('code/connect.php');
    
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
    <?php include('code/header.php')?>
    <?php include('code/nav.php')?>
    <main>
        <h1 id='page_heading'>Checkout</h1>
        <section id='checkout'>
            <div id='check_content'>
                <div id='check_shipping'>
                    <ul>
                        <li><i class="material-symbols-outlined">location_on</i>Deliver To:</li>
                        <?php
                            $db = connectToDB();
                            $sql = "SELECT street, town, postcode, memberType
                                    FROM eCustomer
                                    WHERE customerId = :custId"; #Gets address details
                            $custQuery = $db->prepare($sql);
                            $custQuery->bindParam(':custId',$_SESSION['id']);
                            $custQuery->execute();

                            $custData = $custQuery->fetch();
                            echo "<li>{$custData['street']}</li>";
                            echo "<li>{$custData['town']}</li>";
                            echo "<li>{$custData['postcode']}</li>";
                        
                        ?>
                    </ul>
                </div>
                <div id='check_items'>
                    <?php
                        $sql = "SELECT eStock.stockId AS 'stockId', name, quantity, price, (quantity*price) AS 'totalPrice', imageURL, qtyInStock
                                FROM eStock, eCart
                                WHERE eStock.stockId = eCart.stockId
                                AND customerId = :custId"; #Gets items from cart
                        $cartQuery = $db->prepare($sql);
                        $cartQuery->bindParam(':custId',$_SESSION['id']);
                        $cartQuery->execute();
                        $db = null;
                    
                        $total = 0;
                        $rowCount = $cartQuery->rowCount();
                        if ($rowCount == 0) { #Returns to cart if no items in cart
                            header('location: cart.php');
                        } else {
                            while ($row = $cartQuery->fetch()) { #Displays each item
                                echo "<div class='check_item'>
                                            <ul>
                                                <li>{$row['name']}</li>
                                                <li>Unit Price: £{$row['price']}</li>
                                                <li>Quantity: {$row['quantity']}</li>
                                                <li>Total Price: £{$row['totalPrice']}</li>
                                            </ul>
                                        </div>";
                                $total += $row['totalPrice']; #Works out total price of cart
                            }

                            

                        }
                    ?>
                </div>
            </div>
            <div id='check_price'>
                <div id='price_before'>
                    <p>Price:</p>
                    <p>£<?php echo $total?></p>
                </div>
                <div id='discount_type'>
                    <p>Member:</p>
                    <?php
                        $discount = 0;
                        
                        if ($custData['memberType'] == 1) { #Works out discount
                            echo "<p id='membershipText' style='background:#C0C0C0;'>SILVER</p>";
                            $discount = $total*0.15;
                        } else if ($custData['memberType'] == 2) {
                            echo "<p id='membershipText' style='background:gold;'>GOLD</p>";
                            $discount = $total*0.25;
                        } else {
                            echo "<p id='membershipText' style='background:#CD7F32;'>BRONZE</p>";
                        }
                    
                    ?>
                </div>
                <div id='discount_price'>
                    <p>Discount:</p>
                    <p>- £<?php echo $discount;?></p>
                </div>
                <div id='total_price'>
                    <p>Total Price:</p>
                    <p>£<?php
                        $totalPrice = $total - $discount; #Works out final price
                        echo $totalPrice;
                        ?></p>
                </div>
                <form action="code/createOrder.php" method="post">
                    <button type="submit">Place Order</button>
                </form>
            </div>
        </section>

    </main>
    <?php include('code/footer.php')?>
</body>

</html>