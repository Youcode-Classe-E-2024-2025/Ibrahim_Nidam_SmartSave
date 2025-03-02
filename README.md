# Ibrahim_Nidam_SmartSave

**SaveSmart – Application d’optimisation budgétaire**

**Author du Brief:** Iliass RAIHANI.

**Author de Code:** Ibrahim Nidam.

## Links

- **GitHub Repository :** [View on GitHub](https://github.com/Youcode-Classe-E-2024-2025/Ibrahim_Nidam_SmartSave.git)
- **Project Backlog :** [View Backlog](https://github.com/orgs/Youcode-Classe-E-2024-2025/projects/136/views/1?system_template=team_planning)
- **UML Diagrams:** [View in public/UML]()

![Diagram](public/UML/SmartSave%20-%20UML.drawio.svg)

### Créé : 24/02/25

Application web en Laravel permettant aux utilisateurs de saisir leurs revenus et dépenses, puis de bénéficier d’une répartition automatique du budget selon des règles logiques (par exemple, la règle 50/30/20). Le projet est conçu pour être réalisé sans recours à l’intelligence artificielle.

# Configuration et Exécution du Projet Laravel

## Prérequis

Avant de commencer, assurez-vous d'avoir installé les outils suivants :

- **PHP** (à partir de la version recommandée par Laravel, voir [PHP](https://www.php.net/)).
- **Composer** ([télécharger ici](https://getcomposer.org/download/)).
- **Node.js** et **npm** ([télécharger ici](https://nodejs.org/)).
- **MySQL** (ou un autre SGBD compatible, ex: PostgreSQL).
- **Laravel** installé globalement (optionnel, peut être utilisé via Composer).

## Installation du projet

### 1. Cloner le dépôt

Ouvrez un terminal et exécutez :
```bash
git clone https://github.com/Youcode-Classe-E-2024-2025/Ibrahim_Nidam_SmartSave.git
cd Ibrahim_Nidam_SmartSave
```

### 2. Installer les dépendances PHP

Dans le dossier du projet, exécutez :
```bash
composer install
```

### 3. Configurer le fichier `.env`

Copiez le fichier `.env.example` et renommez-le en `.env` :
```bash
cp .env.example .env  # Linux/Mac
copy .env.example .env # Windows
```

Modifiez les paramètres de la base de données dans `.env` :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nom_de_votre_bdd
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Générer la clé d'application

Exécutez la commande suivante pour générer une clé unique :
```bash
php artisan key:generate
```

### 5. Exécuter les migrations et seeders (si disponibles)

Créez la base de données et appliquez les migrations :
```bash
php artisan migrate --seed
```

### 6. Installer les dépendances front-end

Installez les dépendances npm :
```bash
npm install
```
Si votre projet utilise Vite, démarrez le build :
```bash
npm run dev
```

### 7. Démarrer le serveur local

Utilisez la commande artisan pour démarrer le serveur Laravel :
```bash
php artisan serve
```
Accédez au projet via : [http://127.0.0.1:8000](http://127.0.0.1:8000)

### 8. Configuration supplémentaire (si nécessaire)

- Si vous utilisez **Sail** (environnement Docker pour Laravel) :
  ```bash
  ./vendor/bin/sail up -d
  ```
- Si vous utilisez **Horizon** pour la gestion des files d'attente :
  ```bash
  php artisan horizon
  ```

Votre projet est maintenant configuré et prêt à être utilisé 🚀




# Contexte du projet:

Face à l’importance de la gestion financière personnelle, SaveSmart se présente comme un outil simple et efficace pour aider chacun à maîtriser ses finances. Ce projet s’inscrit dans un cursus de niveau intermédiaire, alliant la mise en pratique des compétences Laravel et la gestion de projet en mode agile.

## **Objectifs du projet :**

#### **Fonctionnels :**
- Permettre l’inscription/authentification sécurisée des utilisateurs. (S1)
- Ajout de plusieurs utilisateurs sous un même compte familial. (S1)
- Gérer la saisie et le suivi des revenus, dépenses et objectifs financiers via des formulaires CRUD. (S1)
- Offrir des visualisations graphiques simples (tableaux, diagrammes) pour illustrer la répartition du budget. (S1)
- Ajout de catégories personnalisables (ex. Alimentation, Logement, Divertissement, Épargne). (S1)
- Création d’objectifs d’épargne (ex. Acheter un PC, Partir en vacances). (S2)
- Affichage de la progression par rapport aux montants économisés. (S2)
- Développer un algorithme d’optimisation budgétaire (basé sur des règles logiques et non sur l’IA) qui propose une répartition du budget en fonction des priorités définies (ex. besoins, envies, épargne). (S2)
- Ajout méthodes d’optimisation 50/30/20 (Besoins 50% / Envies 30% / Épargne 20%). (S2)
Export des données en PDF ou CSV.
#### **Techniques :**

- Mise en place d’un environnement Laravel complet (installation, configuration, structuration MVC).
- Intégration d’un système de tests unitaires et fonctionnels pour garantir la robustesse du code.



## **Modalités pédagogiques**

- Travail individuel.
- Approche itérative : deux cycles de développement avec un livrable à la fin de chaque semaine (24/02/2025 -> 7/02/2025).
- Méthodologie agile : suivi du projet via GitHub Project, avec un backlog et un tableau Kanban pour organiser et prioriser les tâches.

## **Modalités d'évaluation**

- Qualité du code : architecture Laravel, propreté du code, tests unitaires et fonctionnels.
- Pertinence de l’algorithme : logique de répartition budgétaire respectant la règle établie.
- Documentation : clarté du README, présence des diagrammes de modélisation et du planning sur GitHub.
- Présentation orale : qualité de la soutenance (présentation, démonstration et réponses aux questions).

## **Livrables**
**Semaine 1 :**
- Mise en place de l’environnement Laravel, structure MVC, configuration de la base de données et implémentation de l’authentification.
- Développement des premières interfaces de saisie des données financières (formulaires CRUD).
- Dépôt GitHub initial avec documentation et organisation du projet (backlog, Kanban).

**Semaine 2 :**
- Intégration de l’algorithme d’optimisation budgétaire et développement des visualisations (graphiques ou indicateurs).
- Finalisation des tests et amélioration de l’UI/UX.
- Ajout des diagrammes de classe et de cas d’utilisation dans la documentation, préparation de la présentation de soutenance.
- Livraison finale sur GitHub (code source, documentation complète et présentation).

## **Critères de performance**

*Fonctionnels :*
- Authentification sécurisée et gestion des données financières (création, modification, suppression).
- Répartition automatique du budget conforme aux règles établies.

*Techniques :*
- Architecture MVC bien définie, code modulaire et tests automatisés.

*Organisationnels :*
- Suivi régulier via GitHub avec backlog et tableau Kanban mis à jour.
- Respect des deadlines et qualité de la documentation.

*Pédagogiques :*
- Clarté des diagrammes (classe et usecase) et pertinence de la présentation lors de la soutenance.