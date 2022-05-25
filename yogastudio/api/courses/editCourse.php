<?php
header('Content-Type: application/json; charset=UTF-8');

// include database and courses classes
require_once '../../config/database.php';
require_once '../../classes/courses.php';

// initialize Database Connection
$database = new Database();
$db = $database->getConnection();

$course = new Courses($db);

// make sure the request sent is of type PUT
if (strtoupper($_SERVER['REQUEST_METHOD']) != 'PUT') {
    throw new Exception('Only PUT requests are allowed');
}

// get modified course information to update the database record
$courseData = file_get_contents('php://input');
$courseDataArray = json_decode($courseData, true);

if ($courseDataArray) {
    if ($course->updateCourse($courseDataArray)) {
        // on successful update return 200
        http_response_code(200);
    } else {
        // on unsuccessful update return 400
        http_response_code(400);
    }
} else {
    // No course info found to update the database
    http_response_code(400);
}
?>
