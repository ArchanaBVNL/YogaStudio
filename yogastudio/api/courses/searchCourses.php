<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');

// include database and courses classes
require_once '../../config/database.php';
require_once '../../classes/courses.php';

// initialize Database Connection
$database = new Database();
$db = $database->getConnection();

$course = new Courses($db);

// allow POST requests only
if (strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') {
    throw new Exception('Only POST requests are allowed');
}

// read search criteria and parameters
$searchData = file_get_contents('php://input');
$searchDataArray = json_decode($searchData, true);

if ($searchDataArray) {
    // criteria can be name / fee / date
    $criteria = $searchDataArray['criteria'];
    // session variables coursesList is searched based on the criteria
    $coursesList = $_SESSION['coursesList'];
    $matchingCoursesList = [];

    switch ($criteria) {
        case 'name': // criteria = name
            if (!empty($searchDataArray['value'])) {
                foreach ($coursesList as $key => $course) {
                    // find the course title with given search string
                    if (
                        stripos(
                            $course['courseTitle'],
                            $searchDataArray['value']
                        ) !== false
                    ) {
                        // if found, then add the corresponding course into the result list
                        array_push($matchingCoursesList, $course);
                    }
                }
            }
            break;
        case 'fee': // criteria = fee
            // for fee, get the min and max search values
            if (
                is_numeric($searchDataArray['min']) &&
                is_numeric($searchDataArray['max'])
            ) {
                foreach ($coursesList as $key => $course) {
                    // find the course with fee between min & max range
                    if (
                        $searchDataArray['min'] <= $course['courseFee'] &&
                        $course['courseFee'] <= $searchDataArray['max']
                    ) {
                        // if found, then add the corresponding course into the result list
                        array_push($matchingCoursesList, $course);
                    }
                }
            }
            break;
        case 'date': // criteria = date
            // for fee, get the min and max date values
            if (
                !empty($searchDataArray['min']) &&
                !empty($searchDataArray['max'])
            ) {
                foreach ($coursesList as $key => $course) {
                    // find the course with date between min & max date ranges
                    if (
                        $searchDataArray['min'] <= $course['startDate'] &&
                        $course['startDate'] <= $searchDataArray['max']
                    ) {
                        // if found, then add the corresponding course into the result list
                        array_push($matchingCoursesList, $course);
                    }
                }
            }
            break;
        default:
            // return empty list as default
            http_response_code(200);
            echo json_encode($matchingCoursesList);
    }

    // return the result list
    http_response_code(200);
    echo json_encode($matchingCoursesList);
} else {
    // no search info given
    http_response_code(400);
}
?>
