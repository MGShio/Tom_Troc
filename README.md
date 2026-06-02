# TomTroc - Plateforme d'échange de livres

Une application web PHP/MVC pour échanger des livres entre passionnés de lecture.

---

## 🛠 Prérequis

- [XAMPP](https://www.apachefriends.org/) (ou WAMP/MAMP)
- PHP 8.0+
- MySQL / MariaDB
- phpMyAdmin (inclus avec XAMPP)

---

## 🚀 Installation et configuration

### 1. Installer XAMPP

1. Téléchargez et installez [XAMPP](https://www.apachefriends.org/download.html)
2. Lancez le **XAMPP Control Panel**
3. Démarrez les modules **Apache** et **MySQL** (cliquez sur "Start")

### 2. Cloner ou copier le projet

Placez le dossier du projet dans le répertoire `htdocs` de XAMPP :
```
C:\xampp\htdocs\Tom_Troc
```

### 3. Configurer la base de données

#### Via phpMyAdmin :

1. Ouvrez votre navigateur et allez sur : `http://localhost/phpmyadmin`
2. Cliquez sur **"Nouvelle base de données"** (ou "Create database")
3. Nommez-la : `tom_troc`
4. Choisissez l'encodage : `utf8mb4_unicode_ci`
5. Cliquez sur **Créer**

#### Importer la structure et les données :

1. Dans phpMyAdmin, sélectionnez la base `tom_troc` dans le menu de gauche
2. Cliquez sur l'onglet **"Importer"**
3. Cliquez sur **"Parcourir"** et sélectionnez le fichier :
   - `init_database.sql` (structure de base)
   - Ou `database_updates.sql` (si vous avez des mises à jour)
4. Cliquez sur **"Exécuter"**

> ⚠️ Si les deux fichiers existent, importez d'abord `init_database.sql` puis `database_updates.sql`

### 4. Configurer l'application

1. Créez un fichier `.env` à la racine du projet
2. Ajoutez vos variables :
```
DB_HOST=localhost
DB_USER=root
DB_PASS=votre_mot_de_passe
DB_NAME=tom_troc
```
3. Le projet les chargera automatiquement (via `$_ENV`)

### 5. Créer le dossier des uploads

Créez un dossier pour les images uploadées :
```
mkdir uploads
mkdir uploads/avatars
mkdir uploads/books
```

Donnez les permissions d'écriture :
- Sur Windows : clic droit sur le dossier > Propriétés > Sécurité > Modifier
- Autorisez **"Everyone"** ou **"SYSTEM"** en **écriture**

---

## 📁 Structure du projet

```
Tom_Troc/
├── assets/
│   ├── css/
│   │   └── styles.css       # Styles principaux
│   ├── js/
│   │   └── responsive.js    # JavaScript mobile
│   └── images/             # Images statiques
├── controllers/            # Contrôleurs MVC
├── managers/               # Gestionnaires de données (DAO)
├── models/                 # Modèles (entités)
├── views/                  # Vues
│   └── error/
│       └── 404.php         # Page d'erreur 404
├── includes/
│   ├── config.php          # Configuration
│   ├── router.php          # Routage des URLs
│   ├── database.php        # Connexion BDD
│   └── autoload.php        # Chargement automatique des classes
├── uploads/                # Fichiers uploadés (à créer)
├── .htaccess               # Réécriture d'URL (Apache)
├── init_database.sql       # Script de base de données
└── README.md               # Ce fichier
```

---

## 🌐 Accéder à l'application

1. Dans votre navigateur, allez sur : **`http://localhost/Tom_Troc/`**
2. La page d'accueil devrait s'afficher

### Pages principales :

| URL | Description |
|-----|-------------|
| `/` | Page d'accueil |
| `?controller=user&action=register` | Inscription |
| `?controller=user&action=login` | Connexion |
| `?controller=book&action=index` | Liste des livres |
| `?controller=book&action=show&id=1` | Détails d'un livre |

---

## ⚙️ Configuration avancée

### Changer le mot de passe MySQL

Si vous avez sécurisé MySQL avec un mot de passe, modifiez `includes/config.php` :
```php
define('DB_PASS', 'votre_mot_de_passe');
```

### Configurer un virtual host (optionnel)

Pour accéder au projet via `http://tomtroc.test` au lieu de `http://localhost/Tom_Troc/` :

1. Editez `C:\Windows\System32\drivers\etc\hosts` (en tant qu'administrateur) :
```
127.0.0.1   tomtroc.test
```

2. Dans `C:\xampp\apache\conf\extra\httpd-vhosts.conf` :
```apache
<VirtualHost *:80>
    ServerName tomtroc.test
    DocumentRoot "C:/xampp/htdocs/Tom_Troc"
    <Directory "C:/xampp/htdocs/Tom_Troc">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

3. Modifiez `includes/config.php` :
```php
define('BASE_URL', 'http://tomtroc.test/');
```

4. Redémarrez Apache dans XAMPP

---

## 🔧 Dépannage

### Erreur "Access denied for user 'root'@'localhost'"

Votre MySQL a un mot de passe. Vérifiez :
1. Le mot de passe dans `includes/config.php`
2. Que le service MySQL est bien démarré dans XAMPP

### Erreur "Database connection failed"

1. Vérifiez que XAMPP est lancé (Apache + MySQL)
2. Vérifiez les identifiants dans `includes/config.php`
3. Testez la connexion via phpMyAdmin : `http://localhost/phpmyadmin`

### La page 404 ne s'affiche pas

1. Vérifiez que le module Apache `rewrite` est activé
2. Dans `httpd.conf` (XAMPP), décommentez :
```apache
LoadModule rewrite_module modules/mod_rewrite.so
```
3. Assurez-vous que `AllowOverride All` est présent dans la configuration du virtual host
4. Redémarrez Apache

### Les images ne s'affichent pas

1. Vérifiez les permissions du dossier `uploads/`
2. Vérifiez les chemins dans la base de données (table `books` et `users`)
3. Assurez-vous que les fichiers existent physiquement dans `uploads/`

### L'URL de base n'est pas correcte

Si vous avez des problèmes de liens ou de redirections, vérifiez la valeur de `BASE_URL` dans `includes/config.php`. Elle doit correspondre exactement à l'URL que vous utilisez pour accéder au site (avec ou sans `/` final).

---

## 📝 Notes

- **Compatibilité** : Testé avec XAMPP 8.2 (PHP 8.2, MySQL 8.0)
- **Navigateurs supportés** : Chrome, Firefox, Edge (recommandé)
- **Mobile** : Design responsive intégré (`assets/js/responsive.js`)

---

## 🎨 Personnalisation

- Modifiez les couleurs dans `:root` de `assets/css/styles.css`
- Adaptez les textes dans les vues (`views/`)
- Ajoutez de nouvelles routes dans `includes/router.php`
- Personnalisez la page 404 dans `views/error/404.php`

---

## 📄 Licence

Projet étudiant - OpenClassrooms. Libre d'utilisation pour l'apprentissage.
