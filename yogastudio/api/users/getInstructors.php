<?php
header('Content-Type: application/json; charset=UTF-8');

// include database and users classes
require_once '../../config/database.php';
require_once '../../classes/users.php';

// initialize Database Connection
$database = new Database();
$db = $database->getConnection();

$user = new Users($db);

// get all the instructors from the users table
$stmt = $user->getInstructors();

$instructorsList = [];
// if instructor data is found
if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        // fetch each instructor information
        $instructorInfo = [
            'userId' => $userId,
            'firstName' => $firstName,
            'lastName' => $lastName,
        ];
        array_push($instructorsList, $instructorInfo);
    }

    http_response_code(200);
    // return instructors list
    echo json_encode($instructorsList);
} else {
    // no instructor found return empty list
    http_response_code(200);
    echo json_encode($instructorsList);
}
?>
