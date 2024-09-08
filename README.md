# Symfony API - API Platform

Cette API est construite avec Symfony et API Platform pour gérer un ensemble de ressources liées à des auteurs, livres, copies de livres, éditeurs, et genres... Elle fait parti de l'app WIKIBOOK et regroupe les ressources nécessaires au bon fonctionnement de l'app.

La partie front est disponible sur ce repo : https://github.com/AubourgA/wikiBook

## Fonctionnalités principales

- Gestion des ressources (CRUD) pour les entités `Author`, `Book`, `BookCopy`, `Editor`, `Genre`. `Language`,`Loan`,`Nationnality`,`Status` et `User`
- Pagination sur les collections.
- Filtres de recherche et tri sur certaines entités.
- Sécurité intégrée avec des rôles (ex: `ROLE_ADMIN` pour la modification et suppression).
- Authentification avec JWT 
- Systeme d'envois de mail
- Changement de type de status lorsque la réservation d'un livre
  

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

5. Générer la paire de clef jwt
   1. Créer le dossier jwt dans bin/config
   2. taper la commande suivante :
      ```bash
      php bin/console lexik:jwt:generate-keypair
      
    3. suivez les consignes pour créer la passphrase

6. Démarer le serveur Symfony :
    ```bash
    symfony server:start

## Endpoints

### Author

- **GET /authors** : Récupère une collection d'auteurs (pagination: 10 éléments par page).
- **GET /authors/{id}** : Récupère les détails d'un auteur.
- **POST /authors** : Crée un nouvel auteur (ROLE_ADMIN requis).
- **DELETE /authors/{id}** : Supprime un auteur (ROLE_ADMIN requis).
- **PATCH /authors/{id}** : Modifie partiellement un auteur (ROLE_ADMIN requis).

#### Filtres disponibles :

- **name** : Filtre par nom d'auteur (recherche partielle).

### Book

- **GET /books** : Récupère une collection de livres (pagination: 12 éléments par page).
- **GET /books/{id}** : Récupère les détails d'un livre.
- **POST /books** : Crée un nouveau livre (ROLE_ADMIN requis).
- **DELETE /books/{id}** : Supprime un livre (ROLE_ADMIN requis).
- **PATCH /books/{id}** : Modifie partiellement un livre (ROLE_ADMIN requis).

#### Filtres disponibles :

- **title** : Filtre par titre du livre.
- **ISBN** : Filtre par numéro ISBN.
- **YearPublished** : Filtre par année de publication.
- **genre.name** : Filtre par genre.
- **author.name** : Filtre par auteur.

### BookCopy

- **GET /book_copies** : Récupère une collection de copies de livres (ROLE_ADMIN requis).
- **GET /book_copies/{id}** : Récupère les détails d'une copie de livre (ROLE_USER requis).
- **POST /book_copies** : Crée une nouvelle copie de livre (ROLE_ADMIN requis).
- **PATCH /book_copies/{id}** : Modifie partiellement une copie de livre (ROLE_USER requis).
- **DELETE /book_copies/{id}** : Supprime une copie de livre (ROLE_ADMIN requis).

### Editor

- **GET /editors** : Récupère une collection d'éditeurs.
- **GET /editors/{id}** : Récupère les détails d'un éditeur.
- **POST /editors** : Crée un nouvel éditeur (ROLE_ADMIN requis).
- **PATCH /editors/{id}** : Modifie partiellement un éditeur (ROLE_ADMIN requis).

### Genre

- **GET /genres** : Récupère une collection de genres.
- **GET /genres/{id}** : Récupère les détails d'un genre.
- **POST /genres** : Crée un nouveau genre (ROLE_ADMIN requis).
- **PATCH /genres/{id}** : Modifie partiellement un genre (ROLE_ADMIN requis).


### Language

- **GET /languages** : Récupère une collection de langues (pagination non précisée).
- **GET /languages/{id}** : Récupère les détails d'une langue.
- **POST /languages** : Crée une nouvelle langue (ROLE_ADMIN requis).
- **PATCH /languages/{id}** : Modifie partiellement une langue (ROLE_ADMIN requis).

#### Filtres disponibles :

- **name** : Filtre par nom de langue (recherche partielle).

### Loan

- **GET /loans** : Récupère une collection de prêts (ROLE_ADMIN requis, pagination: 10 éléments par page).
- **POST /loans** : Crée un nouveau prêt (ROLE_USER requis).
- **GET /loans/{id}** : Récupère les détails d'un prêt (ROLE_USER requis et l'utilisateur doit être le propriétaire du prêt).
- **PATCH /loans/{id}** : Modifie partiellement un prêt (ROLE_ADMIN ou l'utilisateur doit être le propriétaire).

#### Filtres disponibles :

- **returnDate** : Filtre selon l'existence d'une date de retour.

### Nationality

- **GET /nationalities** : Récupère une collection de nationalités.
- **GET /nationalities/{id}** : Récupère les détails d'une nationalité.
- **POST /nationalities** : Crée une nouvelle nationalité (ROLE_ADMIN requis).
- **PATCH /nationalities/{id}** : Modifie partiellement une nationalité (ROLE_ADMIN requis).

#### Filtres disponibles :

- **country** : Filtre par pays (recherche partielle).

### Status

- **GET /status** : Récupère une collection de statuts.
- **GET /status/{id}** : Récupère les détails d'un statut.
- **POST /status** : Crée un nouveau statut (ROLE_ADMIN requis).
- **PATCH /status/{id}** : Modifie partiellement un statut (ROLE_ADMIN requis).

### User

- **GET /users** : Récupère une collection d'utilisateurs (ROLE_ADMIN requis).
- **POST /users** : Crée un nouvel utilisateur.
- **GET /users/{id}** : Récupère les détails d'un utilisateur (ROLE_USER requis et l'utilisateur doit être le propriétaire).
- **PATCH /users/{id}** : Modifie partiellement un utilisateur (ROLE_USER requis et l'utilisateur doit être le propriétaire).
- **DELETE /users/{id}** : Supprime un utilisateur (ROLE_ADMIN requis).

### EmailDTO

- **POST /send-email** : Envoie un email avec un sujet et un message donnés à une adresse email spécifiée.

#### Paramètres attendus :

- **lastname** (string) : Nom de famille.
- **firstname** (string) : Prénom.
- **email** (string) : Adresse email.
- **message** (string) : Corps du message.

#### Réponses possibles :

- **204** : Email envoyé avec succès.
- **400** : Entrée invalide.

### MeController

#### Route : `GET /api/me`

Cette route permet de récupérer les informations de l'utilisateur courant.

- **Méthode** : `GET`
- **Réponse** : 
  - Si l'utilisateur est authentifié : 
    - Renvoie un objet JSON contenant les informations de l'utilisateur, sérialisées avec le groupe de sérialisation `read:user:item`.
  - Si l'utilisateur n'est pas authentifié : 
    - Renvoie un message d'erreur avec le code HTTP 401 et la structure suivante :
    
      ```json
      {
          "error": "User not authenticated"
      }
      ```

#### Exemple de réponse (utilisateur authentifié) :

```json
{
    "id": 1,
    "email": "user@example.com",
    "name": "John",
    "firstname": "Doe",
    "numPortable": "+33123456789",
    "city": "Paris",
    "subscribedAt": "2022-01-01T12:00:00",
    "isActive": true
}
```

### Sécurité

L'API utilise un système de rôles pour restreindre l'accès à certaines opérations :

- **ROLE_ADMIN** : Peut créer, modifier, ou supprimer des auteurs, livres, copies de livres, éditeurs et genres.
- **ROLE_USER** : Peut consulter les copies de livres et obtenir des informations détaillées sur un livre.


