<?php
    session_start();
    if (isset($_SESSION['username'])) {
        header('location: ../index.php');
    } else if (isset($_SESSION['regFail'])) {
        $failText = $_SESSION['regFail'];
        $_SESSION['regFail'] = null;
    } else {
        $failText = '';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta name="viewport" content="minimum-scale=1"/> -->
    <link rel="stylesheet" href="css/accountForms.css">
    <link rel="icon" href="img/icon.png">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,200" />
    <title>Pet Rocks</title>
</head>
<body>
    <?php include('../../code/xarifaNav.php')?>
    <main>
        <a id='arrow' href="index.php"><i class="material-symbols-outlined">arrow_back</i></a>
        <div id='accountForm'>
        <h1>Register</h1>
        <p id='failText'><?php echo $failText?></p>
        <form action="code/validateRegister.php" method="post">
            <h2>Name</h2>        
            <div class='div_section'>
                <div class='div_input'>
                <p>Forename</p>    
                <input type="text" name="forename" id="forename"><br>
                </div><div class='div_input'>
                <p>Surname</p>
                <input type="text" name="surname" id="surname"><br>
                </div>
            </div>
            <h2>Address</h2>
            <div class='div_section'>        
                <div class='div_input'>    
                <p>Street</p>
                <input type="text" name="street" id="street"><br>
                </div><div class='div_input'>
                <p>Town</p>
                <input type="text" name="town" id="town"><br>
                </div><div class='div_input'>
                <p>Postcode</p>
                <input type="text" name="postcode" id="postcode"><br>
                </div>
            </div>
            <h2>Account Details</h2>
            <div class='div_section'>
                <div class='div_input'>    
                <p>Email</p>
                <input type="text" name="email" id="email"><br>
                </div><div class='div_input'>
                <p>Username</p>
                <input type="text" name="username" id="username"><br>
                </div><div class='div_input'>
                <p>Member Type</p>
                <select name='memberType' id='memberType'>
                    <option style='background:#CD7F32;' selected value='0'>BRONZE</option>
                    <option style='background:#C0C0C0;' value='1'>SILVER</option>
                    <option style='background:gold;' value='2'>GOLD</option>
                </select>    
                <br>
                </div>
            </div>
            <h2>Password</h2>
            <div class='div_section'>
                <div class='div_input'>    
                <p>Password</p>
                <input type="password" name="password" id="password"><br>
                </div><div class='div_input'>
                <p>Retype Password</p>
                <input type="password" name="password2" id="password2"><br>
                </div><div class='div_input'>
                </div>
            </div>

            <button type="submit">Register<i class='material-symbols-outlined'>person_add</i></button>
        </div>
        </form>
    </main>
</body>
</html>