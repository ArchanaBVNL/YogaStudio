<?php
header('Content-Type: application/json; charset=UTF-8');

// include database, users and login classes
require_once '../../config/database.php';
require_once '../../classes/users.php';
require_once '../../classes/login.php';

// initialize Database Connection
$database = new Database();
$db = $database->getConnection();

$user = new Users($db);
$login = new Login($db);

// allow only POST requests
if (strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') {
    throw new Exception('Only POST requests are allowed');
}

// read the input user data
$userData = file_get_contents('php://input');
$userDataArray = json_decode($userData, true);

if ($userDataArray) {
    // make sure username not already present in login table
    if ($login->checkUserName($userDataArray['username']) == false) {
        // insert new user data into the users table and get userId
        $user_id = $user->addUser($userDataArray);

        // if insert was successful
        if ($user_id != null) {
            // insert new userId and information into login table
            $login->addLogin($user_id, $userDataArray);
        } else {
            // Unable to insert User into users table
            http_response_code(404);
        }
        http_response_code(200);
    } else {
        // duplicate user name in login table
        http_response_code(400);
    }
} else {
    // No User Information found to Add.
    http_response_code(400);
}
?>
