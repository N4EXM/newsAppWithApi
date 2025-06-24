<?php 

    header("Access-Control-Allow-Origin: *"); // Allows requests from any origin (replace * with your React app's URL in production)
    header("Content-Type: application/json; charset=UTF-8"); // Tells React that the response is JSON
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Allowed HTTP methods
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); // Allowed headers

    require_once '../../lib/database.php';

    try {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $data = json_decode(file_get_contents("php://input"), true);

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

            // separate the data
            $email = $data["email"];
            $password = $data["password"];

            // Get the user's data from the database
            $stmt = $pdo->prepare("SELECT userId, email, password, username, authority FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if user exists
            if (!$user) {
                http_response_code(401);
                echo json_encode([
                    "success" => false,
                    "message" => "User doesn't exist"
                ]);
                exit;
            }

            // Verify password (NOTE: In production, use password_verify() with hashed passwords)
            if ($password === $user["password"]) { // This is insecure - see note below
                session_start();
                $_SESSION['user'] = [
                    'id' => $user['userId'],
                    'email' => $user['email'],
                    'username' => $user['username'],
                    'authority' => $user['authority']
                ];
                
                echo json_encode([
                    "success" => true,
                    "message" => "Login successful",
                    "user" => [
                        "id" => $user['userId'],
                        "username" => $user['username'],
                        "email" => $user['email'],
                        "authority" => $user['authority']
                    ]
                ]); 
            }

        }
    }
    catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => "database error"
        ]);
    }



?>