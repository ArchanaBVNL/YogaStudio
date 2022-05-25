<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');

// include database and registration classes
require_once '../../config/database.php';
require_once '../../classes/registration.php';

// initialize Database Connection
$database = new Database();
$db = $database->getConnection();

$registration = new Registration($db);

// accept only GET requests
if (strtoupper($_SERVER['REQUEST_METHOD']) != 'GET') {
    throw new Exception('Only GET requests are allowed');
}

// if courseId and customer userId is set
if (isset($_GET['id']) && isset($_SESSION['userId'])) {
    $courseId = $_GET['id'];
    $userId = $_SESSION['userId'];

    // customer (userId) withdraws from course (courseId)
    if ($registration->withdrawRegistration($userId, $courseId)) {
        // refresh coursesList session variable to set classFull status to false 
        // as the customer withdrew from course resulting in an open seat
        if (isset($_SESSION['coursesList'])) {
            foreach ($_SESSION['coursesList'] as $key => $course) {
                if ($_SESSION['coursesList'][$key]['courseId'] == $courseId) {
                    $_SESSION['coursesList'][$key]['classFull'] = false;
                } 
            }
        }
        http_response_code(200);
    } else {
        // if the registration datable row couldn't be deleted
        http_response_code(400);
    }
} else {
    // no userId or courseId mentioned to delete registration
    http_response_code(400);
}
?>