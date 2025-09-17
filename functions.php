<?php
// Fonction pour échapper le HTML mais conserver le BBCode
function sanitizeMessage($message) {
    return htmlspecialchars(trim($message), ENT_QUOTES, 'UTF-8');
}

// Fonction pour nettoyer et sécuriser le HTML de l'éditeur
function sanitizeHTML($html) {
    // Liste des balises autorisées
    $allowed_tags = '<p><br><strong><em><u><s><blockquote><ul><ol><li><a><span><div>';
    
    // Nettoyer le HTML
    $html = strip_tags($html, $allowed_tags);
    
    // Nettoyer les attributs dangereux
    $html = preg_replace('/(<[^>]+)(on\w+\s*=\s*["\'][^"\']*["\'])/i', '$1', $html);
    $html = preg_replace('/(<[^>]+)(javascript\s*:)/i', '$1', $html);
    $html = preg_replace('/(<[^>]+)(data\s*:)/i', '$1', $html);
    
    // Nettoyer les styles dangereux
    $html = preg_replace('/(<[^>]+style\s*=\s*["\'][^"\']*)(expression\s*\(|behavior\s*:|@import|javascript\s*:)/i', '$1', $html);
    
    return $html;
}

// Fonction pour afficher le HTML sécurisé
function displayHTML($html) {
    return sanitizeHTML($html);
}

// Vérification des mots de passe
function checkPassword($type, $password) {
    switch($type) {
        case 'public':
            return $password === PUBLIC_PASSWORD;
        case 'admin':
            return $password === ADMIN_PASSWORD;
        case 'view':
            return $password === VIEW_PASSWORD;
        default:
            return false;
    }
}

// Formatage des dates
function formatDate($datetime) {
    return date('d/m/Y à H:i', strtotime($datetime));
}

// Validation des données
function validateMessage($message) {
    return !empty(trim($message)) && strlen($message) <= MAX_MESSAGE_LENGTH;
}

function validateAuthor($author) {
    return strlen($author) <= MAX_AUTHOR_LENGTH;
}
?>
