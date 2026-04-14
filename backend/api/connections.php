<?php
// ─────────────────────────────────────────
//  api/connections.php
//  Handles: GET / POST
//  GET  — returns all memory connections
//  POST — creates a new connection (no duplicates)
// ─────────────────────────────────────────

require_once __DIR__ . '/../config/db.php';

// ── CORS headers ──
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Pre-flight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// ── Open DB ──
$db = get_db();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    // ────────────────────────────────────────────
    //  GET /api/connections
    //  Returns all connections as [{id, from_id, to_id}, ...]
    // ────────────────────────────────────────────
    case 'GET':
        $result = $db->query(
            'SELECT id, from_id, to_id, created_at FROM connections'
        );

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $row['id']      = (int) $row['id'];
            $row['from_id'] = (int) $row['from_id'];
            $row['to_id']   = (int) $row['to_id'];
            $rows[] = $row;
        }

        echo json_encode($rows);
        break;


    // ────────────────────────────────────────────
    //  POST /api/connections
    //  Body (JSON): { "from": 1, "to": 2 }
    //  Ignores duplicates — returns { exists: true }
    //  if the connection already exists either way.
    // ────────────────────────────────────────────
    case 'POST':
        $body = json_decode(file_get_contents('php://input'), true);

        $from = isset($body['from']) ? (int) $body['from'] : 0;
        $to   = isset($body['to'])   ? (int) $body['to']   : 0;

        if (!$from || !$to) {
            http_response_code(400);
            echo json_encode(['error' => '"from" and "to" memory IDs are required']);
            exit;
        }

        if ($from === $to) {
            http_response_code(400);
            echo json_encode(['error' => 'A memory cannot connect to itself']);
            exit;
        }

        // Check if this connection already exists in either direction
        $stmt = $db->prepare(
            'SELECT id FROM connections
             WHERE (from_id=? AND to_id=?)
                OR (from_id=? AND to_id=?)'
        );
        $stmt->bind_param('iiii', $from, $to, $to, $from);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Already exists — return gracefully
            $stmt->close();
            echo json_encode(['exists' => true]);
            exit;
        }
        $stmt->close();

        // Insert the new connection
        $stmt = $db->prepare(
            'INSERT INTO connections (from_id, to_id) VALUES (?, ?)'
        );
        $stmt->bind_param('ii', $from, $to);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(['id' => $db->insert_id]);
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
