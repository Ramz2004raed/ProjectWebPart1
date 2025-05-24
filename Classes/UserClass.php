<?php 
require __DIR__ . "/../config/db.php";

class User {

    public function getAllUser() {
        $conn = Database::connect();
        if ($conn->connect_error) return [];

        $command = "SELECT * FROM user";
        $result = $conn->query($command);
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }

    public function getUser($user_id) {
        if (!is_numeric($user_id)) return null;

        $conn = Database::connect();
        if ($conn->connect_error) return null;

        $command = "SELECT * FROM user WHERE id=$user_id";
        $result = $conn->query($command);
        return $result->fetch_assoc();
    }

    public function updateUser($user_id, $name, $email, $password, $role) {
    $conn = Database::connect();
    if ($conn->connect_error) return false;

    $name = htmlspecialchars($name);
    $email = htmlspecialchars($email);
    $role = htmlspecialchars($role);

    }


    public function deleteUser($user_id) {
        if (!is_numeric($user_id)) return false;

        $conn = Database::connect();           
        if ($conn->connect_error) return false;

        $command = "DELETE FROM user WHERE id = $user_id";
        return $conn->query($command);
    }

    public function logIn($email, $password) {
        $email = trim($email);
        $password = trim($password);

        $conn = Database::connect();
        if ($conn->connect_error) return false;

        $stmt = $conn->prepare("SELECT id, password, role FROM user WHERE email = ?");
        if (!$stmt) return false;

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) return false;

                $user = $result->fetch_assoc();
            if (!password_verify($password, $user["password"])) {
                return false;
            }
        
            return $user["id"];
    }

    public function insertUser($name, $email, $password, $role) {        
        $conn = Database::connect();           
        if ($conn->connect_error) return false;

        $name = htmlspecialchars($name);
        $email = htmlspecialchars($email);
        $password = password($password);
        $role = htmlspecialchars($role);

        $stmt = $conn->prepare("INSERT INTO user(name, email, password, role) VALUES(?, ? , ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $password, $role);
        $result = $stmt->execute();        
        $stmt->close();

        return $result;
    }
}
?>
