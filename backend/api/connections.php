<?php
/**
 * api/connections.php
 * Handles: GET / POST
 */

require_once __DIR__ . '/../config/db.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$db = get_db();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Task: SELECT id, from_id, to_id FROM connections
        $query = "SELECT id, from_id, to_id FROM connections";
        $result = $db->query($query);
        
        $rows = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $row['id'] = (int)$row['id'];
                $row['from_id'] = (int)$row['from_id'];
                $row['to_id'] = (int)$row['to_id'];
                $rows[] = $row;
            }
        }
        echo json_encode($rows);
        break;

    case 'POST':
        $body = json_decode(file_get_contents("php://input"), true);
        
        // Accept from_id/to_id from JSON body
        $from_id = isset($body['from_id']) ? (int)$body['from_id'] : (isset($body['from']) ? (int)$body['from'] : 0);
        $to_id   = isset($body['to_id']) ? (int)$body['to_id'] : (isset($body['to']) ? (int)$body['to'] : 0);

        if (!$from_id || !$to_id) {
            http_response_code(400);
            echo json_encode(['error' => 'Valid from_id and to_id are required']);
            exit;
        }

        // Check if exists either way
        $stmt = $db->prepare("SELECT id FROM connections WHERE (from_id=? AND to_id=?) OR (from_id=? AND to_id=?)");
        $stmt->bind_param("iiii", $from_id, $to_id, $to_id, $from_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->close();
            echo json_encode(['success' => true, 'exists' => true]);
            exit;
        }
        $stmt->close();

        // Insert
        $stmt = $db->prepare("INSERT INTO connections (from_id, to_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $from_id, $to_id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'id' => $db->insert_id]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => $stmt->error]);
        }
        $stmt->close();
        break;

    default:
        http_response_code(405);
        break;
}

$db->close();
