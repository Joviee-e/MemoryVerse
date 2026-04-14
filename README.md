# MemoryVerse — PHP Backend

## File Structure
```
memoryverse-backend/
├── config/
│   └── db.php           ← DB credentials & connection helper
├── api/
│   ├── memories.php     ← GET / POST / PUT / DELETE
│   └── connections.php  ← GET / POST
├── schema.sql           ← Run once to create the database
└── README.md
```

## Setup (5 steps)

**1. Create the database**
```bash
mysql -u root -p < schema.sql
```

**2. Set your credentials**
Open `config/db.php` and update:
```php
define('DB_PASS', 'your_password');
```

**3. Place files on your server**
Copy the entire folder into your web root (e.g. `htdocs/` or `www/`):
```
htdocs/
└── memoryverse-backend/
    ├── config/db.php
    ├── api/memories.php
    └── api/connections.php
```

**4. Open the frontend**
Open `memory.html` in your browser directly (or via localhost).

**5. Update the API base URL in memory.html**
Search for `API_BASE` or the fetch URLs inside the HTML and set them to:
```
http://localhost/memoryverse-backend/api/
```

---

## API Reference

### Memories

| Method | URL | Body | Response |
|--------|-----|------|----------|
| GET | `/api/memories.php` | — | `[{id, title, ...}]` |
| POST | `/api/memories.php` | `{title, desc, category, emotion, date, worldX, worldY, size}` | `{id}` |
| PUT | `/api/memories.php?id=1` | same as POST (no size) | `{ok: true}` |
| DELETE | `/api/memories.php?id=1` | — | `{ok: true}` |

### Connections

| Method | URL | Body | Response |
|--------|-----|------|----------|
| GET | `/api/connections.php` | — | `[{id, from_id, to_id}]` |
| POST | `/api/connections.php` | `{from, to}` | `{id}` or `{exists: true}` |

---

## Notes
- All responses are JSON with `Content-Type: application/json`
- CORS is open (`*`) — fine for a college project; restrict in production
- All queries use **prepared statements** (safe from SQL injection)
- Deleting a memory also removes its connections (FK CASCADE + explicit DELETE)
