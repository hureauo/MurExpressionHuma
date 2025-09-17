<?php
require_once 'config.php';

class Database {
    private $db;
    
    public function __construct() {
        // Créer le dossier data s'il n'existe pas
        if (!is_dir('data')) {
            mkdir('data', 0755, true);
        }
        
        $this->db = new SQLite3(DB_PATH);
        $this->initTables();
    }
    
    private function initTables() {
        $this->db->exec("CREATE TABLE IF NOT EXISTS messages (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            author VARCHAR(255),
            message TEXT NOT NULL,
            created_at DATETIME DEFAULT (datetime('now','localtime')),
            is_approved INTEGER DEFAULT 1
        )");
    }
    
    public function addMessage($author, $message) {
        $stmt = $this->db->prepare("INSERT INTO messages (author, message) VALUES (?, ?)");
        $stmt->bindValue(1, $author, SQLITE3_TEXT);
        $stmt->bindValue(2, $message, SQLITE3_TEXT);
        return $stmt->execute();
    }
    
    public function getAllMessages() {
        $result = $this->db->query("SELECT * FROM messages ORDER BY created_at DESC");
        $messages = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $messages[] = $row;
        }
        return $messages;
    }
    
    public function getApprovedMessages() {
        $result = $this->db->query("SELECT * FROM messages WHERE is_approved = 1 ORDER BY created_at DESC");
        $messages = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $messages[] = $row;
        }
        return $messages;
    }
    
    public function moderateMessage($id, $action) {
        switch($action) {
            case 'approve':
                $stmt = $this->db->prepare("UPDATE messages SET is_approved = 1 WHERE id = ?");
                break;
            case 'reject':
                $stmt = $this->db->prepare("UPDATE messages SET is_approved = 0 WHERE id = ?");
                break;
            case 'delete':
                $stmt = $this->db->prepare("DELETE FROM messages WHERE id = ?");
                break;
            default:
                return false;
        }
        
        $stmt->bindValue(1, $id, SQLITE3_INTEGER);
        return $stmt->execute();
    }
}
