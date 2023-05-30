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
    <?php include('code/header.php')?>
    <?php include('code/nav.php')?>
    <main>
        <h1 id='page_heading'>Cart</h1>
        <section id='cart_items'>
        <?php 
            require_once('code/connect.php');
            $db = connectToDB();
            $sql = "SELECT eStock.stockId AS 'stockId', name, quantity, price, (quantity*price) AS 'totalPrice', imageURL, qtyInStock
                    FROM eStock, eCart
                    WHERE eStock.stockId = eCart.stockId
                    AND customerId = :custId"; #Gets all items in cart
            $query = $db->prepare($sql);
            $query->bindParam(':custId',$_SESSION['id']);
            $query->execute();
            $db = null;

            $total = 0;
            $rowCount = $query->rowCount();
            
            while($row = $query->fetch()) { #Displays each item in cart
                $quantitySelector = 5;
                if ($quantitySelector > $row['qtyInStock']) { #Limits new quantity selection to 5 or less
                    $quantitySelector = $row['qtyInStock'];
                }

                echo "<div class='item'>
                        <div class='item_image' style='background-image: url(\"img/Stock/{$row['imageURL']}\");'></div>
                        <div class='item_content'>
                            <div class='item_text'>
                                <h1>{$row['name']}</h1>
                                <p>In Stock</p>
                                <p>Unit Price: £{$row['price']}</p>
                                <p>Quantity: {$row['quantity']}</p>
                            </div>
                            <div class='item_options'>
                            <form action='code/updateCart.php' method='post'>
                            <select name='sel_quantity' id='sel_quantity' onchange='this.form.submit()'>";
                            for ($i=1;$i<$quantitySelector+1;$i++) { #Sets quantity to selected value on dropdown
                                if ($i == $row['quantity']) {
                                    echo "<option selected value='$i'>$i</option>";
                                } else {
                                    echo "<option value='$i'>$i</option>";
                                }
                            }
                echo        "</select>
                                <input type='hidden' id='stockId' name='stockId' value='{$row['stockId']}'>
                                </form>
                            <a href='product.php?id={$row['stockId']}'>View</a>
                            <form action='code/deleteFromCart.php' method='post'>
                                <input type='hidden' id='stockId' name='stockId' value='{$row['stockId']}'>
                                <button>Delete</button>
                            </form>
                            </div>
                        </div>
                        <div class='item_price'>
                            <p>£{$row['totalPrice']}</p>
                        </div>
                    </div>";
                $total += $row['totalPrice']; #Calculates total cart price
            }
            
            if ($rowCount > 0) { #Shows total price is the cart is not empty
                echo "<div class='item total'>
                    <div class='item_content'>
                        <div class='item_text'>
                            <h1>Total:</h1>
                        </div>
                    </div>
                    <div class='item_price'>
                        <p>£$total</p>
                        <a href='checkout.php'>Checkout</a>
                    </div>
                </div>";

            } else { #Shows "cart is empty" if it is
                echo "<h1>Cart is Empty...</h1>";
            }
        ?>
        </section>
    </main>
    <?php include('code/footer.php')?>
</body>

</html>