## Projet de Thomas DUCRET

Commandes utilisées:

- composer require
- composer require --dev doctrine/doctrine-fixtures-bundle
- composer require doctrine/doctrine-fixtures-bundle
- composer require fakerphp/faker --dev
- composer require symfony/security-bundle
- composer require symfonycasts/reset-password-bundle
- composer require league/csv

# Étape 3 : Gestion des produits

6. Création d’une commande Symfony pour importer un fichier CSV contenant des produits. Le fichier CSV comportera un en-tête avec les colonnes suivantes : name, description, price. Les id seront générés automatiquement lors de l'importation.

php bin/console app:import-products "C:\Users\Vous\Downloads\products.csv" <-- Commande pour importer des produits en bdd (Étape 3.6)

# Étape 4 : Gestion des clients

6. Ajout d’une commande pour ajouter des clients en ligne de commande. La commande demandera le nom, prénom email et le rôle.
commande à utiliser:

php bin/console app:add-client

# Étape 5: Tests

Utilisez la commande suivante pour lancer les tests:

php bin/phpunit --testdox
ou
php bin/phpunit


# Symfony 7.1 Boilerplate 

Attention : Il vous faut PHP 8.2 pour faire fonctionner ce projet si vous avez PHP 8.1 utiliser la branche symfony64 du repository.

## Initialisation de votre IDE

### PHPStorm

1. Ouvrir le projet dans PHPStorm
2. Installer les extensions Twig et Symfony
    - Aller dans File > Settings > Plugins
    - Installer les extensions (Twig, EA Inspection, PHP Annotations, .env files support)

### Visual Studio Code

1. Ouvrir le projet dans Visual Studio Code
2. Installer les extensions pour PHP, Twig et Symfony
    - Aller dans l'onglet Extensions
    - Installer les extensions (whatwedo.twig, TheNouillet.symfony-vscode, DEVSENSE.phptools-vscode, 
    bmewburn.vscode-intelephense-client, zobo.php-intellisense)

## Installation avec IDX

1. Fork le projet sur votre compte GitHub
2. Importer le projet depuis votre GitHub sur IDX
3. Le projet est déjà lancé il suffit d'aller dans l'onglet du terminal avec `start` puis cliquer sur le lien `localhost`
4. Lancer la commande `composer i` pour installer les dépendances du projet.
5. Pour accéder à la base de données `mysql -u root`
6. Dans un fichier à la racine `.env.local` mettre cette variable d'environnement 
`DATABASE_URL="mysql://root:@127.0.0.1:3306/app?serverVersion=10.11.2-MariaDB&charset=utf8mb4"`

## Installation en local

1. Cloner le projet
2. Installer PHP >= 8.2 et Composer (Sur votre machine utiliser XAMPP pour windows, MAMP pour mac ou LAMP pour linux bien prendre la version PHP 8.2)
3. Installer les dépendances du projet avec la commande `composer install`
4. Faire un virtual host sur votre serveur local (XAMPP par exemple pour Windows) 
 - Ouvrir le fichier `httpd-vhosts.conf` dans le répertoire `C:\xampp\apache\conf\extra`
    - Ajouter le code suivant à la fin du fichier
    ```
    <VirtualHost *>
        DocumentRoot "C:\Users\votre_username\Documents\iut\symfony_base\public"
        ServerName symfony_base.local
        
        <Directory "C:\Users\votre_username\Documents\iut\symfony_base\public">
            AllowOverride All
            Require all granted
        </Directory>
    </VirtualHost>
    ```
    - Ajouter l'adresse IP de votre machine dans le fichier `C:\Windows\System32\drivers\etc\hosts`
    ```
    127.0.0.1 symfony_base.local
    ```
    - Redémarrer Apache
    - Accéder à l'adresse `symfony_base.local` dans votre navigateur

4. Créer un fichier `.env.local` à la racine du projet et ajouter la configuration de la base de données
5. Créer la base de données avec la commande `php bin/console doctrine:database:create`

## Utilisation

- N'hésitez pas à consulter la documentation de Symfony pour plus d'informations sur l'utilisation du framework : https://symfony.com/doc/current/index.html

- Notez comment fonctionne votre projet dans le fichier README.md et mettez à jour ce fichier au fur et à mesure de l'avancement de votre projet pour aider les autres développeurs à comprendre comment fonctionne votre projet.
