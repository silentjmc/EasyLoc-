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

```
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
```

## Steps for installing test pages
### Prerequisites
Before you start, make sure you have the following items:

- PHP (version 8.2 or higher)
- Composer (PHP dependency manager)
- MongoDB (NoSQL database)
- MySQL or MariaDB (SQL database)

### 1. clone the project
Clone the project in the directory of your web server

```
git clone https://github.com/silentjmc/EasyLoc-.git 
cd easyloc                                           
```

### 2 Installing dependencies with Composer
Make sure that Composer is installed on your machine, then run the following command in the project directory :

```
composer install
```

This will install the mongoDB extension.

The code has been documented for use with PHPDocumentor, you can install PHPDocumentor to generate the documentation if you wish.

### 3. Configuring the MongoDB database
#### a. Connect to your MongoDB instance (via MongoDB Compass or the command line).
Create a database called easyloc (or use another name and update it in config.php).

Configure the connection information:
- Open the src/database/config.php file.
- Update the MongoDB connection information:

```php
$mongodb_config = [
    'instance' => 'localhost', // Replace with your MongoDB instance
    'user' => '', // Replace with the MongoDB user name (if necessary)
    'password' => '', // Replace with the MongoDB password (if necessary)
    'database' => 'easyloc', // Database name
];
```
#### b. import a dataset
Using MongoDB Database Tools and the mongoimport command, you can copy the two json files sample/customer.json and sample/vehicle.json into your mongoDB database to create a dataset for the two collections.

### 4. Configuring the MySQL database
#### a. Create a database called easyloc (or use another name and update it in config.php).

Configure the connection information:
- Open the src/database/config.php file.
- Update the MySQL connection information:

```php
$mysql_config = [
   'host' => 'localhost', // Replace with your MySQL database
   'port' => 3306, // Replace with the port number
   'user'=>'root', // Replace with the MySQL database user name
   'password' => '', // Replace with the MySQL database password
   'database' => 'easyloc', // Database name
];
```

#### b. import a dataset
Copy the contents of the sample/sample_insert file into your database administration portal to copy a dataset for the two tables

### 5. Configuring the web server
Place the project in the root directory of your web server (for example, EasyLoc).
Make sure that your web server points to the index.php file as the main entry point.

### 6. Features
#### SQL Base
- Delete/modify an existing contract.
- Access a specific contract using its unique key.
- Create a new contract.
- Create/modify/delete payment data.
- Access a specific payment using its unique key.
- List all contracts associated with a Customer uid.
- List current rentals associated with a Customer uid.
- Be able to list all late rentals (a rental is said to be "late" if returning_datetime is more than 1 hour after loc_end_datetime).
- List all payments associated with a rental.
- Check that a rental has been paid in full.
- List all unpaid rentals.
- Be able to count the number of late payments between two given dates.
- Be able to count the average number of late payments per customer.
- Be able to list all contracts where a certain vehicle has been used.
- To obtain the average delay per vehicle.
- Retrieve all contracts grouped by vehicle or by customer.

#### NoSQL database
- Create/modify/delete Customer and Vehicle documents.
- Be able to search for a Customer by surname+first name.
- Search for a vehicle using its registration number.
- Count vehicles with more (or less) than a certain mileage.
