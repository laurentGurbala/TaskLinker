# TaskLinker

TaskLinker est une application web développée avec **Symfony 6**, PHP et MySQL.  
Elle permet de gérer des **projets et employés** avec un système complet d’authentification et d’administration.  
Le projet a été conçu dans le cadre d’une formation en développement web, et sert de **portfolio interactif**.

---

## Fonctionnalités principales

- Gestion des **projets** : création, modification, suppression
- Gestion des **employés** : ajout, édition, suppression
- Système d’**authentification** avec rôles (utilisateur / admin)
- Possibilité de tester l’application en ligne ou de cloner le projet

---

## Technologies utilisées

- **Backend** : PHP 8+, Symfony 6
- **Base de données** : MySQL
- **Outils** : Composer

---

## Installation

### 1. Cloner le projet
```bash
git clone https://github.com/tonGitHub/TaskLinker.git
```

### 2. Installer les dépendances
```bash
cd TaskLinker
composer install
```

### 3. Configurer la base de données
Mettre à jour le fichier .env ou créer un .env.local avec votre base de données
exemple: 
```bash
DATABASE_URL="mysql://DB_USER:DB_PASSWORD@DB_HOST:3306/DB_NAME?serverVersion=8.0&charset=utf8mb4"
```

### 4. Importer la base de données
via phpMyAdmin : importer le fichier **tasklinker.sql** (fourni)

### 5. Lancer le serveur Symfony
```bash
symfony server:start
```

## Utilisation

| Rôle  | Email                                     | Mot de passe |
| ----- | ------------------------- | ------------ |
| Admin | natalie@driblet.com   | test123          |
| User  | demi@driblet.com | test123          |
| User  | marie@driblet.com | test123          |

### user
- sélectionner un projet dans lequel il est affecté
- créer des tâches
- modifier des tâches existantes
- supprimer des tâches existantes

### admin
- créer un nouveau projet
- modifier l'équipe associé à un projet
- supprimer un projet
- ajouter un membre à l'équipe
- supprimer un membre à l'équipe

## Liens
- [démo](https://laurent-gurbala.fr/TaskLinker)
- [portfolio](https://laurent-gurbala.fr)