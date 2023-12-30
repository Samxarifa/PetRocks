<?php
    session_start();
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
        <div class='div_backgroundImage' style="background-image: url('img/background.png');">
            <div class='div_foreground'>
                <div class='content-container'>
                    <h1>Pet Rocks</h1>
                    <h2>They are definitely not a scam...</h2>
                </div>
            </div>
        </div>
        <p class='quote'><q>Pet Rocks Rock, and together, we will change the future of Pet Rocks</q></p><p class='quote'>- Scott Bots (CEO)</p>
        <h1 id='page_heading'>Our Top Sellers</h1>
        <section class='cards indexCards'>
            <?php
                require_once('code/connect.php');

                $db = connectToDB();

                $sql = "SELECT eStock.stockId, name, price, imageURL
                        FROM eStock, eOrderStock
                        WHERE eStock.stockId = eOrderStock.stockId
                        AND qtyInStock > 0
                        GROUP BY eStock.stockId
                        ORDER BY SUM(quantity) DESC, name ASC
                        LIMIT 3;"; #Gets top 3 products sold that are still in stock
            
                $query = $db->prepare($sql);
            
                $query->execute();
                $db = null;

                while ($row = $query->fetch()) { #Displays products returned from sql
                    $id = $row['stockId'];
                    $name = $row['name'];
                    $price = $row['price'];
                    $image = $row['imageURL'];
                    
                    echo "<a href='product.php?id=$id' class='card-link'>
                                <div class='card-div'>
                                    <div class='card-OOS-text-div'><p>Out of Stock</p></div>
                                    <img src='img/Stock/$image' loading='lazy'>
                                    <div class='card-details'>
                                        <p class='card-title'>$name</p>
                                        <p>£$price</p>
                                    </div>
                                </div>
                            </a>";
                }
            
            
            ?>
            <a id='link_products' href="products.php"><i class='material-symbols-outlined'>arrow_forward</i></a>
        </section>
        <div class='div_backgroundImage' style="background-image: url('img/rocks.jpg');"><div class='div_foreground'></div></div>
        <h1 id='page_heading'>About Us</h1>
        <section class='content'>
            <div class='content-container'>
                <p>Scott Bots: CEO</p>
                <p>Alex Cooper: Chief Operating Officer</p>
                <p>Scott Tizzle: Chief Creative Director</p>
            </div>
        </section>
    </main>
    <?php include('code/footer.php')?>
</body>

</html>