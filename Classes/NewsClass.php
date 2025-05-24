<?php
include_once "config.php";

class NewsItem {
    public function updateReadersCountForNewsItem($id) {
        if (!is_numeric($id)) return false;
        $conn = Database::connect();

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
            return false;
        }

        $result = $conn->query("SELECT readers_count FROM news WHERE id = $id");
        if (!$result) return false;
        $readersCount = $result->fetch_array()[0];
        $readersCount++;
        $result = $conn->query("UPDATE news SET readers_count = $readersCount WHERE id = $id");

        return $result;
    }

    private function _updateLastInsertedId($newId) {
        if (!is_numeric($newId)) return false;

        $oldId = $this->getLastInsertedId();

        $conn = Database::connect();

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
            return false;
        }

        $result = $conn->query("UPDATE last_insert_news_id SET id = $newId WHERE id = $oldId");
        return $result;
    }

    public function getLastInsertedId() {
        $conn = Database::connect();

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
            return -1;
        }

        $command = "SELECT id FROM last_insert_news_id;";
        $result = $conn->query($command);
        if (!$result) return -1;
        $row = $result->fetch_array();

        return (int)$row[0];
    }

    public function getNewsItem($id) {
        if (!is_numeric($id)) return false;

        $conn = Database::connect();
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
            return false;
        } else if ($id < 0) return false;

        $id = htmlspecialchars($id);

        $stmt = $conn->prepare("
            SELECT n.id as news_id, n.title, n.body, n.image, n.dateposted, n.status, 
                n.category_id, c.name as category_name, c.description, 
                n.author_id
            FROM news n 
            INNER JOIN categories c ON n.category_id = c.id 
            WHERE n.id = ?;
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $newsItem = $result->fetch_assoc();
        $stmt->close();

        return $newsItem;
    }

    public function getTopReadNews($number) {
        if (!is_numeric($number)) return false;

        $conn = Database::connect();

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
            return [];
        }

        $result = $conn->query("
            SELECT n.id, n.title, n.readers_count
            FROM news n
            ORDER BY readers_count DESC
            LIMIT $number;
        ");
        $news = $result->fetch_all(MYSQLI_ASSOC);

        return $news;
    }

    public function getTopCommentedNews($number) {
        if (!is_numeric($number)) return false;

        $conn = Database::connect();

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
            return [];
        }

        $result = $conn->query("
            SELECT n.id, n.title, COUNT(c.id) AS comment_count
            FROM news n
            LEFT JOIN comments c ON n.id = c.news_id
            GROUP BY n.id, n.title
            ORDER BY comment_count DESC
            LIMIT $number;
        ");
        $news = $result->fetch_all(MYSQLI_ASSOC);

        return $news;
    }

    public function insertNewsItem($title, $body, $imagePath, $category, $authorid) {
        if (!is_numeric($category) || !is_numeric($authorid)) return false;

        $body = htmlspecialchars($body);
        $title = htmlspecialchars($title);

        $conn = Database::connect();

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
            return false;
        }

        $stmt = $conn->prepare("
            INSERT INTO news(title, body, image, category_id, author_id) 
            VALUES (?, ?, ?, ?, ?);
        ");
        $stmt->bind_param("sssii", $title, $body, $imagePath, $category, $authorid);
        $result = $stmt->execute();
        $this->_updateLastInsertedId($conn->insert_id);
        $stmt->close();

        return $result;
    }

    public function getNewsForAuthor($authorid) {
        if (!is_numeric($authorid)) return [];

        $conn = Database::connect();

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
            return [];
        } else if ($authorid == -1) return [];

        $stmt = $conn->prepare("
            SELECT n.id as news_id, n.title, n.body, n.image, n.dateposted, n.status, 
                n.category_id, c.name as category_name, c.description, 
                n.author_id, u.name as author_name, u.email, u.password, u.role 
            FROM news n 
            INNER JOIN categories c ON n.category_id = c.id
            INNER JOIN users u ON u.id = n.author_id
            WHERE u.id = ?
            ORDER BY n.dateposted DESC;
        ");
        $stmt->bind_param("i", $authorid);
        $stmt->execute();
        $result = $stmt->get_result();

        $news = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $news;
    }

    public function getLatestNewsFromCategory($category_id, $onlyApprovedNews = false, $newsNumber = -1) {
        if (!is_numeric($category_id)) return [];

        $conn = Database::connect();

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
            return [];
        }

        $condition1 = "";
        if ($onlyApprovedNews) {
            $condition1 = "AND n.status = 1";
        }

        $condition2 = "";
        if ($newsNumber != -1) {
            $condition2 = "LIMIT $newsNumber";
        }

        $command = "
            SELECT n.id as news_id, n.title, n.body, n.image, n.dateposted, n.status, n.category_id, c.name as category_name, c.description
            FROM news n 
            INNER JOIN categories c ON n.category_id = c.id 
            WHERE n.category_id = $category_id $condition1
            ORDER BY n.dateposted DESC $condition2;
        ";

        $result = $conn->query($command);
        $news = $result->fetch_all(MYSQLI_ASSOC);

        return $news;
    }

    public function getLatestNews($newsNumber = -1, $onlyApprovedNews = false) {
        if (!is_numeric($newsNumber)) return [];

        $conn = Database::connect();

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
            return [];
        }

        $condition1 = "1=1";
        if ($onlyApprovedNews) {
            $condition1 = "n.status = 1";
        }

        $condition2 = "";
        if ($newsNumber != -1) {
            $condition2 = "LIMIT $newsNumber";
        }

        $command = "
            SELECT n.id as news_id, n.title, n.body, n.image, n.dateposted, n.status, n.category_id, c.name as category_name, c.description
            FROM news n 
            INNER JOIN categories c ON n.category_id = c.id
            WHERE $condition1
            ORDER BY n.dateposted DESC
            $condition2;
        ";

        $result = $conn->query($command);

        $news = $result->fetch_all(MYSQLI_ASSOC);

        return $news;
    }

    public function denyNewsItem($news_id) {
        if (!is_numeric($news_id)) return false;
        $conn = Database::connect();

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
            return false;
        }

        $command = "UPDATE news SET status = 0 WHERE id = $news_id;";
        $result = $conn->query($command);

        return $result;
    }

    public function approveNewsItem($news_id) {
        if (!is_numeric($news_id)) return false;
        $conn = Database::connect();

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
            return false;
        }

        $command = "UPDATE news SET status = 1 WHERE id = $news_id;";
        $result = $conn->query($command);

        return $result;
    }

    public function deleteNewsItem($news_id) {
        if (!is_numeric($news_id)) return false;
        $conn = Database::connect();

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
            return false;
        }

        // حذف صورة الخبر من السيرفر
        $command = "SELECT image FROM news WHERE id = $news_id;";
        $result = $conn->query($command);
        $row = $result->fetch_assoc();
        if (!empty($row["image"]) && file_exists($row["image"])) {
            unlink($row["image"]);
        }

        // حذف الخبر نفسه من قاعدة البيانات
        $command = "DELETE FROM news WHERE id = $news_id;";
        $result = $conn->query($command);

        return $result;
    }
}
?>
