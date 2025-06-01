# CarRental – Plateforme de gestion de vente de voitures

> Une application Symfony MVC permettant au gérant d'une concession de gérer voitures, clients, marques, modèles et réservations de véhicules.

<p align="center">
  <img src="https://img.shields.io/badge/Symfony-000000?style=flat-square&logo=symfony&logoColor=white" alt="Symfony"/>
  <img src="https://img.shields.io/badge/PHP-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP"/>
  <img src="https://img.shields.io/badge/MySQL-00758F?style=flat-square&logo=mysql&logoColor=white" alt="MySQL"/>
  <img src="https://img.shields.io/badge/Twig-20B2AA?style=flat-square&logo=twig&logoColor=white" alt="Twig"/>
  <img src="https://img.shields.io/badge/Bootstrap-7952B3?style=flat-square&logo=bootstrap&logoColor=white" alt="Bootstrap"/>
  <img src="https://img.shields.io/badge/Architecture-MVC-critical?style=flat-square" alt="MVC"/>
  <img src="https://img.shields.io/badge/Status-Terminé%20-brightgreen?style=flat-square" alt="Statut"/>
  <img src="https://img.shields.io/badge/License-MIT-blue?style=flat-square" alt="License"/>
</p>

<h3 align="center">• • •</h3>

## À propos

**CarRental** est une application développée avec Symfony permettant à une concession automobile de gérer son activité. Le gérant peut CRUDer les entités clés : voitures, clients, marques, modèles, et gérer les réservations de véhicules. Les clients peuvent consulter les véhicules disponibles et effectuer une réservation.

<h3 align="center">• • •</h3>

## Objectifs techniques

- Implémentation d’une architecture MVC avec Symfony
- Création de relations entre entités : voiture, client, marque, modèle
- Authentification des utilisateurs (clients et admin)
- Interface CRUD sécurisée pour le gérant
- Réservation de véhicule par le client connecté

<h3 align="center">• • •</h3>

## Fonctionnalités

### Gérant (Administrateur)
- Ajouter, modifier, supprimer : voitures, clients, marques, modèles
- Voir toutes les réservations
- Accès complet au back-office

### Client
- Voir les voitures disponibles
- Filtrer par marque et modèle
- Réserver une voiture
- Voir ses réservations

<h3 align="center">• • •</h3>

## Stack technique

| Élément          | Technologies utilisées               |
|------------------|--------------------------------------|
| Backend          | Symfony 6, PHP 8.2                   |
| ORM              | Doctrine ORM                         |
| Frontend         | Twig, Bootstrap                      |
| Base de données  | MySQL                                |
| Authentification | Symfony Security                     |
| Conteneurisation | Docker + Docker Compose              |

<h3 align="center">• • •</h3>

## 📁 Structure du projet

```
📁 bin/
📁 config/
📁 GestionVenteVoiture/
📁 migrations/
📁 public/
📁 src/
📁 templates/
📁 tests/
📁 translations/
📁 var/
📁 vendor/
.env
composer.json
symfony.lock
docker-compose.yml
```

<h3 align="center">• • •</h3>

## Installation locale

```bash
# 1. Cloner le projet
git clone https://github.com/ton-utilisateur/carrental.git
cd carrental

# 2. Installer les dépendances PHP
composer update
composer install

# 3. Copier le fichier d'environnement
cp .env .env.local

# 4. Créer la base de données
php bin/console doctrine:database:create

# 5. Appliquer les migrations
php bin/console doctrine:migrations:migrate

# 6. Lancer le serveur
symfony server:start
```

<h3 align="center">• • •</h3>

## Rôles et droits

| Rôle      | Accès                                  |
|-----------|-----------------------------------------|
| Visiteur  | Consultation des voitures               |
| Client    | Réservation de véhicules                |
| Gérant    | Gestion complète (CRUD + réservations)  |

<h3 align="center">• • •</h3>

## Compétences mobilisées

- Développement fullstack Symfony MVC
- Relations complexes entre entités Doctrine
- Authentification et sécurité
- Utilisation de Docker pour la conteneurisation
- Gestion d’un CRUD complet + rôles

<h3 align="center">• • •</h3>

## Captures d’écran

<p align="center">
  <table>
    <tr>
      <td align="center">Page d’accueil<br/>
        <img src="https://github.com/majoiefaya/CarRental/blob/main/assets/images/accueil.png" width="300" alt="accueil"/>
      </td>
      <td align="center">À propos<br/>
        <img src="https://github.com/majoiefaya/CarRental/blob/main/assets/images/car_rental_about.png" width="300" alt="about"/>
      </td>
      <td align="center">Ajout vente<br/>
        <img src="https://github.com/majoiefaya/CarRental/blob/main/assets/images/car_rental_add_vente.png" width="300" alt="add_vente"/>
      </td>
      <td align="center">Admin Dashboard<br/>
        <img src="https://github.com/majoiefaya/CarRental/blob/main/assets/images/car_rental_admin_dashboard.png" width="300" alt="admin_dashboard"/>
      </td>
    </tr>
    <tr>
      <td align="center">Contact<br/>
        <img src="https://github.com/majoiefaya/CarRental/blob/main/assets/images/car_rental_contact.png" width="300" alt="contact"/>
      </td>
      <td align="center">Réservation<br/>
        <img src="https://github.com/majoiefaya/CarRental/blob/main/assets/images/car_rental_reservation.png" width="300" alt="reservation"/>
      </td>
      <td align="center">Liste des clients<br/>
        <img src="https://github.com/majoiefaya/CarRental/blob/main/assets/images/liste_clients.png" width="300" alt="liste_clients"/>
      </td>
      <td align="center">Consulting<br/>
        <img src=" https://github.com/majoiefaya/CarRental/blob/main/assets/images/consulting.png" width="300" alt="consulting"/>
      </td>
    </tr>
  </table>
</p>

<h3 align="center">• • •</h3>

## Licence

Ce projet est open-source, publié sous licence **MIT**.


## ☕ Me soutenir

<p align="center">
  <a href="https://buymeacoffee.com/majoiefaya" target="_blank" rel="noopener noreferrer">
    <img src="https://img.shields.io/badge/Buy%20Me%20a%20Coffee-ffdd00?style=flat-square&logo=buymeacoffee&logoColor=black" alt="Buy Me a Coffee"/>
  </a>
</p>
