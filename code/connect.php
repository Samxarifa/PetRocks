<?php
    #Function that connects to db
    function connectToDB() {
        return $db = new PDO('mysql:host=<ip>;dbname=<name>','<username>','<password>');
    }

?>
