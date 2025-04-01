# Projet « Développer les composants d'accès aux données »

## PRÉSENTATION DU CONTEXTE DU CAHIER DES CHARGES

### 1. Présentation de l’entreprise à l’origine du besoin
L’entreprise EasyLoc’ est une entreprise Lyonnaise de location de voitures à des particuliers. Elle a connu une croissance importante ces dernières années. Sa directrice veut ouvrir une nouvelle agence à Nice et a donc entrepris de numériser les données de son entreprise. Elle a pris conseil sur une ingénieure en SGBD qui lui a conseillé une répartition en base de données SQL et NoSQL.
En l’attente d’équipes de développement supplémentaires, elle a besoin d’une bibliothèque de gestion de base de données qui sera utilisée par les équipes Frontend et Backend pour accéder aux données de ses agences pour la gestion des locations, des véhicules et des paiements.

#### 1.1. Contexte et besoin
Vous devez écrire et documenter une bibliothèque d’accès aux données des agences (décrites plus loin).
Cette bibliothèque s’interfacera avec les différents outils développés dans le futur et devra donc être :
- documentée pour faciliter le travail des développeurs/développeuses des autres applications ;
- sécurisée ;
- fournie avec un jeu de test unitaires pour pouvoir refactoriser et maintenir la bibliothèque sans
risques ;
- évolutive sans un coût technologique trop élevé.

#### 1.2. Architecture matérielle
L’architecture choisie par la directrice de EasyLoc’ est la suivante :
- 1 serveur applicatif sur lequel tournerons les outils de gestions des contrats de location et de
véhicules.
- 1 serveur SQL contenant les tables Contract et Billing (voir plus loin).
- 1 serveur MongoDB contenants les Customers et les Vehicles (voir plus loin).

#### 1.3. Architecture des bases de données
**Tables de la base de données SQL**
Table Contract : contient les données des contrats de locations.
Champs :
- id (INT - clé unique du contrat)
- vehicle_uid (CHAR(255) - uid du Vehicle associé au contrat)
- customer_uid (CHAR(255) - uid du Customer associé au contrat)
- sign_datetime (DATETIME - Date + heure de signature du contrat)
- loc_begin_datetime (DATETIME - Date + heure de début de la location)
- loc_end_ datetime (DATETIME - Date + heure de fin de la location)
- returning_datetime (DATETIME - Date + heure de rendu du véhicule)
- price (MONEY - Prix facturé pour le contrat)

Table Billing : Contient les données de payement des contrats. Payement en plusieur fois possible.
Champs :
- ID ( INT - clé unique du payement)
- Contract_id (INT - clé unique du contrat concerné par le payement)
- Amount (MONEY - Montant payé)

**Schémas de documents dans la base MongoDB**
Table Customer : Contient les données clients
- uid (UUID - Identifiant unique du document)
- first_name (CHAR(255) - Nom)
- second_name (CHAR(255) - Prénom)
- address (CHAR(255) - Adresse complète)
- permit_number (CHAR(255) -numéro de permis)

Table Vehicle : Contient les données associées à un véhicule
- uid (UUID - Identifiant unique du document)
- licence_plate (CHAR(255) - Immatriculation du véhicule)
- informations (TEXT - Notes sur le véhicule, par exemple dégradations)
- km (INT - Kilométrage du véhicule)

### 2. Cahier des charges
#### Partie 1 : base de donnée SQL
Étant donné un couple utilisateur/mot de passe, établir une connexion sécurisée au SGBD.

**Table Contract**
- Pouvoir créer la table Contract si elle n’existe pas.
- Pouvoir accéder à un contrat en particulier à partir de sa clé unique.
- Pouvoir créer un nouveau contrat à la date actuelle, à une date autre.
- Pouvoir supprimer/modifier un contrat existant.

**Table Billing**
- Pouvoir créer la table Billing si elle n’existe pas.
- Pouvoir accéder à un payement en particulier à partir de sa clé unique.
- Créer/Modifier/supprimer des données payement.

#### Partie 2 : Recoupement d’informations SQL
- Pouvoir lister tous les contrats associées à un uid de Customer.
- Pouvoir lister les locations en cours associées à un uid de Customer.
- Pouvoir lister toutes les locations en retard (une location est dite « en retard » si returning_datetime est postérieur à loc_end_datetime de plus d’1 heure).
- Pouvoir lister tous les payements associés à une location.
- Pouvoir vérifier qu’une location a été intégralement payée.
- Pouvoir lister toutes les locations impayées.
- Pouvoir compter le nombre de retard entre deux dates données.
- Pouvoir compter le nombre de retard moyens par Customer.
- Pouvoir lister tous les contrats où un certain véhicule a été utilisé.
- Pouvoir avoir la moyenne du temps de retard par véhicule.
- Pouvoir récupérer tous les contrats regroupés par véhicules ou par client/cliente.

**Partie 3 : Base NoSQL**
Étant donné un couple utilisateur/mot de passe, établir une connexion sécurisée à une instance MongoDB.
- Pouvoir créer/modifier/supprimer un document Customer et Vehicle.
- Pouvoir rechercher un Customer à partir de son nom+prénom.
- Pouvoir rechercher un véhicule à partir de son numéro d’immatriculation.
- Pouvoir compter les véhicules ayant plus (respectivement moins) d’un certain kilométrage.


## Structure du dépôt
Le dépôt contient
- les éléments de bibiothèques pour les requêtes à une base sql (type mysql) qu'a une base mongoDB contenu dans les dossiers nosql et sql
- un jeu de données pour sql et des examples de requêtes dans le dossier sample
- des pages de tests pour tester les différentes requêtes avec vos bases de données

EasyLoc/
├── css (style pour les pages de test)
├── nosql (Bibliothèque de gestion des données)
├── sample (données a injecter dans les tables sql et exemples de rêquetes fonctionnelles pour chaque demande)
├── sql (Bibliothèque de gestion des données)
├── src (classes pour tester les différentes requêtes en php)
├── vendor/ (a généré par Composer)
├── index.php (page de test)
├── billing.php (page de test)
├── customer.php (page de test)
├── vehicle.php (page de test)

## Étapes d'installation des pages de tests
Prérequis
Avant de commencer, assurez-vous d'avoir les éléments suivants :

- PHP (version 8.2 ou supérieure)
- Composer (gestionnaire de dépendances PHP)
- MongoDB (base de données NoSQL)
- MySQL ou MariaDB (base de données SQL)

### 1. Cloner le projet
Clonez le projet dans le répertoire de votre serveur web

```
git clone https://github.com/silentjmc/EasyLoc-.git 
cd easyloc                                           
```

### 2. Installer les dépendances avec Composer
Assurez-vous que Composer est installé sur votre machine, puis exécutez la commande suivante dans le répertoire du projet :

```
composer install
```

Cela installera l'extension mongoDB.

Le code a été documenté pour être utiliser avec PHPDocumentor, vous pouvez installer PHPDocumentor pour générér la documentation si vous le souhaitez.

### 3. Configurer la base de données MongoDB
Connectez-vous à votre instance MongoDB (via MongoDB Compass ou la ligne de commande).
Créez une base de données appelée easyloc (ou utilisez un autre nom et mettez-le à jour dans config.php).

Configurer les informations de connexion :
Ouvrez le fichier src/database/config.php.
Mettez à jour les informations de connexion MongoDB :

```php
$mongodb_config = [
    'instance' => 'localhost', // Remplacez par votre instance MongoDB
    'user' => '', // Remplacez par le nom d'utilisateur MongoDB (si nécessaire)
    'password' => '', // Remplacez par le mot de passe MongoDB (si nécessaire)
    'database' => 'easyloc', // Nom de la base de données
];
```

### 4. Configurer la base de données MySQL
Créez une base de données appelée easyloc (ou utilisez un autre nom et mettez-le à jour dans config.php).

Configurer les informations de connexion :
Ouvrez le fichier src/database/config.php.
Mettez à jour les informations de connexion MySQL :

```php
$mysql_config = [
   'host' => 'localhost', // Remplacez par votre base de données MySQL
   'port' => 3306, // Remplacez par le numero du port
   'user'=>'root', // Remplacez par le nom d'utilisateur de la base de données MySQL
   'password' => '', // Remplacez par le mot de passe de la base de données MySQL
   'database' => 'easyloc', // Nom de la base de données
];
```

### 5. Configurer le serveur web
Placez le projet dans le répertoire racine de votre serveur web (par exemple, EasyLoc).
Assurez-vous que votre serveur web pointe vers le fichier index.php comme point d'entrée principal.

### 6. Fonctionnalités
Base SQL
- Pouvoir supprimer/modifier un contrat existant.
- Pouvoir accéder à un contrat en particulier à partir de sa clé unique.
- Pouvoir créer un nouveau contrat
- Créer/Modifier/supprimer des données payement.
- Pouvoir accéder à un payement en particulier à partir de sa clé unique.
- Pouvoir lister tous les contrats associées à un uid de Customer.
- Pouvoir lister les locations en cours associées à un uid de Customer.
- Pouvoir lister toutes les locations en retard (une location est dite « en retard » si returning_datetime est postérieur à loc_end_datetime de plus d’1 heure).
- Pouvoir lister tous les payements associés à une location.
- Pouvoir vérifier qu’une location a été intégralement payée.
- Pouvoir lister toutes les locations impayées.
- Pouvoir compter le nombre de retard entre deux dates données.
- Pouvoir compter le nombre de retard moyens par Customer.
- Pouvoir lister tous les contrats où un certain véhicule a été utilisé.
- Pouvoir avoir la moyenne du temps de retard par véhicule.
- Pouvoir récupérer tous les contrats regroupés par véhicules ou par client/cliente.

Base NoSQL
- Pouvoir créer/modifier/supprimer un document Customer et Vehicle.
- Pouvoir rechercher un Customer à partir de son nom+prénom.
- Pouvoir rechercher un véhicule à partir de son numéro d’immatriculation.
- Pouvoir compter les véhicules ayant plus (respectivement moins) d’un certain kilométrage.
