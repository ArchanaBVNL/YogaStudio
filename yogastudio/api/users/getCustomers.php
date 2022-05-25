<?php
header('Content-Type: application/json; charset=UTF-8');

// include database and users classes
require_once '../../config/database.php';
require_once '../../classes/users.php';

// initialize Database Connection
$database = new Database();
$db = $database->getConnection();

$user = new Users($db);

// get information of all the customers in the users tables
$stmt = $user->getCustomers();

$customersList = [];
// if customer(s) data is found
if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        // fetch each customer information
        $customerInfo = [
            'userId' => $userId,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'phoneNumber' => $phoneNumber,
            'emailId' => $emailId,
        ];
        // add to the customersList
        array_push($customersList, $customerInfo);
    }

    http_response_code(200);
    // return the customers list
    echo json_encode($customersList);
} else {
    // No Customer found, return empty list.
    http_response_code(200);
    echo json_encode($customersList);
}
?>
