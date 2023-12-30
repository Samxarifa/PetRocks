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
    
    $id = intval($_POST['custId']);
    $suspend = intval($_POST['suspend']);

    if ($suspend == 1) {
        $suspendReturn = "Suspended";
    } else {
        $suspendReturn = "Un-Suspended";
    }

    // Enable Error Reporting
    // error_reporting(E_ALL);
    // ini_set('display_errors',1);

    require_once('../code/connect.php');
    $db = connectToDB();
    // Sets account suspension based on input
    try {
        $sql = "UPDATE eCustomer 
                SET isSuspended = :suspend 
                WHERE customerId = :custId";
        
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':custId',$id,PDO::PARAM_INT);
        $stmt->bindParam(':suspend',$suspend,PDO::PARAM_INT);
        $stmt->execute();

        // Return Success
        http_response_code(200);
        echo json_encode(["Success","Account $id " . $suspendReturn]);

    } catch(PDOException $error) {
        // Set HTTP Response Code
        http_response_code(500);
        echo json_encode(["Error","Database Error: " . $error->getMessage()]);
    } finally {
        $db = null;
    }
?>