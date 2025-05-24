<?php
require_once 'config/db.php';

$conn = Database::connect(); // <-- أضف هذا السطر


$sql = "SELECT * FROM news WHERE status = 'approved' ORDER BY dateposted DESC";
$result = $conn->query($sql);

$cats_sql = "SELECT * FROM category";
$cats_result = $conn->query($cats_sql);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>الصفحة الرئيسية - أخبار</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { 
      background-color: white;
     }


    .navbar { 
      padding: 10px 20px;
     }


    .navbar .nav-link { 
      color: white;
     }


    .news-card img {
       width: 100%; 
       height: 150px;
     }


    .news-card {
       margin-bottom: 20px; 
    }

    .category-section {
       margin-top: 30px;
     }

    .category-section h2 {
      background-color: blue;
      color: white;
      padding: 10px;
      border-radius: 5px;
    }
    .top-news {
      background-color: white;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    .main-news-card { 
      margin-bottom: 20px;
    
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
  <div class="container">
    <a class="navbar-brand" href="#">أخبار العالم</a>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav"><a class="nav-link" href="Front_Page.php">الرئيسية</a></li>
        <li class="nav"><a class="nav-link" href="category.php">سياسة</a></li>
        <li class="nav"><a class="nav-link" href="economy.php">اقتصاد</a></li>
        <li class="nav"><a class="nav-link" href="sports.php">رياضة</a></li>
        <li class="nav"><a class="nav-link" href="health.php">صحة</a></li>
      </ul>
      <a href="login.php" class="btn btn-light ms-2">تسجيل الدخول</a>
    </div>
  </div>
</nav>

<div class="container mt-4">

  <div class="top-news mb-5">
    <h2 class="section-title">أهم الأخبار</h2>
    <div class="row">
    <?php 
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
    ?>
      <div class="col-md-8">
        <div class="main-news-card">
          <img src="Uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>" class="img-fluid">
          <div class="news-content">
            <h3><?php echo $row['title']; ?></h3>
            <p><?php echo substr($row['body'], 0, 150) . '...'; ?></p>
            <a href="details.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">المزيد</a>
          </div>
        </div>
      </div>
    <?php
      }
    }
    ?>
    </div>
  </div>

  <?php
  if ($cats_result->num_rows > 0) {
    while ($cat = $cats_result->fetch_assoc()) {
      $cat_id = $cat['id'];
      $cat_name = $cat['name'];

      $news_sql = "SELECT * FROM news WHERE category_id = $cat_id AND status = 'approved' ORDER BY dateposted DESC LIMIT 4";
      $news_result = $conn->query($news_sql);

      if ($news_result->num_rows > 0) {
  ?>
  <div class="category-section">
    <h2><?php echo $cat_name; ?></h2>
    <div class="row">
    <?php
      while ($row = $news_result->fetch_assoc()) {
    ?>
      <div class="col-md-4">
        <div class="card" style="width: 18rem;">
          <img src="<?php echo $row['image']; ?>" class="card-img-top" alt="<?php echo $row['title']; ?>">
          <div class="card-body">
            <h5 class="card-title"><?php echo $row['title']; ?></h5>
            <p class="card-text"><?php echo substr($row['body'], 0, 100) . '...'; ?></p>
            <a href="details.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">قراءة المزيد</a>
          </div>
        </div>
      </div>
    <?php 
      } 
    ?>
    </div>
  </div>
  <?php 
      } 
    } 
  } 
  ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
