<?php

class LandingPageController
{
    public function landingPage()
    {
        include __DIR__ . '/../views/landingPage/index.php';
    }

    public function notFound()
    {
        http_response_code(404);
        include __DIR__ . '/../views/404/index.php';
    }

    public function smileOMet()
    {
        $apiKey = isset($_SERVER['HTTP_X_API_KEY']) ? $_SERVER['HTTP_X_API_KEY'] : '';
        $validSecret = "SinergiSecret123";

        if (!isset($_SESSION['user_id']) && $apiKey !== $validSecret) {
            http_response_code(401);
            echo json_encode(["error" => "Unauthorized: Need Login or Valid API Key"]);
            return;
        }

        if (!isset($_FILES['image'])) {
            http_response_code(400);
            echo json_encode(["error" => "No image uploaded"]);
            return;
        }

        $file = $_FILES['image'];
        $filename = 'mood_' . time() . '.png';

        $storagePath = __DIR__ . '/../../storage/moods/';
        $fullPath = $storagePath . $filename;

        if (!is_dir($storagePath)) {
            mkdir($storagePath, 0777, true);
        }

        if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
            http_response_code(500);
            echo json_encode(["error" => "Failed saving file"]);
            return;
        }

        $_SESSION['img_mood'] = '/storage/moods/' . $filename;

        echo json_encode([
            "success" => true,
            "path" => '/storage/moods/' . $filename
        ]);
    }

    public function syncMood()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_GET['img'])) {
            $imagePath = $_GET['img'];

            $imagePath = strip_tags($imagePath);

            $_SESSION['img_mood'] = $imagePath;
        }

        session_write_close();

        header("Location: /sinergi/homepage");
        exit();
    }

    public function deletePreview()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!isset($_SESSION['img_mood'])) {
                echo json_encode(['status' => 'error', 'message' => 'Null Session']);
                exit;
            }


            $filename = $_SESSION['img_mood'];
            $filePath = __DIR__ . '/../../' . $filename;

            error_log("di Klik " . $filename);

            if (file_exists($filePath)) {
                if (unlink($filePath)) {
                    unset($_SESSION['img_mood']);
                    echo json_encode(['status' => 'success', 'debug_file' => $filename]);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed unlink permission']);
                }
            } else {
                unset($_SESSION['img_mood']);
                echo json_encode(['status' => 'success', 'message' => 'No physical files exist, session cleared.']);
            }
            exit;
        }
    }

    public function con()
    {
        include __DIR__ . '/../../config/database.php';
    }
}
