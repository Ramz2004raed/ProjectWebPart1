<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التصنيف - أخبار العالم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: white;
        }

        .news-card img {
            width: 100%;
            height: 200px;
        }

        .news-card {
            margin-bottom: 20px;
            border-radius: 8px;
            overflow: hidden;
        }

        .category-header {
            background-color: blue;
            color: white;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 10px;
        }

        .navbar {
            padding: 10px 20px;
            background-color: blue;
        }

        .navbar .nav-link {
            color: white;
        }

        .news-content {
            padding: 15px;
        }
    </style>
</head>
<body>
<?php
?>
<nav class="navbar navbar-expand-lg" data-bs-theme="dark">
    <div class="container">
        <a class="navbar-brand" href="#">أخبار العالم</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav">
                    <a class="nav-link" href="Front_Page.php">الرئيسية</a>
                </li>
                <li class="nav">
                    <a class="nav-link" href="politics.php">سياسة</a>
                </li>
                <li class="nav">
                    <a class="nav-link" href="economy.php">اقتصاد</a>
                </li>
                <li class="nav">
                    <a class="nav-link" href="sports.php">رياضة</a>
                </li>
                <li class="nav">
                    <a class="nav-link" href="health.php">صحة</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="category-header">
        <h1>أخبار السياسة</h1>
        <p class="mb-0">آخر التطورات والأخبار في عالم السياسة</p>
    </div>
    <div class="row">
        <?php
        $news = [
            [
                "title" => "نتنياهو: غدا سيكون يوما صعبا لإسرائيل",
                "desc" => "ذكر مكتب رئيس الوزراء الإسرائيلي بنيامين نتنياهو، الأربعاء، أن إسرائيل...",
                "img" => "https://images.skynewsarabia.com/images/v3/2025/01/14/1768983/1100/619/1-1768983.jpg",
                "link" => "details.php"
            ],
            [
                "title" => "بوتين يعلن \"تأييد\" مقترح وقف إطلاق النار مع أوكرانيا",
                "desc" => "قال الرئيس الروسي فلاديمير بوتين، الخميس، إنه \"يؤيد\" وقفا مؤقتا لإطلاق النار في...",
                "img" => "https://images.skynewsarabia.com/images/v1/2025/03/13/1783558/2000/1125/1-1783558.jpg",
                "link" => "details.php"
            ],
            [
                "title" => "السيسي يؤكد على أهمية بدء عملية سياسية شاملة في سوريا",
                "desc" => "أكد الرئيس المصري عبد الفتاح السيسي، الثلاثاء، ضرورة الحفاظ على وحدة سوريا...",
                "img" => "https://images.skynewsarabia.com/images/v2/2024/11/07/1753229/2000/1125/1-1753229.JPG",
                "link" => "details.php"
            ],
            [
                "title" => "الكرملين يحدد هدف لقاء ترامب وبوتين.. وسبب اختيار السعودية",
                "desc" => "أكد الكرملين أن المحادثات الروسية الأميركية، الثلاثاء، في الرياض هدفها هو الاستعادة...",
                "img" => "https://images.skynewsarabia.com/images/v3/2018/08/21/1175563/1100/619/1-1175563.jpg",
                "link" => "details.php"
            ],
            [
                "title" => "قبل إقرار اتفاق لبنان.. إسرائيل تلوح بـ\"سياسة عدم التسامح\"",
                "desc" => "قال وزير الدفاع الإسرائيلي يسرائيل كاتس لمبعوثة الأمم المتحدة في لبنان، الثلاثاء، إن أي خرق...",
                "img" => "https://images.skynewsarabia.com/images/v1/2024/11/12/1754340/2000/1125/1-1754340.JPG",
                "link" => "details.php"
            ],
        ];

        foreach ($news as $item) {
            echo '
            <div class="col-md-4">
                <div class="news-card">
                    <img src="' . $item["img"] . '" alt="خبر سياسي">
                    <div class="news-content">
                        <h5>' . $item["title"] . '</h5>
                        <p>' . $item["desc"] . '</p>
                        <a href="' . $item["link"] . '" class="btn btn-primary">قراءة المزيد</a>
                    </div>
                </div>
            </div>';
        }
        ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>