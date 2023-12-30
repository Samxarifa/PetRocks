<?php
    session_start();
    if(!isset($_SESSION['username'])) {
        header('location:../login.php'); #Login Check
    } else if ($_SESSION['isAdmin'] != 1) {
        header('location: ../index.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="../css/settings.css">
    <link rel="icon" href="../img/icon.png">
    <script src="../js/hammer.js"></script>
    <script src="../js/adminPage.js"></script>
    <script src="../js/fetches/orders.js"></script>
</head>
<body>
    <?php include('../code/adminHeader.php')?>
    <?php include('../code/adminNav.php')?>
    <main>
        <div id="admin_wrapper">
            <div id="orders">
                <h2>Orders</h2>
                <section>
                    <ul class='admin_list'></ul>
                </section>
            </div>
        </div>
    </main>
</body>
</html>