<?php
header('Content-Type: application/json; charset=UTF-8');

// include database and courses
require_once '../../config/database.php';
require_once '../../classes/courses.php';

// initialize Database Connection
$database = new Database();
$db = $database->getConnection();

// course object
$course = new Courses($db);

// if method type is not POST then throw exception
if (strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') {
    throw new Exception('Only POST requests are allowed');
}

// read new course information from the POST request
$courseData = file_get_contents('php://input');
// decode JSON into an associative array
$courseDataArray = json_decode($courseData, true);

if ($courseDataArray) {
    // validate New Course Info to avoid duplicate record
    if ($course->validateNewCourse($courseDataArray)) {
        // add new course information by calling addCourse()
        if ($course->addCourse($courseDataArray)) {
            // on success send 200
            http_response_code(200);
        } else {
            // on error send code 400
            http_response_code(400);
        }
    } else {
        // on error send code 400
        http_response_code(400);
    }
} else {
    // if no course information was entered return 400
    http_response_code(400);
}
?>