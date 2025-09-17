# 🗣️ Mur d'Expression

Un système simple et sécurisé permettant aux utilisateurs de partager leurs ressentis de manière anonyme ou nominative, avec modération.

## ✨ Fonctionnalités

- **Publication anonyme ou nominative** de messages
- **Modération** des contenus par les administrateurs
- **BBCode** pour la mise en forme des messages
- **Trois niveaux d'accès** : public, visualisation, administration
- **Protection par mots de passe** différents selon les niveaux
- **Base de données SQLite** légère et portable
- **Interface responsive** et moderne

## 🚀 Installation

1. **Téléchargez** tous les fichiers sur votre serveur web
2. **Exécutez** `install.php` dans votre navigateur
3. **Modifiez** les mots de passe dans `config.php`
4. **Supprimez** le fichier `install.php`
5. **Configurez** votre serveur (voir .htaccess)

## 🔑 Configuration

### Mots de passe (config.php)
```php
define('PUBLIC_PASSWORD', 'votre_mot_de_passe_public');
define('ADMIN_PASSWORD', 'votre_mot_de_passe_admin');
define('VIEW_PASSWORD', 'votre_mot_de_passe_lecture');
```

### Paramètres
- `MAX_MESSAGE_LENGTH` : Longueur max des messages (défaut: 2000)
- `MAX_AUTHOR_LENGTH` : Longueur max du nom d'auteur (défaut: 50)
- `SESSION_TIMEOUT` : Durée de session en secondes (défaut: 3600)

## 👥 Niveaux d'accès

1. **Public** (`PUBLIC_PASSWORD`)
   - Poster des messages
   - Rester anonyme ou se nommer
   - Utiliser la mise en forme BBCode

2. **Visualisation** (`VIEW_PASSWORD`)
   - Consulter tous les messages approuvés
   - Ordre chronologique

3. **Administration** (`ADMIN_PASSWORD`)
   - Modérer tous les messages
   - Approuver/Rejeter/Supprimer
   - Voir les adresses IP
   - Accès à toutes les fonctions

## 🎨 BBCode supporté

- `[b]texte[/b]` : **Gras**
- `[i]texte[/i]` : *Italique*
- `[u]texte[/u]` : <u>Souligné</u>
- `[s]texte[/s]` : ~~Barré~~
- `[color=couleur]texte[/color]` : Couleur
- `[size=taille]texte[/size]` : Taille
- `[center]texte[/center]` : Centré
- `[quote]citation[/quote]` : Citation
- `[quote=auteur]citation[/quote]` : Citation avec auteur
- `[url]lien[/url]` : Lien simple
- `[url=lien]texte[/url]` : Lien avec texte

## 🔒 Sécurité

- **Échappement HTML** pour prévenir les attaques XSS
- **Validation** des données d'entrée
- **Protection** de la base de données via .htaccess
- **Sessions** sécurisées avec timeout
- **Limitation** de longueur des messages
- **Journalisation** des adresses IP

## 📁 Structure des fichiers

```
mur-expression/
├── config.php          # Configuration
├── database.php        # Gestion base de données
├── functions.php       # Fonctions utilitaires
├── auth.php            # Authentification
├── index.php           # Page principale
├── admin.php           # Administration
├── view.php            # Visualisation
├── styles.css          # Styles
├── .htaccess           # Configuration Apache
├── install.php         # Installation (à supprimer)
├── README.md           # Documentation
└── data/
    └── expression_wall.db  # Base de données
```

## 🛠️ Maintenance

### Sauvegarde
La base de données se trouve dans `data/expression_wall.db`. Sauvegardez ce fichier régulièrement.

### Logs
Les adresses IP sont enregistrées pour chaque message (accessible via l'administration).

### Nettoyage
Les messages supprimés sont effacés définitivement de la base de données.

## 🚨 Support

En cas de problème :
1. Vérifiez les permissions des dossiers
2. Consultez les logs d'erreur PHP
3. Assurez-vous que SQLite3 est installé
4. Vérifiez la configuration dans config.php

## 📄 Licence

Projet libre d'utilisation et de modification selon vos besoins.
```

## Structure finale des fichiers :
```
📁 mur-expression/
├── 📄 config.php
├── 📄 database.php
├── 📄 functions.php
├── 📄 auth.php
├── 📄 index.php
├── 📄 admin.php
├── 📄 view.php
├── 📄 styles.css
├── 📄 .htaccess
├── 📄 install.php
├── 📄 README.md
└── 📁 data/
    └── 📄 expression_wall.db (créé automatiquement)
