# Symfony API - API Platform

Cette API est construite avec Symfony et API Platform pour gérer un ensemble de ressources liées à des auteurs, livres, copies de livres, éditeurs, et genres.

## Fonctionnalités principales

- Gestion des ressources (CRUD) pour les entités `Author`, `Book`, `BookCopy`, `Editor`, `Genre`.
- Pagination sur les collections.
- Filtres de recherche et tri sur certaines entités.
- Sécurité intégrée avec des rôles (ex: `ROLE_ADMIN` pour la modification et suppression).

## Prérequis

- PHP 8.1 ou supérieur
- Composer
- Symfony CLI
- Base de données (MySQL, PostgreSQL, etc.)

## Installation

1. Clonez ce dépôt :
   ```bash
   git clone https://github.com/AubourgA/wikibookAPI.git

2. Installer les dépendance :
   ```bash
   composer install 
3. Configurer la base de données dans le fichier .env :
   ```bash
  DATABASE_URL="mysql://username:password@127.0.0.1:3306/database_name"  
4. Exécuter les migrations :
   ```bash
   php bin/console doctrine:migrations:migrate
5. Démarer le serveur Symfony :
    ```bash
    symfony server:start
    