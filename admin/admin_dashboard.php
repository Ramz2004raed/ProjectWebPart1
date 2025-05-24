<?php
include_once "../Classes/UserClass.php";
$conn = Database::connect(); // <-- أضف هذا السطر

if ($_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit;
}

$sql = "SELECT COUNT(*) AS total_users FROM user";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$total_users = $row['total_users'];
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم الإدارة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            overflow-x: hidden;
        }
        .sidebar {
            background-color: #2c3e50;
            color: white;
            height: 100vh;
            padding-top: 30px;
            position: fixed;
            width: 250px;
            top: 0;
            right: 0;
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
            transition: width 0.3s ease;
        }
        .sidebar h4 {
            font-size: 1.5rem;
            margin-bottom: 30px;
        }
        .sidebar a {
            color: #ecf0f1;
            text-decoration: none;
            font-size: 1.1rem;
            padding: 15px 25px;
            display: block;
            transition: background-color 0.3s ease, padding-right 0.3s ease;
        }
        .sidebar a:hover {
            background-color: #3498db;
            padding-right: 30px;
        }
        .main-content {
            margin-right: 270px;
            padding: 40px 20px;
            min-height: 100vh;
            transition: margin-right 0.3s ease;
        }
        .main-content h1 {
            color: #2c3e50;
            font-size: 2rem;
            margin-bottom: 30px;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            margin-bottom: 25px;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            font-size: 1.1rem;
            font-weight: bold;
            border-bottom: none;
            padding: 15px 20px;
            border-radius: 10px 10px 0 0 !important;
        }
        .card-body {
            padding: 20px;
        }
        .card-title {
            font-size: 1.5rem;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .card-text {
            color: #6c757d;
            margin-bottom: 15px;
        }
        .btn {
            border-radius: 5px;
            padding: 8px 20px;
            font-size: 0.9rem;
            transition: background-color 0.3s ease, transform 0.1s ease;
        }
        .btn:hover {
            transform: scale(1.05);
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>

    <!-- الشريط الجانبي -->
    <div class="sidebar">
        <h4 class="text-center text-white">لوحة التحكم</h4>
        <a href="admin_dashboard.php">الصفحة الرئيسية</a>
        <a href="manage_users.php">إدارة المستخدمين</a>
        <a href="../Front_Page.php">تسجيل الخروج</a>
    </div>

    <!-- المحتوى الرئيسي -->
    <div class="main-content">
        <div class="container">
            <h1>مرحبًا بك في لوحة تحكم الإدارة</h1>

            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            عدد المستخدمين
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $total_users; ?> مستخدم</h5>
                            <p class="card-text">إدارة جميع المستخدمين في النظام.</p>
                            <a href="manage_users.php" class="btn btn-success">عرض المستخدمين</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
