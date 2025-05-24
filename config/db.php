

<?php
class Database {
    public static function connect() {
        $conn = new mysqli("localhost", "root", "", "project_part_tow_web");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }
}
?>
