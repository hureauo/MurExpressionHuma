<?php
require_once 'auth.php';
require_once 'database.php';
require_once 'functions.php';

$db = new Database();
$success_message = '';
$error_message = '';

// Traitement du nouveau message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_message']) && isLoggedIn('public')) {
    $author = sanitizeMessage($_POST['author'] ?? '');
    $message = sanitizeMessage($_POST['message'] ?? '');
    
    if (!validateMessage($message)) {
        $error_message = "Le message est vide ou trop long (" . MAX_MESSAGE_LENGTH . " caractères maximum).";
    } elseif (!validateAuthor($author)) {
        $error_message = "Le nom d'auteur est trop long (" . MAX_AUTHOR_LENGTH . " caractères maximum).";
    } else {
        $author = !empty($author) ? $author : 'Anonyme';
        
        if ($db->addMessage($author, sanitizeHTML($message))) {
            $success_message = "Votre message a été envoyé avec succès. 
            </br>Bien que cela ne soit pas nécessaire, vous pouvez contacter votre personne de confiance pour vérifier.";
        } else {
            $error_message = "Erreur lors de la publication du message.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retours et expressions personnels</title>
    <link rel="stylesheet" href="styles.css">
</head>

<?php if (isLoggedIn('public')): ?>
                  
                  <!-- Messages d'alerte -->
                  <?php if ($success_message): ?>
                      <div class="alert alert-success"><?= $success_message ?></div>
                  <?php endif; ?>
                  
                  <?php if ($error_message): ?>
                      <div class="alert alert-error"><?= $error_message ?></div>
                  <?php endif; ?>

  <?php endif; ?>


<body>
    <div class="container">

     

        <div class="header">
            <h1>S'exprimer. </h1>
            Un espace d'expression afin de faire entendre sa voix, exprimer son point de vue.

            </p>
            <?php if (isLoggedIn()): ?>
                <a href="?logout=1" class="logout-btn">Déconnexion</a>
            <?php endif; ?>
        </div>
        
        <div class="content">
            
                <!-- Explication du site -->
                <div class="explanation">
                    <h1> Objectifs </h1>
                    <p>Ce site a été créé suite aux événements récents pour vous offrir un espace sûr où vous pouvez :</p>
                    <ul style="margin: 15px 0; padding-left: 30px;">
                        <li> <strong> Exprimez votre ressenti </strong> par rapport au stand de la guinguette alpine à la fête de l'Humanitée. </li>
                        <li><strong>Partager vos émotions</strong> : Exprimez librement ce que vous ressentez</li>
                        <li><strong>Être entendus</strong> : Vos messages seront lus et pris au sérieux. </li>

                 </ul>
     		  </br></br> L'écriture des messages sera fermé à date de <à définir>.

               
        	</br>    Le mot de passe d'accès à tout les messages vous sera mis à disposition dès la date du <à définir>
	                    </div>
                    
                    

                <div class="regles">
                    <h1> Règles </h1>
                        <li> <strong>Restez respectueux envers les autres participant·es</strong> </li> 
                        <li> Évitez les <strong>attaques personnelles</strong> ou les propos <strong>discriminatoires</strong> </li>
                        <li><strong>Ne réagissez pas au messages des autres ! </strong> Dans un but de bienveillance, merci de ne pas réagir aux autres messages.
                        L'objectif étant de s'exprimer sur <b> SON </b> ressentie de la fête et de faire un retour avec <b> SON </b> point de vue. </li>
                        <li><strong>Rester anonyme</strong> : Vous pouvez poster sans dévoiler votre identité</li>
                        
                        </ul>

            </br>
                    <p><em>Cet espace est (et sera) modéré par Olivier et <à définir> pour maintenir un environnement respectueux.
      
            </br> Les messages inappropriés seront supprimés (attaques directes, discriminations, messages envoyé plusieurs fois)</em></p>
                </div>

            <?php if (!isLoggedIn()): ?>

                <!-- Formulaire de connexion -->
                <div class="login-form">
                    <h3>🔐 Connexion</h3>
                    <p>Entrez le mot de passe qui vous a été communiqué :</p>
                    
                    <?php if (isset($login_error)): ?>
                        <div class="alert alert-error"><?= $login_error ?></div>
                    <?php endif; ?>
                    
                    <form method="post">
                        <div class="form-group">
                            <label for="password">Mot de passe :</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        <button type="submit" name="login">Se connecter</button>
                    </form>
                </div>
                
            <?php else: ?>
                <!-- Navigation pour les utilisateurs connectés -->
                <div class="navigation">
                    <?php if (isLoggedIn('admin')): ?>
                        <a href="admin.php" class="btn btn-warning">🛠️ Administration</a>
                    <?php endif; ?>
                    <?php if (isLoggedIn('view') || isLoggedIn('admin')): ?>
                        <a href="view.php" class="btn btn-success">👁️ Voir les messages</a>
                    <?php endif; ?>
                </div>
                
                <?php if (isLoggedIn('public')): ?>
                  
                
        
                    
                    <!-- Formulaire de nouveau message -->
                    <form method="post" class="message-form" onsubmit="return submitForm()">
                        <div class="form-group">
                            <label for="author">Votre nom (optionnel - laissez vide pour rester anonyme) :</label>
                            <input type="text" id="author" name="author" maxlength="<?= MAX_AUTHOR_LENGTH ?>" placeholder="Anonyme">
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Votre message * :</label>
                            <div class="rich-editor">
                                <div class="editor-toolbar">
                                    <button type="button" class="editor-btn" data-command="bold" title="Gras"><strong>G</strong></button>
                                    <button type="button" class="editor-btn" data-command="italic" title="Italique"><em>I</em></button>
                                    <button type="button" class="editor-btn" data-command="underline" title="Souligné"><u>S</u></button>
                                    <button type="button" class="editor-btn" data-command="strikeThrough" title="Barré">B̶</button>
                                    <span style="border-left: 1px solid #ccc; margin: 0 5px;"></span>
                                    
                                    <button type="button" class="editor-btn" data-command="insertUnorderedList" title="Liste à puces">• Liste</button>
                                    <button type="button" class="editor-btn" data-command="insertOrderedList" title="Liste numérotée">1. Liste</button>
                                    <button type="button" class="editor-btn" data-command="formatBlock" data-value="blockquote" title="Citation">❝ Citation</button>
                                    <span style="border-left: 1px solid #ccc; margin: 0 5px;"></span>
                                    
                            
                                </div>
                                <div class="editor-content" 
                                     contenteditable="true" 
                                     id="editorContent"
                                     onpaste="handlePaste(event)"
                                     oninput="updateCharCount()">
                                </div>
                            </div>
                            <input type="hidden" id="message" name="message" required>
                            <small style="color: #666;">
                                <span id="charCount">0</span> / <?= MAX_MESSAGE_LENGTH ?> caractères
                            </small>
                        </div>
                        
                        <button type="submit" name="new_message">📤 Publier mon message</button>
                    </form>
                    
                    <script>
                    // Variables globales
                    const editor = document.getElementById('editorContent');
                    const messageInput = document.getElementById('message');
                    const charCountSpan = document.getElementById('charCount');
                    const maxLength = <?= MAX_MESSAGE_LENGTH ?>;
                    
                    // Initialisation de l'éditeur
                    document.addEventListener('DOMContentLoaded', function() {
                        // Gestion des boutons de la barre d'outils
                        document.querySelectorAll('.editor-btn[data-command]').forEach(btn => {
                            btn.addEventListener('click', function(e) {
                                e.preventDefault();
                                const command = this.dataset.command;
                                const value = this.dataset.value || null;
                                
                                document.execCommand(command, false, value);
                                editor.focus();
                                updateButtonStates();
                            });
                        });
                        
                        // Gestion du sélecteur de couleurs
                        const colorBtn = document.getElementById('colorBtn');
                        const colorPicker = document.getElementById('colorPicker');
                        
                        colorBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            colorPicker.classList.toggle('active');
                        });
                        
                        // Fermer le sélecteur de couleurs en cliquant ailleurs
                        document.addEventListener('click', function(e) {
                            if (!e.target.closest('#colorBtn') && !e.target.closest('#colorPicker')) {
                                colorPicker.classList.remove('active');
                            }
                        });
                        
                        // Gestion des couleurs
                        document.querySelectorAll('.color-option').forEach(option => {
                            option.addEventListener('click', function() {
                                const color = this.dataset.color;
                                document.execCommand('foreColor', false, color);
                                colorPicker.classList.remove('active');
                                editor.focus();
                            });
                        });
                        
                        // Mise à jour des états des boutons lors de la sélection
                        editor.addEventListener('mouseup', updateButtonStates);
                        editor.addEventListener('keyup', updateButtonStates);
                        
                        // Mise à jour du compteur de caractères
                        editor.addEventListener('input', updateCharCount);
                        
                        updateCharCount();
                    });
                    
                    // Mise à jour des états des boutons
                    function updateButtonStates() {
                        document.querySelectorAll('.editor-btn[data-command]').forEach(btn => {
                            const command = btn.dataset.command;
                            const isActive = document.queryCommandState(command);
                            btn.classList.toggle('active', isActive);
                        });
                    }
                    
                    // Mise à jour du compteur de caractères
                    function updateCharCount() {
                        const text = editor.textContent || editor.innerText || '';
                        const count = text.length;
                        charCountSpan.textContent = count;
                        
                        if (count > maxLength) {
                            charCountSpan.style.color = '#e74c3c';
                        } else if (count > maxLength * 0.9) {
                            charCountSpan.style.color = '#f39c12';
                        } else {
                            charCountSpan.style.color = '#666';
                        }
                    }
                    
                    // Gestion du collage pour nettoyer le HTML
                    function handlePaste(e) {
                        e.preventDefault();
                        const text = e.clipboardData.getData('text/plain');
                        document.execCommand('insertText', false, text);
                        updateCharCount();
                    }
                    
                    // Insertion de liens
                    function insertLink() {
                        const url = prompt('Entrez l\'URL du lien :');
                        if (url) {
                            const selection = window.getSelection();
                            if (selection.rangeCount > 0 && !selection.isCollapsed) {
                                document.execCommand('createLink', false, url);
                            } else {
                                const text = prompt('Entrez le texte du lien :', url);
                                if (text) {
                                    const link = `<a href="${url}" target="_blank">${text}</a>`;
                                    document.execCommand('insertHTML', false, link);
                                }
                            }
                        }
                        editor.focus();
                    }
                    
                    // Soumission du formulaire
                    function submitForm() {
                        const content = editor.innerHTML;
                        const textContent = editor.textContent || editor.innerText || '';
                        
                        if (textContent.trim() === '') {
                            alert('Veuillez saisir un message.');
                            return false;
                        }
                        
                        if (textContent.length > maxLength) {
                            alert(`Votre message est trop long (${textContent.length} caractères). Limite : ${maxLength} caractères.`);
                            return false;
                        }
                        
                        messageInput.value = content;
                        return true;
                    }
                    </script>
                    
                <?php else: ?>
                    <div class="explanation">
                        <h2>👋 Bienvenue !</h2>
                        <p>Vous êtes connecté avec un accès 
                        <?php if (isLoggedIn('admin')): ?>
                            <strong>administrateur</strong>. Vous pouvez modérer les messages et consulter tous les contenus.
                        <?php elseif (isLoggedIn('view')): ?>
                            <strong>lecture seule</strong>. Vous pouvez consulter tous les messages approuvés.
                        <?php endif; ?>
                        </p>
                        <p>Utilisez la navigation ci-dessus pour accéder aux différentes fonctions.</p>
                    </div>
                <?php endif; ?>
                
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
