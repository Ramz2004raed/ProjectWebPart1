<?php
session_start();
include '../config/db.php';
include_once '../classes/NewsClass.php';  // تأكد من المسار الصحيح للكلاس

if ($_SESSION['role'] != 'author') {
    header("Location: login.php");
    exit;
}

$author_id = $_SESSION['user_id'];

$categories_result = $conn->query("SELECT id, name FROM category");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $body = $_POST['body'];
    $category_id = $_POST['category_id'];
    $image_name = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($image_tmp, '../uploads/' . $image_name);
    }

    // إنشاء كائن من الكلاس
    $news = new NewsItem();

    // استدعاء دالة الإدخال
    $result = $news->insertNewsItem($title, $body, $image_name, $category_id, $author_id);

    if ($result) {
        $success_message = "تمت إضافة الخبر بنجاح! بانتظار الموافقة.";
    } else {
        $error_message = "حدث خطأ أثناء إضافة الخبر.";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>إضافة خبر جديد</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet" />
    <style>
        /* (يمكنك إبقاء نفس التنسيقات التي لديك) */
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
        <h2>إضافة خبر جديد</h2>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success text-center"><?= htmlspecialchars($success_message) ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <div class="form-container">
            <form action="add_news.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">عنوان الخبر</label>
                    <input type="text" name="title" class="form-control" required />
                </div>

                <div class="mb-3">
                    <label class="form-label">محتوى الخبر</label>
                    <textarea name="body" class="form-control" rows="6" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">التصنيف</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- اختر التصنيف --</option>
                        <?php while ($row = $categories_result->fetch_assoc()): ?>
                            <option value="<?= (int)$row['id']; ?>"><?= htmlspecialchars($row['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">صورة الخبر</label>
                    <input type="file" name="image" class="form-control" />
                </div>

                <button type="submit" class="btn btn-primary w-100">نشر الخبر</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
