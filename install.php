<?php
// Script d'installation automatique
echo "<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <title>Installation - Mur d'Expression</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { color: green; } .error { color: red; } .warning { color: orange; }
        .step { background: #f5f5f5; padding: 15px; margin: 10px 0; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>🚀 Installation du Mur d'Expression</h1>";

$errors = [];
$warnings = [];
$success = [];

// Vérification des extensions PHP
echo "<div class='step'><h3>1. Vérification des prérequis</h3>";

if (extension_loaded('sqlite3')) {
    $success[] = "✅ Extension SQLite3 disponible";
    echo "<p class='success'>Extension SQLite3 : OK</p>";
} else {
    $errors[] = "❌ Extension SQLite3 manquante";
    echo "<p class='error'>Extension SQLite3 : ERREUR - Extension manquante</p>";
}

if (version_compare(PHP_VERSION, '7.4.0') >= 0) {
    $success[] = "✅ Version PHP compatible (" . PHP_VERSION . ")";
    echo "<p class='success'>Version PHP : OK (" . PHP_VERSION . ")</p>";
} else {
    $warnings[] = "⚠️ Version PHP ancienne (" . PHP_VERSION . ") - Recommandé: 7.4+";
    echo "<p class='warning'>Version PHP : " . PHP_VERSION . " (recommandé 7.4+)</p>";
}
echo "</div>";

// Vérification des permissions
echo "<div class='step'><h3>2. Vérification des permissions</h3>";

if (!is_dir('data')) {
    if (mkdir('data', 0777, true)) {
        $success[] = "✅ Dossier 'data' créé";
        echo "<p class='success'>Dossier 'data' : Créé avec succès</p>";
    } else {
        $error = error_get_last(); // Récupère la dernière erreur PHP
        $errors[] = "❌ Impossible de créer le dossier 'data' : " . $error['message'];
        echo "<p class='error'>Dossier 'data' : Impossible de créer<br>";
        echo "<small>Détail : " . htmlspecialchars($error['message']) . "</small></p>";
    }
} else {
    echo "<p class='success'>Dossier 'data' : Déjà existant</p>";
}

if (is_writable('.')) {
    $success[] = "✅ Répertoire principal accessible en écriture";
    echo "<p class='success'>Permissions répertoire : OK</p>";
} else {
    $testfile = './.writetest_' . uniqid();
    $canWrite = @file_put_contents($testfile, "test");
    if ($canWrite === false) {
        $error = error_get_last();
        $errors[] = "❌ Répertoire principal non accessible en écriture : " . $error['message'];
        echo "<p class='error'>Permissions répertoire : ERREUR<br>";
        echo "<small>Détail : " . htmlspecialchars($error['message']) . "</small></p>";
    } else {
        // If we somehow could write despite is_writable being false, clean up
        unlink($testfile);
        $errors[] = "⚠️ Incohérence : is_writable() a retourné false mais l’écriture a réussi.";
        echo "<p class='error'>Permissions répertoire : Incohérence détectée</p>";
    }
}


echo "</div>";

// Test de la base de données
echo "<div class='step'><h3>3. Initialisation de la base de données</h3>";
if (empty($errors)) {
    try {
        require_once 'database.php';
        $db = new Database();
        $success[] = "✅ Base de données initialisée";
        echo "<p class='success'>Base de données : Initialisée avec succès</p>";
    } catch (Exception $e) {
        $errors[] = "❌ Erreur base de données: " . $e->getMessage();
        echo "<p class='error'>Base de données : ERREUR - " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p class='warning'>Base de données : Test ignoré (erreurs précédentes)</p>";
}
echo "</div>";

// Vérification des fichiers
echo "<div class='step'><h3>4. Vérification des fichiers</h3>";
$required_files = ['config.php', 'functions.php', 'auth.php', 'database.php', 'index.php', 'admin.php', 'view.php', 'styles.css'];

foreach ($required_files as $file) {
    if (file_exists($file)) {
        echo "<p class='success'>$file : ✅</p>";
    } else {
        $errors[] = "❌ Fichier manquant: $file";
        echo "<p class='error'>$file : ❌ MANQUANT</p>";
    }
}
echo "</div>";

// Résumé
echo "<div class='step'><h3>📋 Résumé de l'installation</h3>";

if (empty($errors)) {
    echo "<div style='background: #d4edda; padding: 20px; border-radius: 5px; margin: 20px 0;'>
            <h4 style='color: #155724; margin: 0 0 10px 0;'>🎉 Installation réussie !</h4>
            <p>Votre mur d'expression est prêt à être utilisé.</p>
            <p><strong>Mots de passe par défaut :</strong></p>
            <ul>
                <li>Poster des messages : <code>expression2024</code></li>
                <li>Administration : <code>admin2024</code></li>
                <li>Lecture seule : <code>view2024</code></li>
            </ul>
            <p><strong>⚠️ N'oubliez pas de :</strong></p>
            <ul>
                <li>Modifier les mots de passe dans <code>config.php</code></li>
                <li>Supprimer ce fichier <code>install.php</code> pour la sécurité</li>
                <li>Configurer votre serveur web (voir .htaccess)</li>
            </ul>
            <p><a href='index.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🚀 Accéder au site</a></p>
          </div>";
} else {
    echo "<div style='background: #f8d7da; padding: 20px; border-radius: 5px; margin: 20px 0;'>
            <h4 style='color: #721c24; margin: 0 0 10px 0;'>❌ Erreurs détectées</h4>";
    foreach ($errors as $error) {
        echo "<p>$error</p>";
    }
    echo "<p>Veuillez corriger ces erreurs avant de continuer.</p>
          </div>";
}

if (!empty($warnings)) {
    echo "<div style='background: #fff3cd; padding: 20px; border-radius: 5px; margin: 20px 0;'>
            <h4 style='color: #856404; margin: 0 0 10px 0;'>⚠️ Avertissements</h4>";
    foreach ($warnings as $warning) {
        echo "<p>$warning</p>";
    }
    echo "</div>";
}

echo "</div>";
echo "</body></html>";
?>
