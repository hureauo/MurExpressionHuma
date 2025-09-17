<?php
require_once 'auth.php';
require_once 'database.php';
require_once 'functions.php';

// Vérifier les droits admin
requireLogin('admin');

$db = new Database();
$success_message = '';
$error_message = '';

// Traitement de la modération
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['moderate'])) {
    $message_id = (int)$_POST['message_id'];
    $action = $_POST['action'];
    
    if ($db->moderateMessage($message_id, $action)) {
        $success_message = "Action effectuée avec succès.";
    } else {
        $error_message = "Erreur lors de l'action.";
    }
}

$messages = $db->getAllMessages();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Mur d'Expression</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🛠️ Administration</h1>
            <p>Modération des messages</p>
            <a href="?logout=1" class="logout-btn">Déconnexion</a>
        </div>
        
        <div class="content">
            <div class="navigation">
                <a href="index.php" class="btn">🏠 Accueil</a>
                <a href="view.php" class="btn btn-success">👁️ Vue publique</a>
            </div>
            
            <?php if ($success_message): ?>
                <div class="alert alert-success"><?= $success_message ?></div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="alert alert-error"><?= $error_message ?></div>
            <?php endif; ?>
            
            <h2>📋 Tous les messages (<?= count($messages) ?>)</h2>
            
            <?php if (empty($messages)): ?>
                <div class="explanation">
                    <p>Aucun message pour le moment.</p>
                </div>
            <?php else: ?>
                <?php foreach ($messages as $message): ?>
                    <div class="message <?= $message['is_approved'] ? '' : 'pending' ?>">
                        <div class="message-header">
                            <div>
                                <span class="message-author"><?= htmlspecialchars($message['author']) ?></span>
                                <span class="status-badge status-<?= $message['is_approved'] ? 'approved' : 'pending' ?>">
                                    <?= $message['is_approved'] ? '✅ Approuvé' : '⏳ En attente' ?>
                                </span>
                            </div>
                            <div class="message-date">
                                <?= formatDate($message['created_at']) ?><br>
                            </div>
                        </div>
                        
                        <div class="message-content">
                            <?= displayHTML($message['message']) ?>
                        </div>
                        
                        <div class="message-actions">
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="message_id" value="<?= $message['id'] ?>">
                                <?php if ($message['is_approved']): ?>
                                    <button type="submit" name="moderate" value="1" onclick="this.form.action.value='reject'" class="btn btn-warning">⚠️ Rejeter</button>
                                <?php else: ?>
                                    <button type="submit" name="moderate" value="1" onclick="this.form.action.value='approve'" class="btn btn-success">✅ Approuver</button>
                                <?php endif; ?>
                                <button type="submit" name="moderate" value="1" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?') && (this.form.action.value='delete', true)" class="btn btn-danger">🗑️ Supprimer</button>
                                <input type="hidden" name="action" value="">
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        // Gestion des boutons de modération
        document.querySelectorAll('button[name="moderate"]').forEach(button => {
            button.addEventListener('click', function(e) {
                const action = this.textContent.includes('Approuver') ? 'approve' : 
                              this.textContent.includes('Rejeter') ? 'reject' : 'delete';
                this.form.querySelector('input[name="action"]').value = action;
            });
        });
    </script>
</body>
</html>
