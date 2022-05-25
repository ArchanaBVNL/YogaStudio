<?php
class Users
{
    // database table Users
    private $conn;
    private $table_name = 'users';

    // User data
    public $userId;
    public $firstName;
    public $lastName;
    public $phoneNumber;
    public $emailId;
    public $created;
    public $userType;

    // database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * get User information for given userId
     */
    function getUserById($userId)
    {
        // select * from users where userId = $userId
        $sql = 'SELECT * FROM ' . $this->table_name . ' WHERE userId = :userId';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':userId', $userId);
        $stmt->execute();

        return $stmt;
    }

    /**
     * get all user records from table users
     */
    function getAllUsers()
    {
        // select * from users
        $sql = 'SELECT * FROM ' . $this->table_name . '  ORDER BY created DESC';

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt;
    }

    /**
     * get all the users of type customer from users table
     */
    function getCustomers()
    {
        // select * from users where userType = 'customer'
        $query =
            'SELECT * FROM ' .
            $this->table_name .
            "  where userType = 'customer' ORDER BY userId ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // get all the users of type admin from users table
    function getAdmins()
    {
        // select all admin records
        $query =
            'SELECT * FROM ' .
            $this->table_name .
            "  where userType = 'admin' ORDER BY userId ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    /**
     * get all the users of type instructor from users table
     */
    function getInstructors()
    {
        // select all instructor records
        $query =
            'SELECT * FROM ' .
            $this->table_name .
            "  where userType = 'instructor' ORDER BY userId ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    /**
     * add a new user into users table
     */
    function addUser($userData)
    {
        $sql =
            'INSERT INTO  ' .
            $this->table_name .
            " (firstName, lastName, phoneNumber, emailId, userType) 
        VALUES (:firstName, :lastName, :phoneNumber, :emailId, :userType)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':firstName', $userData['firstName']);
        $stmt->bindValue(':lastName', $userData['lastName']);
        $stmt->bindValue(':phoneNumber', $userData['phoneNumber']);
        $stmt->bindValue(':emailId', $userData['emailId']);
        $stmt->bindValue(':userType', $userData['userType']);
        $stmt->execute();
        $stmt->closeCursor();

        // get the new user_id auto created by MySQL
        $user_id = $this->conn->lastInsertId();

        // return new user user_id on success else return null
        if (!empty($user_id)) {
            return $user_id;
        } else {
            return null;
        }
    }
}
?>
