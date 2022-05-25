<?php
session_start(); // start session
header('Content-Type: application/json; charset=UTF-8');

// include database 
require_once '../../config/database.php';

// initialize Database Connection
$database = new Database();
$db = $database->getConnection();

// throw exception if the request method is not POST
if (strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') {
    throw new Exception('Only POST requests are allowed');
}

// check if userId is valid Session variable
if (isset($_SESSION['userId'])) {
    // userId
    $userId = $_SESSION['userId'];

    // read POST request inputs and store in associative array
    $enrollData = file_get_contents('php://input');
    $enrollDataArray = json_decode($enrollData, true);

    $found = false;

    // If enroll button is pressed the corresponding course information is added to a cart
    // The cart is specific to a userId and saved in a SESSION variable
    if ($enrollDataArray) {

        // check if $_SESSION['cart] is already set if not initialize it
        if (!isset($_SESSION['cart'])) {

            $_SESSION['cart'] = [];
            $userCart =  new stdClass();
            $userCart->userId = $userId;
            // store the courseId to be confirmed in an array
            $userCart->courses = array($enrollDataArray['courseId']);

            // $_SESSION['cart'] = [{userId, [pending confirmation course]}]
            array_push($_SESSION['cart'], $userCart);  

        } else {

            // if $_SESSION['cart'] is already present then loop on existing entries to finding matching userId
            foreach ($_SESSION['cart'] as $key => $cartValue) {
                if ($cartValue->userId == $userId ) {
                    // if user Cart already exists then add courseId to it
                    array_push($cartValue->courses, $enrollDataArray['courseId']);
                    $found = true;
                    break;
                }
            }

            // if the userId has no Session[cart] variable then create a new entry in array
            if (!$found) {
                $userCart = new stdClass();
                $userCart->userId = $userId;
                $userCart->courses = array($enrollDataArray['courseId']);
                array_push($_SESSION['cart'], $userCart);
            }
        }

        // on success set response code to 200
        http_response_code(200);
        // return the updated cart as response
        echo json_encode($_SESSION['cart']);
    } else {
        // on error send 400
        http_response_code(400);
    }
} else {
    // if no course info found to add into cart then return 400
    http_response_code(400);
}
?>