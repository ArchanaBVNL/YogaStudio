<?php
class Courses
{
    // database table Courses
    private $conn;
    private $table_name = 'courses';

    // variables for Course information
    public $courseId;
    public $courseTitle;
    public $courseLevel;
    public $courseDescription;
    public $courseFee;
    public $instructorId;
    public $startDate;
    public $endDate;
    public $startTime;
    public $endTime;
    public $frequency;
    public $created;
    public $studentLimit;

    // constructor to create database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Get all the Courses from the database using MySQL
     */
    function getAllCourses()
    {
        // select all courses from table 'courses'
        $query =
            'SELECT * FROM ' . $this->table_name . '  ORDER BY created DESC';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Get information of all the Courses a Customer is enrolled in
     */
    function getCustomerCourses($coursesArray)
    {
        // courseIdArray consists of all the courseIds a customer is enrolled in
        $courseIdArray = implode(', ', $coursesArray);

        // get information of all the courses listed in courseIdArray
        $sql =
            'SELECT * FROM ' .
            $this->table_name .
            " WHERE courseId IN ($courseIdArray)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Get Course information for given CourseId
     */
    function getCourseById($courseId)
    {
        // get course information
        $sql =
            'SELECT * FROM ' .
            $this->table_name .
            ' WHERE courseId = :courseId';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':courseId', $courseId);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Add a new course record into the table Courses
     */
    function addCourse($courseData)
    {
        $sql =
            'INSERT INTO ' .
            $this->table_name .
            " (courseTitle, courseLevel, courseDescription, courseFee, instructorId, startDate, endDate, 
            startTime, endTime, frequency, studentLimit) 
        VALUES (:courseTitle, :courseLevel, :courseDescription, :courseFee, :instructorId, :startDate, :endDate, 
        :startTime, :endTime, :frequency, :studentLimit)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($courseData);

        if ($stmt->rowCount() > 0) {
            // return true on success
            return true;
        } else {
            // return false if unsuccessful
            return false;
        }
    }

    /**
     * Update a course record in the table Courses
     */
    function updateCourse($courseData)
    {
        $sql =
            'UPDATE ' .
            $this->table_name .
            " SET courseTitle = :courseTitle, courseLevel = :courseLevel, courseDescription = :courseDescription, 
        courseFee = :courseFee, instructorId = :instructorId, startDate = :startDate, endDate = :endDate, 
        startTime = :startTime, endTime = :endTime, frequency = :frequency, studentLimit = :studentLimit WHERE courseId = :courseId";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':courseTitle', $courseData['courseTitle']);
        $stmt->bindValue(':courseLevel', $courseData['courseLevel']);
        $stmt->bindValue(
            ':courseDescription',
            $courseData['courseDescription']
        );
        $stmt->bindValue(':courseFee', $courseData['courseFee']);
        $stmt->bindValue(':instructorId', $courseData['instructorId']);
        $stmt->bindValue(':startDate', $courseData['startDate']);
        $stmt->bindValue(':endDate', $courseData['endDate']);
        $stmt->bindValue(':startTime', $courseData['startTime']);
        $stmt->bindValue(':endTime', $courseData['endTime']);
        $stmt->bindValue(':frequency', $courseData['frequency']);
        $stmt->bindValue(':studentLimit', $courseData['studentLimit']);
        $stmt->execute($courseData);

        if ($stmt->rowCount() > 0) {
            // return true on success
            return true;
        } else {
            // return false if update unsuccessful
            return false;
        }
    }

    /**
     * Delete a course from the table courses
     */
    function deleteCourse($courseId)
    {
        // delete row with matching courseId
        $sql =
            'DELETE FROM ' . $this->table_name . ' WHERE courseId = :courseId';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':courseId', $courseId);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // return true on success
            return true;
        } else {
            // return false if delete unsuccessful
            return false;
        }
    }

    function validateNewCourse($courseData)
    {
        $sql =
            'SELECT * FROM ' .
            $this->table_name .
            ' WHERE courseTitle = :courseTitle AND courseLevel = :courseLevel AND 
            courseDescription = :courseDescription AND courseFee = :courseFee AND
            instructorId = :instructorId AND startDate = :startDate AND endDate = :endDate AND
            startTime = :startTime AND endTime = :endTime AND frequency = :frequency AND studentLimit = :studentLimit';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':courseTitle', $courseData['courseTitle']);
        $stmt->bindValue(':courseLevel', $courseData['courseLevel']);
        $stmt->bindValue(
            ':courseDescription',
            $courseData['courseDescription']
        );
        $stmt->bindValue(':courseFee', $courseData['courseFee']);
        $stmt->bindValue(':instructorId', $courseData['instructorId']);
        $stmt->bindValue(':startDate', $courseData['startDate']);
        $stmt->bindValue(':endDate', $courseData['endDate']);
        $stmt->bindValue(':startTime', $courseData['startTime']);
        $stmt->bindValue(':endTime', $courseData['endTime']);
        $stmt->bindValue(':frequency', $courseData['frequency']);
        $stmt->bindValue(':studentLimit', $courseData['studentLimit']);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // return false if matching record already present in courses table
            return false;
        } else {
            // return true if no duplicate course found
            return true;
        }
    }

    /**
     * Get Course student limit
     */
    function getCourseStudentLimit($courseId)
    {
        // get course information
        $sql =
            'SELECT studentLimit FROM ' .
            $this->table_name .
            ' WHERE courseId = :courseId';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':courseId', $courseId);
        $stmt->execute();
        $courseStud = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $courseStud;
    }

}
?>
