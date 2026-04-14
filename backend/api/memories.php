<?php
/**
 * api/memories.php
 * Handles: GET / POST / PUT / DELETE
 */

require_once __DIR__ . '/../config/db.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$db = get_db();
$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;

switch ($method) {
    case 'GET':
        $query = "SELECT id, title, description, category, emotion, date, worldX, worldY, size FROM memories ORDER BY date DESC";
        $result = $db->query($query);
        $rows = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $row['id'] = (int)$row['id'];
                $row['worldX'] = $row['worldX'] !== null ? (float)$row['worldX'] : null;
                $row['worldY'] = $row['worldY'] !== null ? (float)$row['worldY'] : null;
                $row['size'] = (int)$row['size'];
                $rows[] = $row;
            }
        }
        echo json_encode($rows);
        break;

    case 'POST':
        $body = json_decode(file_get_contents("php://input"), true);
        if (!$body || empty($body['title'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Title is required']);
            exit;
        }

        $title = $body['title'];
        $description = $body['description'] ?? ($body['desc'] ?? null);
        $category = $body['category'] ?? 'Personal';
        $emotion = $body['emotion'] ?? 'Happy';
        $date = $body['date'] ?? date('Y-m-d');
        $worldX = isset($body['worldX']) ? (float)$body['worldX'] : (isset($body['world_x']) ? (float)$body['world_x'] : 0);
        $worldY = isset($body['worldY']) ? (float)$body['worldY'] : (isset($body['world_y']) ? (float)$body['world_y'] : 0);
        $size = isset($body['size']) ? (int)$body['size'] : 40;

        $stmt = $db->prepare("INSERT INTO memories (title, description, category, emotion, date, worldX, worldY, size) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssddi", $title, $description, $category, $emotion, $date, $worldX, $worldY, $size);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'id' => $db->insert_id]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => $stmt->error]);
        }
        $stmt->close();
        break;

    case 'PUT':
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID is required']);
            exit;
        }

        $body = json_decode(file_get_contents("php://input"), true);
        if (!$body) {
            http_response_code(400);
            echo json_encode(['error' => 'No data provided']);
            exit;
        }

        // Check if it's a POSITION ONLY update (from dragging)
        if (isset($body['worldX']) && isset($body['worldY']) && !isset($body['title'])) {
            $worldX = floatval($body['worldX']);
            $worldY = floatval($body['worldY']);

            $stmt = $db->prepare("UPDATE memories SET worldX=?, worldY=? WHERE id=?");
            $stmt->bind_param("ddi", $worldX, $worldY, $id);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => $stmt->error]);
            }
            $stmt->close();
            break;
        }

        // Full update (from modal)
        $title = $body['title'] ?? null;
        if (!$title) {
            http_response_code(400);
            echo json_encode(['error' => 'Title is required for full update']);
            exit;
        }

        $description = $body['description'] ?? ($body['desc'] ?? null);
        $category    = $body['category'] ?? 'Personal';
        $emotion     = $body['emotion']  ?? 'Happy';
        $date        = $body['date']     ?? date('Y-m-d');
        $worldX      = isset($body['worldX']) ? (float)$body['worldX'] : (isset($body['world_x']) ? (float)$body['world_x'] : 0);
        $worldY      = isset($body['worldY']) ? (float)$body['worldY'] : (isset($body['world_y']) ? (float)$body['world_y'] : 0);
        $size        = isset($body['size']) ? (int)$body['size'] : 40;

        $stmt = $db->prepare("UPDATE memories SET title=?, description=?, category=?, emotion=?, date=?, worldX=?, worldY=?, size=? WHERE id=?");
        $stmt->bind_param("sssssddii", $title, $description, $category, $emotion, $date, $worldX, $worldY, $size, $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => $stmt->error]);
        }
        $stmt->close();
        break;

    case 'DELETE':
        if (!$id) {
            http_response_code(400);
            exit;
        }
        $stmt = $db->prepare("DELETE FROM memories WHERE id=?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        }
        $stmt->close();
        break;
}

$db->close();
