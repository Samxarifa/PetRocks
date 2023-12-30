<?php
    // Set CORS policy - Allow all origin, headers, methods
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Content-Type: application/json");
    
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
        // Gets a list of non-admin users with there details based in defined range
        $sql = "SELECT customerId, forename, surname, street, town, postcode, email, username, memberType, isSuspended
                FROM eCustomer
                WHERE isAdmin = 0
                ORDER BY username ASC
                LIMIT :limit
                OFFSET :offset";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':limit',$limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset',$offset, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch Data as an associative array
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return Result as JSON
        echo json_encode($result);

    } catch(PDOException $error) {
        // Set HTTP Response Code
        http_response_code(500);
        echo json_encode(["Error" => "Database Error: " . $error->getMessage()]);
    } finally {
        $db = null;
    }
?>