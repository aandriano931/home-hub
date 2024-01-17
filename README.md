# home-hub

## Description

Projet d'appli web généraliste de gestion du quotidien et d'expérimentations en développement web.

### Fonctionnalités:
* Espace d'administration pour la création des données bancaires (transactions, catégories, comptes etc.)
* Pages d'analyses macro/annuelle/mensuelle des dépenses du foyer avec widgets graphiques
* Espace d'administration pour la création des données du budget (budgets, lignes de budget, participants etc.)
* Page d'information sur le calcul du budget mensuel en cours avec détails des lignes du budget, participants et leurs parts
* Commande mensuelle d'ajout d'une ligne de transaction bancaire pour simuler les dépenses de tickets restaurants
* Bouton d'envoi par email des parts du budget pour chaque participant
* Commande pour envoyer un email lorsque le budget actif approche de sa date d'expiration

### En cours de développement:
* Build et déploiement automatique via Github Actions
* Sauvegarde automatique de la base de données

### A venir: 
* Analyse de la consommation électrique
* Regroupement des agendas ? Agenda famille
* Ludothèque
* Bilbiothèque
* Recettes de cuisine

## Cronjobs nécessaires

* Cronjob de lancement mensuel (premier du mois) de la commande d'ajout d'une transaction bancaire pour simuler les dépenses liées aux tickets restaurants

``0 0 1 * *  docker exec app php artisan budget:add-tickets-resto``

* Cronjob de lancement quotidien d'une commande qui vérifie si le budget mensuel en cours approche de sa date d'expiration (-7 jours) et envoi un email aux participants le cas échéant.

``30 17 * * *  docker exec app php artisan budget:expiration:warn``