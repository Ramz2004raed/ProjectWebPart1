<?php
session_start();
include '../config/db.php';
include_once '/../classes/NewsClass.php';
include_once '/../classes/UserClass.php'; // تأكد من المسار الصحيح
 // تأكد من المسار الصحيح

if ($_SESSION['role'] != 'author') {
    header("Location: login.php");
    exit();
}

$author_id = $_SESSION['user_id'];

// إنشاء كائن من الكلاس
$newsObj = new NewsClass();

$result = $newsObj->getNewsByAuthor($author_id);

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>لوحة تحكم المؤلف</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet" />
    <style>
        /* التنسيقات كما هي في كودك الأصلي */
        body {
            background-color: #f8f9fa;
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
            text-align: center;
        }
        .sidebar a {
            color:white;
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
        .table th, .table td {
            text-align: center;
            font-size: 1rem;
        }
        .table thead {
            background-color:rgb(44, 62, 80);
            color: #fff;
        }
        .btn-primary {
            background-color:rgb(52, 152, 219);
            border-color:rgb(52, 152, 219);
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 1rem;
        }
        .btn-primary:hover {
            background-color:rgb(41, 128, 185);
            border-color:rgb(41, 128, 185);
        }
        .btn-success {
            background-color:rgb(40, 167, 69);
            border-color:rgb(40, 167, 69);
        }
        .btn-success:hover {
            background-color:rgb(33, 136, 56);
            border-color:rgb(33, 136, 56);
        }
        .btn-danger {
            background-color: #e74c3c;
            border-color: #e74c3c;
        }
        .btn-danger:hover {
            background-color:red;
            border-color: red;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4>لوحة التحكم</h4>
        <a href="author_dashboard.php">لوحة تحكم المؤلف</a>
        <a href="add_news.php">إضافة خبر جديد</a>
        <a href="../Front_Page.php">تسجيل الخروج</a>
    </div>

    <div class="main-content">
        <div class="container">
            <h1>لوحة تحكم المؤلف</h1>

            <div class="d-flex justify-content-end mb-4">
                <a href="add_news.php" class="btn btn-success">إضافة خبر جديد</a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>العنوان</th>
                            <th>التصنيف</th>
                            <th>تاريخ النشر</th>
                            <th>الحالة</th>
                            <th>خيارات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?= htmlspecialchars($row['title']); ?></td>
                                <td><?= htmlspecialchars($row['category_name']); ?></td>
                                <td><?= date("Y-m-d", strtotime($row['dateposted'])); ?></td>
                                <td>
                                    <?php if ($row['status'] == 'approved') { ?>
                                        <span class="badge bg-success">مقبول</span>
                                    <?php } elseif ($row['status'] == 'pending') { ?>
                                        <span class="badge bg-warning">قيد الانتظار</span>
                                    <?php } else { ?>
                                        <span class="badge bg-danger">مرفوض</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <a href="edit_news.php?id=<?= (int)$row['id']; ?>" class="btn btn-sm btn-primary">تعديل</a>
                                    <a href="delete_news.php?id=<?= (int)$row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف الخبر؟');">حذف</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
