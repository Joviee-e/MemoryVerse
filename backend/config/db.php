<?php
// ─────────────────────────────────────────
//  config/db.php  — Database connection
//  Returns a live mysqli connection object.
//  Include this file in every API endpoint.
// ─────────────────────────────────────────

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // ← change this
define('DB_NAME', 'memoryverse');

/**
 * Returns a mysqli connection.
 * Kills the script with a JSON error if the connection fails.
 */
function get_db(): mysqli {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode([
            'error' => 'Database connection failed: ' . $conn->connect_error
        ]);
        exit;
    }

    // Always use UTF-8
    $conn->set_charset('utf8mb4');

    return $conn;
}
