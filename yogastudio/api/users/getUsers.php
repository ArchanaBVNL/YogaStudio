<?php
header('Content-Type: application/json; charset=UTF-8');

// include database and users classes
require_once '../../config/database.php';
require_once '../../classes/users.php';

// initialize Database Connection
$database = new Database();
$db = $database->getConnection();

$user = new Users($db);

// if user info if userId is given else get information of all users
if (isset($_GET['id'])) {
    $stmt = $user->getUserById($_GET['id']);
} else {
    $stmt = $user->getAllUsers();
}

$usersList = [];
// if user record(s) found
if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        // fetch each user record
        $userInfo = [
            'userId' => $userId,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'phoneNumber' => $phoneNumber,
            'emailId' => $emailId,
            'created' => $created,
            'userType' => $userType,
        ];
        // add to the usersList result
        array_push($usersList, $userInfo);
    }

    http_response_code(200);
    // return the users list
    echo json_encode($usersList);
} else {
    // No Users found.
    http_response_code(404);
}
?>
