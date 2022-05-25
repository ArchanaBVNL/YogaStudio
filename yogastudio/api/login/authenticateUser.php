<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');

// include database and login classes
require_once '../../config/database.php';
require_once '../../classes/login.php';

// initialize Database Connection
$database = new Database();
$db = $database->getConnection();

$login = new Login($db);

// allow only POST requests
if (strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') {
    throw new Exception('Only POST requests are allowed');
}

// read the user login data
$loginData = file_get_contents('php://input');
$loginDataArray = json_decode($loginData, true);


if ($loginDataArray) {
    // call method authenticateUser to check if user exists in the users and login table
    $stmt = $login->authenticateUser($loginDataArray);

    // if found fetch the user userId and userType
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $user = [
                'userId' => $userId,
                'firstName' => $firstName,
                'lastName' => $lastName,
                'userType' => $userType,
            ];
        }

        // store the user information in the Session variables
        $_SESSION['userId'] = $user['userId'];
        $_SESSION['userType'] = $user['userType'];

        http_response_code(200);
        echo json_encode($user);
    } else {
        // no user found 
        http_response_code(404);
    }
} else {
    // no user data provided to authenticate
    http_response_code(400);
}
?>
