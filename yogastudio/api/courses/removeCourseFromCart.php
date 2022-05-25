<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');

// include database class
require_once '../../config/database.php';

// initialize Database Connection
$database = new Database();
$db = $database->getConnection();

// accept only GET request
if (strtoupper($_SERVER['REQUEST_METHOD']) != 'GET') {
    throw new Exception('Only GET requests are allowed');
}

// if courseId and userId is set
if (isset($_GET['id']) && isset($_SESSION['userId'])) {
    $courseId = $_GET['id'];
    $userId = $_SESSION['userId'];

    // used to delete a course the user has added into the cart 
    // find the the cart corresponding to the userId
    foreach ($_SESSION['cart'] as $key => $cartValue) {
        if ($cartValue->userId == $userId) {
            // find the courseId in the coursesList
            $pos = array_search($courseId, $_SESSION['cart'][$key]->courses);
            // delete the courseId from the cart
            if($pos==0) {
                array_splice($_SESSION['cart'][$key]->courses, $pos, 1);
            } else {
                array_splice($_SESSION['cart'][$key]->courses, $pos, $pos);
            }
            $_SESSION['cart'] = $_SESSION['cart'];
            break;
        }
    }
    http_response_code(200);
} else {
    // no courseId or userId is set
    http_response_code(400);
}
?>
