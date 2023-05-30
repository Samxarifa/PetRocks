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
        <h1 id='page_heading'>About Us</h1>
        <section class='content'>
            <p>Scott Bots: CEO</p>
            <p>Alex Cooper: Chief Operating Officer</p>
            <p>Scott Tizzle: Chief Creative Director</p>
        </section>
    </main>
    <?php include('code/footer.php')?>
</body>

</html>