<?php
    session_start();

    if (isset($_GET['search'])) { #Checks if user is searching
        $q = "%{$_GET['search']}%";
    }
?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <!-- <meta name="viewport" content="minimum-scale=1"/> -->
    <title>Pet Rocks</title>
    <script src='js/script.js'></script>
    <link rel='stylesheet' href='css/rules.css'>
    <link rel="icon" href="img/icon.png">
    <link rel='stylesheet'
        href='https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,200' />
</head>

<body>
    <?php include('../../code/xarifaNav.php')?>
    <?php include('code/header.php')?>
    <?php include('code/nav.php')?>
    <main>
        <h1 id='page_heading'>Products</h1>
        <section class='cards'>
            <?php
                require_once('code/connect.php');

                $db = connectToDB();

            
                if(isset($q)) { #If search, returns products maching search
                    $sql = "SELECT stockId, name, price, qtyInStock, imageURL FROM eStock WHERE name like :q OR price like :q";
                    $query = $db->prepare($sql);
                    $query->bindValue(':q',$q);
                } else { #Gets all products
                    $sql = "SELECT stockId, name, price, qtyInStock, imageURL FROM eStock";
                    $query = $db->prepare($sql);
                }
            
                $query->execute();
                $db = null;

                while ($row = $query->fetch()) { #Displays each product returned
                    $id = $row['stockId'];
                    $name = $row['name'];
                    $price = $row['price'];
                    $qtyInStock = $row['qtyInStock'];
                    $image = $row['imageURL'];
                    
                    $stockText = 'Out of Stock';
                    $stockColor = 'red';
                    $cartButton = '';
                    

                    if ($qtyInStock > 0) { #If in stock, shows add to cart button
                        $stockText = 'In Stock';
                        $stockColor = 'white';
            
                        $cartButton = "<form action='code/addToCart.php' method='post'>
                                            <input hidden type='text' id='id' name='id' value='$id'>
                                            <button type='submit'>Add to <i class='material-symbols-outlined'>shopping_cart</i></button>
                                        </form>";
                    }
                    
                    echo "<div class='card'>
                        <div class='card_image' style='background-image: url(\"img/Stock/$image\");'></div>
                        <div class='card_content'>
                            <h1>$name</h1>
                            <div>
                                <p>£$price</p>
                                <p style='color: $stockColor;'>$stockText</p>
                            </div>
                        </div>
                        <div class='card_buttons'>
                            <a href='product.php?id=$id'>View <i class='material-symbols-outlined'>visibility</i></a>
                            $cartButton
                        </div>
                        </div>";

                }
            
            
            ?>
        </section>

    </main>
    <?php include('code/footer.php')?>
</body>

</html>