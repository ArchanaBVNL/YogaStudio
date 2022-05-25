<?php
header('Content-Type: application/json; charset=UTF-8');

// include database, courses and registration classes
require_once '../../config/database.php';
require_once '../../classes/courses.php';
require_once '../../classes/registration.php';

// initialize Database Connection
$database = new Database();
$db = $database->getConnection();

$course = new Courses($db);
$registration = new Registration($db);

// accept only GET requests
if (strtoupper($_SERVER['REQUEST_METHOD']) != 'GET') {
    throw new Exception('Only GET requests are allowed');
}

// check if courseId to be removed is set
if (isset($_GET['id'])) {
    $courseId = $_GET['id'];
    $registrationDeleted = true;

    // check if the there are any customers registered in the course to be deleted
    $stmtR = $registration->getUsersByCourse($courseId);
    // if yes, then delete all the corresponding registrations from the registration table
    if ($stmtR->rowCount() > 0) {
        if ($registration->deleteRegistration($courseId) == false) {
            // if there is an error in deleting from table then set flag to false
            $registrationDeleted = false;
        }
    }

    // if there is no registrations for the courseId in registration table then
    if ($registrationDeleted = true) {
        // delete the course data for courseId from the courses table
        if ($course->deleteCourse($courseId)) {
            // on success return 200
            http_response_code(200);
        } else {
            // on error return 400
            http_response_code(400);
        }
    } else {
        // if registration table info couldn't be deleted then return 400
        http_response_code(400);
    }
}
?>