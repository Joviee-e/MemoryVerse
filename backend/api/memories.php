<?php
// ─────────────────────────────────────────
//  api/memories.php
//  Handles: GET / POST / PUT / DELETE
//  Routes via the HTTP method + ?id= param
// ─────────────────────────────────────────

require_once __DIR__ . '/../config/db.php';

// ── CORS headers (allow the HTML frontend to call this) ──
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Pre-flight OPTIONS request — browsers send this before PUT/DELETE
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// ── Open DB ──
$db = get_db();

// ── Route by HTTP method ──
$method = $_SERVER['REQUEST_METHOD'];
$id     = isset($_GET['id']) ? (int) $_GET['id'] : null;

switch ($method) {

    // ────────────────────────────────────────────
    //  GET /api/memories
    //  Returns all memories ordered by date DESC
    // ────────────────────────────────────────────
    case 'GET':
        $result = $db->query(
            'SELECT id, title, description, category, emotion,
                    date, world_x, world_y, size, created_at
             FROM memories
             ORDER BY date DESC'
        );

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            // Cast numeric fields so JS gets numbers, not strings
            $row['id']      = (int)   $row['id'];
            $row['world_x'] = $row['world_x'] !== null ? (float) $row['world_x'] : null;
            $row['world_y'] = $row['world_y'] !== null ? (float) $row['world_y'] : null;
            $row['size']    = (int)   $row['size'];
            $rows[] = $row;
        }

        echo json_encode($rows);
        break;


    // ────────────────────────────────────────────
    //  POST /api/memories
    //  Body (JSON): title, desc, category, emotion,
    //               date, worldX, worldY, size
    // ────────────────────────────────────────────
    case 'POST':
        $body = json_decode(file_get_contents('php://input'), true);

        // Basic validation — title is the only required field
        if (empty($body['title'])) {
            http_response_code(400);
            echo json_encode(['error' => 'title is required']);
            exit;
        }

        $stmt = $db->prepare(
            'INSERT INTO memories
               (title, description, category, emotion, date, world_x, world_y, size)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );

        $title    = $body['title'];
        $desc     = $body['desc']     ?? null;
        $category = $body['category'] ?? 'Personal';
        $emotion  = $body['emotion']  ?? 'Happy';
        $date     = $body['date']     ?? null;
        $worldX   = isset($body['worldX']) ? (float) $body['worldX'] : null;
        $worldY   = isset($body['worldY']) ? (float) $body['worldY'] : null;
        $size     = isset($body['size'])   ? (int)   $body['size']   : 40;

        // bind_param types: s=string, d=double, i=integer
        $stmt->bind_param(
            'ssssssddi',
            $title, $desc, $category, $emotion, $date, $worldX, $worldY, $size
        );

        // Fix: correct bind_param type string (8 params → 8 type chars)
        $stmt->close();
        $stmt = $db->prepare(
            'INSERT INTO memories
               (title, description, category, emotion, date, world_x, world_y, size)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->bind_param('sssssddi', $title, $desc, $category, $emotion, $date, $worldX, $worldY, $size);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(['id' => $db->insert_id]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => $stmt->error]);
        }

        $stmt->close();
        break;


    // ────────────────────────────────────────────
    //  PUT /api/memories?id=1
    //  Body (JSON): title, desc, category, emotion,
    //               date, worldX, worldY
    // ────────────────────────────────────────────
    case 'PUT':
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'id query param is required']);
            exit;
        }

        $body = json_decode(file_get_contents('php://input'), true);

        if (empty($body['title'])) {
            http_response_code(400);
            echo json_encode(['error' => 'title is required']);
            exit;
        }

        $stmt = $db->prepare(
            'UPDATE memories
             SET title=?, description=?, category=?, emotion=?,
                 date=?, world_x=?, world_y=?
             WHERE id=?'
        );

        $title    = $body['title'];
        $desc     = $body['desc']     ?? null;
        $category = $body['category'] ?? 'Personal';
        $emotion  = $body['emotion']  ?? 'Happy';
        $date     = $body['date']     ?? null;
        $worldX   = isset($body['worldX']) ? (float) $body['worldX'] : null;
        $worldY   = isset($body['worldY']) ? (float) $body['worldY'] : null;

        $stmt->bind_param('ssssssddi', $title, $desc, $category, $emotion, $date, $worldX, $worldY, $id);

        if ($stmt->execute()) {
            echo json_encode(['ok' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => $stmt->error]);
        }

        $stmt->close();
        break;


    // ────────────────────────────────────────────
    //  DELETE /api/memories?id=1
    //  Also removes all connections for that memory
    //  (the FK CASCADE handles it automatically, but
    //   we delete explicitly for clarity)
    // ────────────────────────────────────────────
    case 'DELETE':
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'id query param is required']);
            exit;
        }

        // Remove linked connections first (in case FK cascade isn't set)
        $stmt = $db->prepare(
            'DELETE FROM connections WHERE from_id=? OR to_id=?'
        );
        $stmt->bind_param('ii', $id, $id);
        $stmt->execute();
        $stmt->close();

        // Now delete the memory itself
        $stmt = $db->prepare('DELETE FROM memories WHERE id=?');
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            echo json_encode(['ok' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => $stmt->error]);
        }

        $stmt->close();
        break;


    // ── Unknown method ──
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

$db->close();
