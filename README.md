# home-hub

## Description

Projet d'appli web généraliste de gestion du quotidien et d'expérimentations sur Laravel.\
La base de données est alimentée quotidiennement par le projet [Polyvalent-Scraper](https://github.com/aandriano931/polyvalent-scraper)

## Fonctionnalités:
### Métier
* Espace d'administration pour la création des données bancaires (transactions, catégories, comptes etc.)

![espace_admin_banque_1](https://github.com/aandriano931/home-hub/assets/49196540/efa719d2-6831-47a4-a096-4e0bd0fa7a58)

![espace_admin_banque_2](https://github.com/aandriano931/home-hub/assets/49196540/1ff0b450-7b45-46e1-8091-991a489732f7)

* Pages d'analyses macro/annuelle/mensuelle des dépenses du foyer avec widgets graphiques

![analyse_macro_budget](https://github.com/aandriano931/home-hub/assets/49196540/2e7fe1fc-092b-4847-bcb7-0e826cd7fc5e)

![analyse_annuelle_budget](https://github.com/aandriano931/home-hub/assets/49196540/4d25e6e1-11ac-4ba3-a3de-5f136f2463f0)

![analyse_mensuelle_budget](https://github.com/aandriano931/home-hub/assets/49196540/edfd7643-7ac1-4266-82e4-dc8990891b29)

* Espace d'administration pour la création des données du budget (budgets, lignes de budget, participants etc.)

![espace_admin_budget_1](https://github.com/aandriano931/home-hub/assets/49196540/8bb0bb65-b797-4a1f-99b6-232613e20f4f)

![espace_admin_budget_2](https://github.com/aandriano931/home-hub/assets/49196540/21cf5b19-63db-4a4d-96de-bb0091a8fd1a)

* Page d'information sur le calcul du budget mensuel en cours avec détails des lignes du budget, participants et leurs parts

![budget_mensuel_en_cours](https://github.com/aandriano931/home-hub/assets/49196540/00a60ee1-580d-4f23-afa4-d3dffee22a23)

* Commande d'ajout d'une ligne de transaction bancaire pour simuler les dépenses de tickets restaurants
* Bouton d'envoi par email des parts du budget pour chaque participant
* Commande pour envoyer un email lorsque le budget actif approche de sa date d'expiration

### Technique
* Dockerisaton de l'environnement
* Sauvegarde quotidienne automatique de la base de données
* Mise en place d'un reverse proxy Nginx pour gérer les sous-domaines des différents conteneurs front
* Automatisation des confs Nginx grâce à [docker-gen](https://github.com/nginx-proxy/docker-gen)
* Mise en place des certificats SSL avec [let's encrypt](https://letsencrypt.org/)
* Ajout d'un Makefile pour gagner du temps 

### En cours de développement:
* Build et déploiement automatique via Github Actions

### A venir: 
* Ludothèque
* Analyse de la consommation électrique
* Mise en place d'un utilisateur guest pour démonstration
* Regroupement des agendas ? Agenda famille
* Bibliothèque
* Recettes de cuisine

## Cronjobs nécessaires
* Cronjob de lancement mensuel (premier du mois) de la commande d'ajout d'une transaction bancaire pour simuler les dépenses liées aux tickets restaurants

``0 0 1 * *  docker exec app php artisan budget:add-tickets-resto``

* Cronjob de lancement quotidien d'une commande qui vérifie si le budget mensuel en cours approche de sa date d'expiration (-7 jours) et envoi un email aux participants le cas échéant.

``30 17 * * *  docker exec app php artisan budget:expiration:warn``
