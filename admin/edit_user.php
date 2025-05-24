<?php
session_start();
require_once '../Classes/UserClass.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../Front_Page.php");
    exit();
}

$userObj = new User();

if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);
    $user = $userObj->getUser($userId);

    if (!$user) {
        header("Location: manage_users.php");
        exit();
    }
} else {
    header("Location: manage_users.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    $updated = $userObj->updateUser($userId, $name, $email, $password, $role);

    if ($updated) {
        header("Location: manage_users.php");
        exit();
    } else {
        $error = "حدث خطأ أثناء تحديث المستخدم";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تعديل المستخدم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>تعديل المستخدم</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="edit_user.php?id=<?php echo $user['id']; ?>">
            <div class="mb-3">
                <label for="name" class="form-label">الاسم</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">البريد الإلكتروني</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">كلمة المرور (اتركها فارغة إذا لا تريد التغيير)</label>
                <input type="password" name="password" id="password" class="form-control" >
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">الدور</label>
                <select name="role" id="role" class="form-select" required>
                    <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>مدير</option>
                    <option value="editor" <?php if ($user['role'] == 'editor') echo 'selected'; ?>>محرر</option>
                    <option value="author" <?php if ($user['role'] == 'author') echo 'selected'; ?>>كاتب</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">تحديث المستخدم</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
