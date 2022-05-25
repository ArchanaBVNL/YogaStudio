<?php
header('Content-Type: application/json; charset=UTF-8');

// include database and login classes
require_once '../../config/database.php';
require_once '../../classes/login.php';

// initialize Database Connection
$database = new Database();
$db = $database->getConnection();

$login = new Login($db);

// accept only POST requests
if (strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') {
    throw new Exception('Only POST requests are allowed');
}

// read the login data
$loginData = file_get_contents('php://input');
$loginDataArray = json_decode($loginData, true);

if ($loginDataArray) {
    // add new user login info into Login table
    $login->addLogin($loginDataArray);
    http_response_code(200);
} else {
    // no login info found to add
    http_response_code(400);
}
?>
