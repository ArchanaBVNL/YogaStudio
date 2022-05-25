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
$coursesList = [];

// allow only GET requests
if (strtoupper($_SERVER['REQUEST_METHOD']) != 'GET') {
    throw new Exception('Only GET requests are allowed');
}

// if customer id is set
if (isset($_GET['id'])) {
    $customerId = $_GET['id'];
    $count = 0;
    $enrolledCourseList = [];

    $registration = new Registration($db);

    // get all the courses the given customer is enrolled in
    $stmtR = $registration->getCoursesByUser($customerId);

    if ($stmtR->rowCount() > 0) {
        while ($row = $stmtR->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            // fetch all the courseIds of customer enrolled courses
            array_push($enrolledCourseList, $courseId);
        }

        // get the course Information of all the customer courseIds
        $stmtC = $course->getCustomerCourses($enrolledCourseList);

        if ($stmtC->rowCount() > 0) {
            while ($row = $stmtC->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $courseRecord = [
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
                array_push($coursesList, $courseRecord);
            }
        }
    
        http_response_code(200);
        // return all the customer enrolled courses data
        echo json_encode($coursesList);
    } else {
        http_response_code(200);
        // return empty list if no courses were found for the customer
        echo json_encode($coursesList);
    }
} else {
    http_response_code(200);
    // return empty list if no id was mentioned
    echo json_encode($coursesList);
}
?>
