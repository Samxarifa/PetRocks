<?php
    #Function that connects to db
    function connectToDB() {
        return $db = new PDO('mysql:host=db;dbname=sx','sx','sx');
    }

?>