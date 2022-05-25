<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');

// include database and registration classes
require_once '../../config/database.php';
require_once '../../classes/registration.php';
require_once '../../classes/courses.php';

// initialize Database Connection
$database = new Database();
$db = $database->getConnection();

$registration = new Registration($db);
$courses = new Courses($db);

// allow only GET requests
if (strtoupper($_SERVER['REQUEST_METHOD']) != 'GET') {
    throw new Exception('Only GET requests are allowed');
}

// if userId and cart are set for the logged in user
if (isset($_SESSION['userId']) && isset($_SESSION['cart'])) {
    $userCart;
    $registrationDataArray = [];
    $userId = $_SESSION['userId'];
    $pos;

    // find the user's corresponding cart information with courses pending registration
    foreach ($_SESSION['cart'] as $key => $cartValue) {
        if ($cartValue->userId == $userId) {
            // select the user selected courses that are yet to be confirmed
            $userCart = $cartValue->courses;
            $found = true;
            // if found remember the position of the user cart
            $pos = $key;
            break;
        }
    }

    // if the user has a pending courses in cart
    if ($found) {
        // loop on each course in the cart and register the user into it upon confirmation
        for ($i = 0; $i < count($userCart); $i++) {
            $registrationDataArray['userId'] = $userId;
            $registrationDataArray['courseId'] = $userCart[$i];

            $stmtChk = $registration->checkCourseOccupancyById($userCart[$i]);

            $courseSLimit = $courses->getCourseStudentLimit($userCart[$i]);


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
                }

                // if the student occupancy has reached the limit for the course pending cart 
                // then remove it from the cart
                if ($occupancyRecord['count'] != $courseSLimit[0]['studentLimit']) {
                    $registration->addRegistration($registrationDataArray);
                }
            }

          //  $registration->addRegistration($registrationDataArray);
        }
        // once all the courses are confirmed, delete the user cart entry from the SESSION[cart]
        if($pos==0) {
            array_splice($_SESSION['cart'], $pos, 1);
        } else {
            array_splice($_SESSION['cart'], $pos, $pos);
        }
        http_response_code(200);
    } else {
        // user has no pending courses in the cart
        http_response_code(200);
    }
} else {
    // either userId or SESSION[cart] is not set
    http_response_code(200);
}
?>
