<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $delete = "DELETE FROM news WHERE id = $id";
    if (mysqli_query($conn, $delete)) {
        header("Location: author_dashboard.php?deleted=1");
        exit();
    } else {
        echo "فشل في حذف الخبر.";
    }
} else {
    echo "لم يتم تحديد الخبر.";
}
?>
