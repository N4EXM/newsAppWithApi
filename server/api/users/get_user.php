<?php 

    header("Access-Control-Allow-Origin: *"); // Allows requests from any origin (replace * with your React app's URL in production)
    header("Content-Type: application/json; charset=UTF-8"); // Tells React that the response is JSON
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Allowed HTTP methods
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); // Allowed headers

    require_once '../../lib/database.php';

    try {

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $data = json_decode(file_get_contents("php://input"), true);
            $email = $data["email"];
            $password = $data["password"];

            // Check if data is properly decoded
            if ($data === null) {
                http_response_code(400);
                echo json_encode([
                    "success" => false,
                    "message" => "Invalid JSON input"
                ]);
                exit;
            }

            // Validate required fields
            if (empty($data["email"]) || empty($data["password"])) {
                http_response_code(400);
                echo json_encode([
                    "success" => false,
                    "message" => "Email and password are required"
                ]);
                exit;
            }

            // get user data from the database
            $userQuery = "
                SELECT userId ,username, email, password, contactNumber, userImage, userImageType 
                FROM users 
                WHERE email, password = :email, :password
            ";

            $stmt = $pdo->prepare($userQuery);
            $stmt->execute([$email, $password]);
            $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);

            // get the users bookmarked articles
            $bookmarkQuery = "
                SELECT a.*, u.username as author_name 
                FROM articles a
                JOIN user_articles ua ON a.articleId = ua.article_id
                JOIN users u ON a.authorId = u.userId
                WHERE ua.user_id = :userId
                ORDER BY ua.created_at DESC
            ";

            $stmt = $pdo->prepare($bookmarkQuery);
            $stmt->execute([$userInfo["userId"]]);
            $bookmarkedArticles = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // get the articles created by the user
            $authoredQuery = "
                SELECT a.*, u.username as author_name 
                FROM articles a
                JOIN user_articles ua ON a.articleId = ua.article_id
                JOIN users u ON a.authorId = u.userId
                WHERE ua.user_id = :userId
                ORDER BY ua.created_at DESC
            ";

            $stmt = $pdo->prepare($authoredQuery);
            $stmt->execute([$userInfo["userId"]]);
            $authoredArticles = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($userInfo) {

                $userData = [
                    "username" => $userInfo["username"],
                    "email" => $userInfo["email"],
                    "password" => $userInfo["password"],
                    "contactNumber" => $userInfo["contactNumber"],
                    "image" => $userInfo["userImage"],
                    "imageType" => $userInfo["userImageType"],
                ];

                echo json_encode([
                    "success" => true,
                    "userData" => $userData,
                    "bookmarked" => $bookmarkedArticles,
                    "authored" => $authoredArticles
                ]);
            }
            else {
                echo json_encode([
                    "success" => false,
                    "message" => "was not working"
                ]);
            }

        }

    }
    catch (Exception $e) {
        json_encode([
            "success" => false,
            "message" => "could not fetch data from database"
        ]);
    }