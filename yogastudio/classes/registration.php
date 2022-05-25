<?php
class Registration
{
    // database table Registration
    private $conn;
    private $table_name = 'registration';

    // Registration table contains userId of customer and CourseId for which he/she is enrolled
    public $userId;
    public $courseId;

    // Database Connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Get all the course enrollments
     */
    function getAllRegistration()
    {
        $query = 'SELECT * FROM ' . $this->table_name;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Get the course Ids of all courses a Customer is enrolled in
     */
    function getCoursesByUser($userId)
    {
        // select all the courses a customer is enrolled in
        $sql =
            'SELECT courseId FROM ' .
            $this->table_name .
            ' WHERE userId = :userId';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':userId', $userId);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Get the Customers enrolled in given Course Id
     */
    function getUsersByCourse($courseId)
    {
        // select all the customers registered for given course
        $sql =
            'SELECT userId FROM ' .
            $this->table_name .
            ' WHERE courseId = :courseId';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':courseId', $courseId);
        $stmt->execute();

        return $stmt;
    }

    /**
     * add a new registration when a customer enrolls in a course
     */
    function addRegistration($newRegistrationData)
    {
        // insert new registration information into table
        $sql =
            'INSERT INTO ' .
            $this->table_name .
            " (userId, courseId) 
        VALUES (:userId, :courseId)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($newRegistrationData);

        // on success return true else false
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * add a new registration when a customer enrolls in a course
     */
    function withdrawRegistration($userId, $courseId)
    {
        // delete registration information from table
        $sql =
            'DELETE FROM ' .
            $this->table_name .
            ' WHERE userId = :userId AND courseId = :courseId';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':userId', $userId);
        $stmt->bindValue(':courseId', $courseId);
        $stmt->execute();

        // on success return true else false
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * delete a registration based on course
     */
    function deleteRegistration($courseId)
    {
        $sql =
            'DELETE FROM ' . $this->table_name . ' WHERE courseId = :courseId';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':courseId', $courseId);
        $stmt->execute();

        // on success return true else false
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * delete a registration for a customer
     */
    function deleteUserRegistration($userId)
    {
        $sql = 'DELETE FROM ' . $this->table_name . ' WHERE userId = :userId';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':userId', $userId);
        $stmt->execute();

        // on success return true else false
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * calculate number of customers enrolled per course
     */
    function checkCoursesOccupancy()
    {
        // find the number of enrollments for each course in Registration table
        $sql =
            'SELECT courseId, count(courseId) as count FROM ' .
            $this->table_name .
            ' GROUP BY courseId';

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt;
    }

    /**
     * calculate number of customers enrolled per course
     */
    function checkCourseOccupancyById($courseId)
    {
        // find the number of enrollments for the course in Registration table
        $sql =
            'SELECT courseId, count(courseId) as count FROM ' .
            $this->table_name .
            ' WHERE courseId = :courseId';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':courseId', $courseId);
        $stmt->execute();

        return $stmt;
    }
}
?>
