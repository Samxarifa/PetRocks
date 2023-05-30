<?php
    session_start();
    
    session_unset(); #Unsets all session variables and destroys the session (Logs User out)
    session_destroy();
    header('refresh:3 ; url=../index.php'); #Waits for 3 seconds before returning to home
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta name="viewport" content="minimum-scale=1"/> -->
    <link rel="icon" href="../img/icon.png">
    <title>Pet Rocks</title>
    <link rel="stylesheet" href="../css/rules.css">
</head>
<body>
    <h1 style='text-align: center; margin-top: 10px'>You have been logged out. Redirecting...</h1>
</body>
</html>
