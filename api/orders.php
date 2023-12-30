<?php
    // Set CORS policy - Allow all origin, headers, methods
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Content-Type: application/json");
    
    // Login and Admin Check
    session_start();
    if(!isset($_SESSION['username'])) {
        http_response_code(403);
        echo json_encode(["Error" => "You are not logged In"]);
        return;
    } else if ($_SESSION['isAdmin'] != 1) {
        http_response_code(403);
        echo json_encode(["Error" => "You are not an Admin"]);
        return;
    }
    
    // Enable Error Reporting
    // error_reporting(E_ALL);
    // ini_set('display_errors',1);

    $limit = isset($_GET['limit'])? intval($_GET['limit']) : 20;
    $offset = isset($_GET['offset'])? intval($_GET['offset']) : 0;
    
    require_once('../code/connect.php');
    $db = connectToDB();
    try {
        // Gets orders from eOrder Table in certain defined range
        $sql = "SELECT orderId, username, orderDate, price
                FROM eOrders, eCustomer
                WHERE eOrders.customerId = eCustomer.customerId
                ORDER BY orderId ASC
                LIMIT :limit
                OFFSET :offset";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':limit',$limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset',$offset, PDO::PARAM_INT);
        $stmt->execute(); //Gets Order Details

        $jsonResponse = array();
        while ($row = $stmt->fetch()) {
            // For each order, adds order details to array and gets each product in that order
            $rowArray = array();
            $rowArray['orderId'] = $row['orderId'];
            $rowArray['username'] = $row['username'];
            $rowArray['orderDate'] = $row['orderDate'];
            $rowArray['price'] = $row['price'];
            $rowArray['products'] = array(); //Creates array for products

            //Gets all products in defined order
            $productsSql = "SELECT eStock.name, quantity
                            FROM eOrderStock, eStock
                            WHERE eOrderStock.stockId = eStock.stockId
                            AND eOrderStock.orderId = :orderId";
            $productsQuery = $db->prepare($productsSql);
            $productsQuery->bindParam(':orderId',$row['orderId']);
            $productsQuery->execute(); //Gets products from order
            while ($product = $productsQuery->fetch()) {
                // For each product, add name and quantity to array
                array_push($rowArray['products'],
                ["name" => $product['name'],"quantity" => $product['quantity']]);
            }
            array_push($jsonResponse,$rowArray); //Addes Order to array of orders
        }
        // Return Result as JSON
        echo json_encode($jsonResponse);
    } catch(PDOException $error) {
        // Set HTTP Response Code
        http_response_code(500);
        echo ["Error" => "Database Error: " . $error->getMessage()];
    } finally {
        $db = null;
    }
?>