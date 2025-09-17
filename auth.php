<?php
require_once 'config.php';
require_once 'functions.php';

// Démarrer la session
session_start();

// Vérifier le timeout de session
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
    session_unset();
    session_destroy();
    session_start();
}
$_SESSION['last_activity'] = time();

// Traitement des connexions
if (isset($_POST['login'])) {
    $password = $_POST['password'] ?? '';
    
    if (checkPassword('public', $password)) {
        $_SESSION['logged_in'] = 'public';
        $_SESSION['login_time'] = time();
    } elseif (checkPassword('admin', $password)) {
        $_SESSION['logged_in'] = 'admin';
        $_SESSION['login_time'] = time();
    } elseif (checkPassword('view', $password)) {
        $_SESSION['logged_in'] = 'view';
        $_SESSION['login_time'] = time();
    } else {
        $login_error = "Mot de passe incorrect";
    }
}

// Déconnexion
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit;
}

// Vérifier si l'utilisateur est connecté
function isLoggedIn($level = null) {
    if (!isset($_SESSION['logged_in'])) {
        return false;
    }
    
    if ($level === null) {
        return true;
    }
    
    return $_SESSION['logged_in'] === $level;
}

// Rediriger si pas connecté
function requireLogin($level = null) {
    if (!isLoggedIn($level)) {
        header('Location: index.php');
        exit;
    }
}
?>
