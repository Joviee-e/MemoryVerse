-- ─────────────────────────────────────────
--  schema.sql  — MemoryVerse database setup
--  Run once:  mysql -u root -p < schema.sql
-- ─────────────────────────────────────────

CREATE DATABASE IF NOT EXISTS memoryverse
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE memoryverse;

-- ── Memories table ──
CREATE TABLE IF NOT EXISTS memories (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  title       VARCHAR(255) NOT NULL,
  description TEXT,
  category    ENUM('Career','Travel','Education','Personal') DEFAULT 'Personal',
  emotion     ENUM('Happy','Excited','Achievement','Sad','Love','Peaceful') DEFAULT 'Happy',
  date        DATE,
  worldX      FLOAT,         -- canvas X position
  worldY      FLOAT,         -- canvas Y position
  size        INT DEFAULT 40, -- planet radius in px
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ── Connections table ──
--  FK CASCADE means deleting a memory auto-removes its connections.
CREATE TABLE IF NOT EXISTS connections (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  from_id    INT NOT NULL,
  to_id      INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (from_id) REFERENCES memories(id) ON DELETE CASCADE,
  FOREIGN KEY (to_id)   REFERENCES memories(id) ON DELETE CASCADE,
  UNIQUE KEY unique_conn (from_id, to_id)  -- prevents duplicate pairs
);
