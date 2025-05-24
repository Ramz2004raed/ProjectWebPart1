<?php
session_start();
include '../config/db.php';

if ($_SESSION['user_role'] != 'editor') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];

    if ($action == 'approved') {
        $sql = "UPDATE news SET status='approved' WHERE id=$id";
        mysqli_query($conn, $sql);
    } elseif ($action == 'deny') {
        $sql = "UPDATE news SET status='denied' WHERE id=$id";
        mysqli_query($conn, $sql);
    } elseif ($action == 'delete') {
        $sql = "DELETE FROM news WHERE id=$id";
        mysqli_query($conn, $sql);
    }

    header("Location: editor_dashboard.php");
    exit();
}

$sql = "SELECT * FROM news";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة الأخبار</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            
            background-color: #f8f9fa;
        }
        .sidebar {
            background-color: rgb(44, 62, 80);
            color: white;
            height: 100vh;
            position: fixed;
            width: 250px;
            top: 0;
            right: 0;
            padding-top: 30px;
        }
        .sidebar h4 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 1.5rem;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
            padding: 15px 20px;
            display: block;
        }
        .sidebar a:hover {
            background-color:rgb(52, 152, 219);
        }
        .main-content {
            margin-right: 270px;
            padding: 40px 20px;
        }
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }
        .table thead {
            background-color:rgb(44, 62, 80);
            color: white;
        }
        .btn-custom {
            background-color:rgb(52, 152, 219);
            color: white;
        }
        .btn-custom:hover {
            background-color:rgb(41, 128, 185);
        }
    </style>
</head>
<body>
<div class="sidebar">
    <h4>لوحة تحكم المحرر</h4>
    <a href="editor_dashboard.php">لوحة التحكم</a>
    <a href="../Front_Page.php">تسجيل الخروج</a>
</div>

<div class="main-content">
    <div class="container">
        <h1>إدارة الأخبار</h1>
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
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo $row['category_id']; ?></td>
                        <td><?php echo $row['dateposted']; ?></td>
                        <td>
    <?php
    if ($row['status'] == 'approved') {
        echo '<span class="badge bg-success">موافق عليه</span>';
    } elseif ($row['status'] == 'denied') {
        echo '<span class="badge bg-danger">مرفوض</span>';
    } else {
        echo '<span class="badge bg-warning">معلق</span>';
    }
    ?>
</td>
                        <td>
                            <a href="editor_dashboard.php?action=approved&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-custom">موافقة</a>
                            <a href="editor_dashboard.php?action=deny&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger">رفض</a>
                            <a href="editor_dashboard.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger">حذف</a>
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