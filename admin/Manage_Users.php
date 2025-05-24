<?php
session_start();
require_once "../Classes/UserClass.php";

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../Front_Page.php");
    exit();
}

$userObj = new User();

// حذف مستخدم
if (isset($_GET['delete'])) {
    $userId = $_GET['delete'];
    $userObj->deleteUser($userId);
    header("Location: manage_users.php");
    exit();
}

// إضافة مستخدم
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $name = $_POST['name'] ?? "";
    $email = $_POST['email'] ?? "";
    $password = $_POST['password'] ?? "";
    $role = $_POST['role'] ?? "";

    $userObj->insertUser($name, $email, $password, $role);
    header("Location: manage_users.php");
    exit();
}

// جلب المستخدمين
$users = $userObj->getAllUser();
?>


<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة المستخدمين</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: white;
            margin: 0;
        }
        .sidebar {
            background-color:rgb(44, 62, 80);
            color: white;
            height: 100vh;
            padding-top: 30px;
            position: fixed;
            width: 250px;
            top: 0;
            right: 0;
        }
        .sidebar h4 {
            font-size: 1.5rem;
            margin-bottom: 30px;
        }
        .sidebar a {
            color:rgb(236, 240, 241);
            text-decoration: none;
            font-size: 1.1rem;
            padding: 15px 25px;
            display: block;
        }
        .sidebar a:hover {
            background-color:rgb(52, 152, 219);
            padding-right: 30px;
        }
        .main-content {
            margin-right: 270px;
            padding: 40px 20px;
        }
        .main-content h1 {
            color:rgb(44, 62, 80);
            font-size: 2rem;
            margin-bottom: 30px;
        }
        .card {
            border: none;
            border-radius: 10px;
            margin-bottom: 25px;
        }
        .card:hover {
            margin-top: -5px;
        }
        .card-header {
            font-size: 1.1rem;
            font-weight: bold;
            border-bottom: none;
            padding: 15px 20px;
            border-radius: 10px 10px 0 0;
        }
        .card-body {
            padding: 20px;
        }
        .card-title {
            font-size: 1.5rem;
            color:rgb(44, 62, 80);
            margin-bottom: 10px;
        }
        .card-text {
            color:rgb(108, 117, 125);
            margin-bottom: 15px;
        }
        .btn {
            border-radius: 5px;
            padding: 8px 20px;
            font-size: 0.9rem;
        }
        .btn:hover {
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4 class="text-center text-white">لوحة التحكم</h4>
        <a href="admin_dashboard.php">الصفحة الرئيسية</a>
        <a href="manage_users.php">إدارة المستخدمين</a>
        <a href="../Front_Page.php">تسجيل الخروج</a>
    </div>

    <div class="main-content">
        <div class="container">
            <h1>إدارة المستخدمين</h1>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">إضافة مستخدم جديد</button>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>البريد الإلكتروني</th>
                            <th>الدور</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                             <?php foreach ($users as $user) { ?>
                        <tr>
                            <td><?php echo $user['name']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo ($user['role'] == 'admin') ? 'مدير' : ($user['role'] == 'editor' ? 'محرر' : 'كاتب'); ?></td>
                            <td>
                                <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-warning">تعديل</a>
                                <a href="manage_users.php?delete=<?php echo $user['id']; ?>" class="btn btn-danger">حذف</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="manage_users.php">
                    <input type="hidden" name="add_user" value="1">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">إضافة مستخدم جديد</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">الاسم</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">كلمة المرور</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">الدور</label>
                            <select name="role" id="role" class="form-select" required>
                                <option value="">-- اختر الدور --</option>
                                <option value="editor">محرر</option>
                                <option value="author">كاتب</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">إضافة المستخدم</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>