<?php
// Configuration générale
define('DB_PATH', 'data/expression_wall.db');
define('PUBLIC_PASSWORD', 'post'); // Mot de passe pour poster
define('ADMIN_PASSWORD', 'admin'); // Mot de passe administrateur
define('VIEW_PASSWORD', 'read'); // Mot de passe pour voir tous les messages

// Paramètres de sécurité
define('SESSION_TIMEOUT', 3600); // 1 heure
define('MAX_MESSAGE_LENGTH', 5000);
define('MAX_AUTHOR_LENGTH', 50);
?>
