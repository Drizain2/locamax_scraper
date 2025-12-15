# LocaMax Scraper – Test Technique AlphaDev / Silitis Tech

Ce projet est une simulation technique visant à développer un outil backend capable de
scraper des sources immobilières et d’alimenter une base de données en Laravel.

Le but est de collecter automatiquement des données provenant d’acteurs du marché
locatif (agences et particuliers), puis de les afficher dans une interface simple avec filtre.

---

## Fonctionnalités

-   Scraping automatisé via une **commande Artisan** :
-   Extraction de :
-   URL source
-   Nom / titre
-   Type d’acteur (AGENCY / PRIVATE)
-   Ville + District
-   Type de bien (simulé)
-   Détection et suppression des doublons
-   Stockage dans une table `rental_sources`
-   Route `/` affichant les résultats dans un tableau HTML
-   Filtre dynamique par **ville**

---

## Technologies utilisées

-   **Laravel 11**
-   **Guzzle HTTP (Symfony DomCrawler)** pour le scraping
-   **Eloquent ORM**
-   Blade Templates
-   PHP 8.1+

---

## Choix de la librairie de scraping

J’ai choisi **Guzzle HTTP + Symfony DomCrawler** pour les raisons suivantes :

-   simple d'utilisation
-   adapté aux petits scrapers rapides
-   très léger
-   contrôle fin des sélecteurs CSS
-   compatible à 100% avec Laravel

Guzzle est utilisé comme client HTTP afin d’effectuer les requêtes vers les pages publiques du site cible.
Symfony DomCrawler est utilisé pour parser le HTML récupéré et extraire les données à l’aide de sélecteurs CSS.

Installation :

---

## Installation et lancement

### Cloner le projet

```
git clone git@github.com:Drizain2/locamax_scraper.git
cd rental-scraper
composer install
cp .env.example .env
php artisan key:generate
#Configurer la connexion à la base de données.
php artisan migrate
#Lancer le Scrapping
php artisan app:scrape-rentals

```
##  Source utilisée pour la démonstration

Le scraping est effectué sur un **site de test officiel pour développeurs** :

 https://www.expat-dakar.com/immobilier

