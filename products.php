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
    <script src="js/hammer.js"></script>
    <script src="js/script.js"></script>
    <link rel='stylesheet' href='css/rules.css'>
    <link rel="icon" href="img/icon.png">
    <link rel='stylesheet'
        href='https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,200' />
</head>

<body>
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

                    if ($qtyInStock == 0) {
                        $stockClass = "card-OOS";
                    } else {
                        $stockClass = "";
                    }

                    echo "<a href='product.php?id=$id' class='card-link'>
                                <div class='card-div $stockClass'>
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
        </section>

    </main>
    <?php include('code/footer.php')?>
</body>

</html>