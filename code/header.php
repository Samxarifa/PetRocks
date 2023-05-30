<?php
    if (isset($_SESSION['username'])) { #Checks for login
        $loggedIn = true;
    } else {
        $loggedIn = false;
    }
    
    $searchVal = '';
    if (isset($q)) {
        $searchVal = str_replace(str_split('!"%^&*()_+-={}[]:;@\'~#<>/?,|\\'),'',$q); #Strips special characters from Search term
    }
?>

<header>
        <a href="index.php"><img src="img/logo.png" alt=""></a>
        <div id="div_search">
            <form action="products.php" method="get">
                <input type="text" id="search" name='search' placeholder="Search" value="<?php echo $searchVal?>">
                <button type="submit"><i class="material-symbols-outlined">search</i></button>
            </form>
        </div>
        
        <?php
            if ($loggedIn) { #Shows account menu and cart if user is logged in

                require_once('code/connect.php');
                $db = connectToDB();
                $sql = "SELECT SUM(quantity) AS 'count' FROM eCart GROUP BY customerId HAVING customerId = :custId";
                $query = $db->prepare($sql);
                $query->bindParam(':custId',$_SESSION['id']);
                $query->execute();
                $db=null;
                
                if($query->rowCount() == 1) {
                    $row = $query->fetch();
                    $cartCount = $row['count'];
                } else {
                    $cartCount = 0;
                }
                

                echo "<div id='div_dropdown'>
                    <ul>
                    <li><a href='orders.php'>Orders<i class='material-symbols-outlined'>receipt_long</i></a></li>
                    <li><a href='code/logout.php'>Logout<i class='material-symbols-outlined'>logout</i></a></li>
                    </ul>
                    </div>
                    
                    <div id='div_account'>
                    <ul>
                    <li><a href='#' onclick='accountDropDown()'>Account<i class='material-symbols-outlined'>expand_more</i></a></li>
                    <li><a href='cart.php'>Cart<i class='material-symbols-outlined head_cart'>shopping_cart</i>($cartCount)</a></li>
                    </ul>
                    </div>";
            } else { # Shows login and register if user is not logged in
                echo "<div id='div_account'>
                    <ul>
                    <li><a href='login.php'>Login<i class='material-symbols-outlined'>login</i></a></li>
                    <li><a href='register.php'>Register<i class='material-symbols-outlined'>person_add</i></a></li>
                    </ul>
                    </div>";
            }
        
        ?>
    </header>