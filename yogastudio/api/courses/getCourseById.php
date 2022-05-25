<?php
header('Content-Type: application/json; charset=UTF-8');

// include database and courses classes
require_once '../../config/database.php';
require_once '../../classes/courses.php';

// initialize Database Connection
$database = new Database();
$db = $database->getConnection();

$course = new Courses($db);

// check if the request method is GET
if (strtoupper($_SERVER['REQUEST_METHOD']) != 'GET') {
    throw new Exception('Only GET requests are allowed');
}

// if courseId is set
if (isset($_GET['id'])) {
    $courseId = $_GET['id'];

    // get course Information for given courseId from database
    $stmt = $course->getCourseById($courseId);
}

// if a course information is found in the database
if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row); // extract each corresponding row
        // create an associative array with course record
        $courseInfo = [
            'courseId' => $courseId,
            'courseTitle' => $courseTitle,
            'courseLevel' => $courseLevel,
            'courseDescription' => $courseDescription,
            'courseFee' => $courseFee,
            'instructorId' => $instructorId,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'startTime' => $startTime,
            'endTime' => $endTime,
            'frequency' => $frequency,
            'created' => $created,
            'studentLimit' => $studentLimit,
        ];
    }

    // set response code to 200 on success
    http_response_code(200);
    // return course information for given CourseId
    echo json_encode($courseInfo);
} else {
    // if no course information found return 400 
    http_response_code(400);
}
?>