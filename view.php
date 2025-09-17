<?php
require_once 'auth.php';
require_once 'database.php';
require_once 'functions.php';

// Vérifier les droits de visualisation
if (!isLoggedIn('view') && !isLoggedIn('admin')) {
    requireLogin();
}

$db = new Database();
$messages = $db->getApprovedMessages();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Mur d'Expression</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👁️ Messages Publics</h1>
            <p>Tous les messages approuvés par ordre chronologique</p>
            <a href="?logout=1" class="logout-btn">Déconnexion</a>
        </div>
        
        <div class="content">
            <div class="navigation">
                <a href="index.php" class="btn">🏠 Accueil</a>
                <?php if (isLoggedIn('admin')): ?>
                    <a href="admin.php" class="btn btn-warning">🛠️ Administration</a>
                <?php endif; ?>
            </div>
            
            <h2>💬 Messages partagés (<?= count($messages) ?>)</h2>
            
            <?php if (empty($messages)): ?>
                <div class="explanation">
                    <p>Aucun message public pour le moment.</p>
                </div>
            <?php else: ?>
                <?php foreach ($messages as $message): ?>
                    <div class="message">
                        <div class="message-header">
                            <div class="message-author">
                                <?= htmlspecialchars($message['author']) ?>
                            </div>
                            <div class="message-date">
                                <?= formatDate($message['created_at']) ?>
                            </div>
                        </div>
                        
                        <div class="message-content">
                            <?= displayHTML($message['message']) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
