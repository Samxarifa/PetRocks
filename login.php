<?php
    session_start();
    if (isset($_SESSION['username'])) { #Checks user is not logged in
        header('location: index.php');
    } else if (isset($_SESSION['loginFail'])) { #Checks for previous failed login attempt
        $failText = 'Incorrect Username or Password';
        $_SESSION['loginFail'] = null;
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
        <a id='register' href="register.php"><i class="material-symbols-outlined">person_add</i></a>
        <div id='accountForm'>
            <h1>Sign In</h1>
            <p id='failText'><?php echo $failText?></p>
            <form action="code/validateLogin.php" method="post">
                <p>Username</p>
                <input type="text" name="username" id="username"><br>
                <p>Password</p>
                <input type="password" name="password" id="password"><br>
                <button type="submit">Login<i class='material-symbols-outlined'>login</i></button>
            </form>
        </div>
    </main>
</body>
</html>