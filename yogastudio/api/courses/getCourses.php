<?php
session_start();
// Uncomment below statement if there are any issues with PHP Sessions
//session_unset();
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

// load all courses information from the database
$stmtAll = $course->getAllCourses();

// if course is found then create a associative array - coursesList[]
if ($stmtAll->rowCount() > 0) {
    // course list array
    $coursesList = [];

    while ($row = $stmtAll->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        // read each course record and store in associative array
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
            'enrolled' => false,
            'classFull' => false,
        ];

        // add all courses into coursesList
        array_push($coursesList, $courseRecord);
        $_SESSION['coursesList'] = $coursesList;
    }
} else {
    http_response_code(200);
    // no courses found then return empty course list
    echo json_encode($coursesList);
}

// check if Session variables - userId & userType are set
if (isset($_SESSION['userId']) && isset($_SESSION['userType'])) {
    // read session variables
    $userId = $_SESSION['userId'];
    $userRole = trim($_SESSION['userType']);

    $count = 0;
    // contains all courses information
    $coursesArray = [];
    // contains all courses a customer is enrolled in
    $enrolledCourseList = [];
    // contains all number of customers enrolled in each course along with courseId
    $courseOccupancyArray = [];

    // first get the number of customers enrolled per course
    $stmtChk = $registration->checkCoursesOccupancy();

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

        // compare the course occupancy with corresponding student limit to avoid further enrollment
        foreach ($courseOccupancyArray as $keyR => $cnt) {
            foreach ($coursesList as $key => $course) {
                // if there are already customers enrolled in the course and course student limit has reached
                if (
                    $cnt['courseId'] == $course['courseId'] &&
                    $course['studentLimit'] == $cnt['count']
                ) {
                    // then mark class as full and do not provide enroll option for new customers
                    $coursesList[$key]['classFull'] = true;
                }
            }
        }
    }

    // if the user is type customer
    if ($userRole == 'customer') {
        $registration = new Registration($db);
        // check if the user has already enrolled in courses
        $stmtR = $registration->getCoursesByUser($userId);

        // if user has enrolled in courses
        if ($stmtR->rowCount() > 0) {
            while ($row = $stmtR->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                // fetch the enrolled courseIds
                array_push($enrolledCourseList, $courseId);
            }

            // for each courseId the user has already enrolled in mark it as 'enrolled'
            foreach ($coursesList as $key => $course) {
                if (in_array($course['courseId'], $enrolledCourseList)) {
                    $coursesList[$key]['enrolled'] = true;
                }
            }
        }
    }

    // assign coursesList to the session variable
    $_SESSION['coursesList'] = $coursesList;

    // if there are courses pending to be confirmed in the cart
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $cartValue) {
            // check if the userId has a corresponding entry in the cart
            if ($cartValue->userId == $userId) {
                // if yes, then get the course list
                $userCart = $cartValue->courses;
                for ($i = 0; $i < count($userCart); $i++) {
                    foreach ($coursesList as $keyC => $courseL) {
                        // the user cart courses that are pending confirmation have enroll status as null
                        if ($userCart[$i] == $courseL['courseId']) {
                            $coursesList[$keyC]['enrolled'] = null;
                            break;
                        }
                    }
                }
            }
        }
    }
    http_response_code(200);
    // if user is logged in then send corresponding coursesList with edit, delete or enroll options
    echo json_encode($coursesList);
} else {
    http_response_code(200);
    // if user is not logged in then send all courses information to be displayed
    echo json_encode($coursesList);
}
?>
