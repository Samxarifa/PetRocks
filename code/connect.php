<?php
    #Function that connects to db
    function connectToDB() {
        return $db = new PDO('mysql:host=comp-server.uhi.ac.uk;dbname=OR22000454','OR22000454','22000454');
    }

?>
