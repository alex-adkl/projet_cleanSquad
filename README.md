# Projet CleanSquad

Descriptif du projet:
Le projet est un système de gestion de collecte bénévole, permettant aux utilisateurs de créer des collectes et de renseigner les déchets collectés ainsi que leurs quantités. Il inclut une interface graphique en ligne, avec connexion par identifiant, et est relié à une base de données pour stocker et gérer les informations. Ce système facilite l'organisation des collectes et le suivi de l'impact écologique des actions menées.

Fonctionnalités principales :
Créer et gérer des collectes de déchets.
Suivi des types de déchets collectés et de leurs quantités.
Interface utilisateur simple et intuitive.
Authentification via identifiant pour les utilisateurs.
Stockage des données dans une base de données MySQL.
Graphique de collecte.
Total collecte par benevoles, total collecte global par type de dechet.

Environnement de développement:
Server Software : Apache/2.4.62 (Win64), PHP/8.3.14
Base de données : MySQL 9.1.0 (via XAMPP ou WAMP)
Interface de gestion de la base de données : PhpMyAdmin 5.2.1

Langages de programmation :
- PHP
- CSS
- HTML
- JavaScript
- Bibliothèque utilisée : Chart.js (pour la visualisation graphique des données)

Prérequis:
Avant de démarrer le projet en local, vous devez installer les logiciels suivants :
- WAMP / XAMPP : pour faire tourner le serveur Apache et la base de données MySQL
- PHP : pour exécuter le code serveur
- PhpMyAdmin : pour gérer facilement la base de données MySQL

Installation :
1. Clonez ou téléchargez ce projet sur votre machine locale.
2. Installez WAMP ou XAMPP si ce n’est pas déjà fait.
3. Placez les fichiers du projet dans le répertoire www de WAMP/XAMPP.
4. Créez une base de données MySQL appelée "gestion_collectes" avec PhpMyAdmin. Ensuite, importez le fichier gestion_collectes.sql pour générer les tables.
5. Lancez Apache et MySQL via WAMP/XAMPP.
6. Accédez à l'application via votre navigateur en allant à l'adresse http://localhost/projetCleanSquad/[.

Ressources utiles:
https://www.wampserver.com/
https://www.apachefriends.org/docs/
https://www.php.net/docs.php
https://www.phpmyadmin.net/docs/
https://www.chartjs.org/
