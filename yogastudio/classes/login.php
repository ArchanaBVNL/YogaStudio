<?php
class Login
{
    // database table Login
    private $conn;
    private $table_name = 'login';

    // Login variables
    public $userId;
    public $userName;
    public $password;

    // Database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Authenticate User by checking if corresponding entry exists in Login and User tables
     */
    function authenticateUser($loginData)
    {
        // select user info from Users and Login table
        $sql =
            'SELECT a.userId, b.firstName, b.lastName, b.userType FROM ' .
            $this->table_name .
            ' a, users b WHERE a.userName = :userName AND a.password = :password AND a.userId = b.userId';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':userName', $loginData['username']);
        $stmt->bindValue(':password', $loginData['password']);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Add new user login credentials into Login table
     */
    function addLogin($userId, $userData)
    {
        // insert into table Login
        $sql =
            'INSERT INTO ' .
            $this->table_name .
            " (userId, userName, password) 
        VALUES (:userId, :userName, :password)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':userId', $userId);
        $stmt->bindValue(':userName', $userData['username']);
        $stmt->bindValue(':password', $userData['password']);
        $stmt->execute();
    }

     // get the userName from Login table
     function checkUserName($userName)
     {
         $query =
             'SELECT * FROM ' .
             $this->table_name .
             ' WHERE userName = :userName';
 
         $stmt = $this->conn->prepare($query);
         $stmt->bindValue(':userName', $userName);
         $stmt->execute();
         
         if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
     }
}
?>
