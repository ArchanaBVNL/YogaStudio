<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');

// include database and courses classes
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

// if userId and session[cart] is set
if (isset($_SESSION['userId']) && isset($_SESSION['cart'])) {
    $userCart;
    $found = false;

    // load all the user specific courses in the cart that are pending enrollment
    foreach ($_SESSION['cart'] as $key => $cartValue) {
        if ($cartValue->userId == $_SESSION['userId']) {
            $userCart = $cartValue->courses;
            $found = true;
            break;
        }
    }

    // if the user has courses that are pending confirmation in the cart then display the cart
    if ($found) {
        // used to delete a course from cart that is full occupancy
        // find the the cart corresponding to the userId and update

        $coursesInfo = [];
        for ($i = 0; $i < count($userCart); $i++) {
            // get each course information for the corresponding courseId in the cart
            $stmt = $course->getCourseById($userCart[$i]);

            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $courseRecord = [
                        'courseId' => $courseId,
                        'courseTitle' => $courseTitle,
                        'courseFee' => $courseFee,
                        'studentLimit' => $studentLimit,
                    ];

                    array_push($coursesInfo, $courseRecord);
                }
            } else {
                http_response_code(200);
                // if no course information was found return empty array
                echo json_encode($coursesInfo);
            }
        }

        foreach ($coursesInfo as $kc => $kv) {
            // first get the number of customers enrolled per course
            $stmtChk = $registration->checkCourseOccupancyById($kv['courseId']);
            $courseOccupancyArray = [];

            // if there are some students enrolled in the courses
            if ($stmtChk->rowCount() > 0) {
                while ($rowC = $stmtChk->fetch(PDO::FETCH_ASSOC)) {
                    extract($rowC);
                    // find the courseId and corresponding number of customers enrolled in it
                    $occupancyRecord = [
                        'courseId' => $courseId,
                        'count' => $count,
                    ];
                    // read and store all the enrolled courses into an array
                    array_push($courseOccupancyArray, $occupancyRecord);
                }

                // if the student occupancy has reached the limit for the course pending cart 
                // then remove it from the cart
                if ($occupancyRecord['count'] == $kv['studentLimit']) {
                    if ($kc == 0) {
                        array_splice($coursesInfo, $kc, 1);
                    } else {
                        array_splice($coursesInfo, $kc, $kc);
                    }
                }
            }
        }
        // course found in the cart for the user
        http_response_code(200);
        echo json_encode($coursesInfo);
    } else {
        // no courses pending for the user in the cart
        http_response_code(200);
    }
} else {
    // userId or cart session variable was not set
    http_response_code(200);
}
?>
