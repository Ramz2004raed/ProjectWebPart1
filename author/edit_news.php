<?php
session_start();
include '../config/db.php';

if ($_SESSION['user_role'] == 'author') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "لا يوجد معرف خبر لتعديله.";
    exit();
}

$news_id = intval($_GET['id']);
$author_id = $_SESSION['user_id'];
$message = "";

$query = "SELECT * FROM news WHERE id = ? AND author_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $news_id, $author_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    echo "الخبر غير موجود أو ليس لديك صلاحية لتعديله.";
    exit();
}

$news = $result->fetch_assoc();

$cat_result = $conn->query("SELECT * FROM category");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $body = $_POST['body'];
    $category_id = $_POST['category_id'];

    $update_query = "UPDATE news SET title = ?, body = ?, category_id = ? WHERE id = ? AND author_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssiii", $title, $body, $category_id, $news_id, $author_id);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>تم تحديث الخبر بنجاح.</div>";
        $news['title'] = $title;
        $news['body'] = $body;
        $news['category_id'] = $category_id;
    } else {
        $message = "<div class='alert alert-danger'>حدث خطأ أثناء التحديث.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تعديل الخبر</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal&display=swap" rel="stylesheet">
  
</head>

<style>
        body {
            background-color: #white;
        }
        .sidebar {
            background-color:rgb(44, 62, 80);
            color: white;
            height: 100vh;
            position: fixed;
            width: 250px;
            top: 0;
            right: 0;
            padding-top: 30px;
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
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
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }
        .table thead {
            background-color:rgb(44, 62, 80);
            color: white;
        }
        .badge-published {
            background-color:rgb(40, 167, 69);
        }
        .badge-pending {
            background-color:rgb(243, 156, 18);
        }
        .badge-denied {
            background-color:rgb(231, 76, 60);
        }
        .btn-custom {
            background-color:rgb(52, 152, 219);
            color: white;
        }
        .btn-custom:hover {
            background-color:rgb(41, 128, 185);
        }
    </style>
    </style>
</head>
<body>
    <div class="sidebar">
        <h4 class="text-center text-white">لوحة التحكم</h4>
        <a href="author_dashboard.php">لوحة تحكم المؤلف</a>
        <a href="add_news.php">إضافة خبر جديد</a>
        <a href="../Front_Page.php">تسجيل الخروج</a>
    </div>

    <div class="main-content">
        <div class="container">
            <h2 class="mb-4">تعديل الخبر</h2>
            <?= $message ?>
            <form method="POST" class="form-container">
                <div class="mb-3">
                    <label for="title" class="form-label">عنوان الخبر</label>
                    <input type="text" id="title" name="title" class="form-control" required value="<?= htmlspecialchars($news['title']) ?>">
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label">التصنيف</label>
                    <select id="category_id" name="category_id" class="form-select" required>
                        <?php while ($cat = $cat_result->fetch_assoc()): ?>
                            <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $news['category_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="body" class="form-label">نص الخبر</label>
                    <textarea id="body" name="body" class="form-control" rows="6" required><?= htmlspecialchars($news['body']) ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                <a href="author_dashboard.php" class="btn btn-secondary">إلغاء</a>
            </form>
        </div>
    </div>
</body>
</html>
