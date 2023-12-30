<?php
    session_start();

    $id = $_GET['id']; #Gets id for item to show

    require_once('code/connect.php');

    $db = connectToDB();
    $sql = "SELECT stockId, name, description, price, qtyInStock, imageURL
            FROM eStock
            WHERE stockId = :id"; #Gets details of item
    
    $query = $db->prepare($sql);
    $query->bindParam(':id',$id);
    $query->execute();
    $db = null;
    $row = $query->fetch();
    $name = $row['name'];
    $description = $row['description'];
    $price = $row['price'];
    $qtyInStock = $row['qtyInStock'];
    $imageURL = $row['imageURL'];
    $stockColor = 'red';
    $cartButton = '';

    if ($qtyInStock > 0) { #Shows add to cart if in stock
        $stockColor = 'green';
        $cartButton = "<form action='code/addToCart.php' method='post'>
                            <input hidden type='text' id='id' name='id' value=$id>
                            <button type='submit'>Add to <i class='material-symbols-outlined'>shopping_cart</i></button>
                        </form>";
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
    <!-- <link rel="stylesheet" href="css/product.css"> -->
    <link rel="icon" href="img/icon.png">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,200" />
</head>

<body>
    <?php include('code/header.php')?>
    <?php include('code/nav.php')?>
    <main class='main-product'>
        <?php
        echo "<div id='product_image' style='background-image: url(\"img/Stock/$imageURL\")';></div>
        <div id='product_content'>
            <h1>$name</h1>
            <h2>£$price</h2>
            <h3 style='color: $stockColor;'>Stock: $qtyInStock Left</h3>
            $cartButton
            <h4>Description</h4>
            <p>$description</p>
        </div>";
        ?>
    </main>
    <?php include('code/footer.php')?>
</body>

</html>

