# home-hub

## Description
_Experimental_

General-purpose web application project for daily management and experimentation with Laravel and Fialement\
The database is updated daily by the project [Polyvalent-Scraper](https://github.com/aandriano931/polyvalent-scraper)

## Functionalities:
### Users

* Administration space for creating banking data (transactions, categories, accounts, etc.)

![espace_admin_banque_1](https://github.com/aandriano931/home-hub/assets/49196540/efa719d2-6831-47a4-a096-4e0bd0fa7a58)

![espace_admin_banque_2](https://github.com/aandriano931/home-hub/assets/49196540/1ff0b450-7b45-46e1-8091-991a489732f7)

* Pages for macro/annual/monthly analysis of household expenses with graphical widgets

![analyse_macro_budget](https://github.com/aandriano931/home-hub/assets/49196540/2e7fe1fc-092b-4847-bcb7-0e826cd7fc5e)

![analyse_annuelle_budget](https://github.com/aandriano931/home-hub/assets/49196540/4d25e6e1-11ac-4ba3-a3de-5f136f2463f0)

![analyse_mensuelle_budget](https://github.com/aandriano931/home-hub/assets/49196540/edfd7643-7ac1-4266-82e4-dc8990891b29)

* Administration space for creating budget-related data (budgets, budget lines, participants, etc.)

![espace_admin_budget_1](https://github.com/aandriano931/home-hub/assets/49196540/8bb0bb65-b797-4a1f-99b6-232613e20f4f)

![espace_admin_budget_2](https://github.com/aandriano931/home-hub/assets/49196540/21cf5b19-63db-4a4d-96de-bb0091a8fd1a)

* Page providing information on the calculation of the current monthly budget with details of budget lines, participants, and their shares

![budget_mensuel_en_cours](https://github.com/aandriano931/home-hub/assets/49196540/00a60ee1-580d-4f23-afa4-d3dffee22a23)

* Command to add a bank transaction line to simulate expenses for "tickets restaurant".
* Button to send by email the budget shares for each participant
* Command to send an email when the active budget approaches its expiration date

### Technical
* Dockerization of the environment.
* Daily automatic backup of the database.
* Implementation of an Nginx reverse proxy to manage subdomains for various front-end containers
* Automation of Nginx configurations generation through [docker-gen](https://github.com/nginx-proxy/docker-gen)
* Implementation of SSL certificates using [let's encrypt](https://letsencrypt.org/)
* Addition of a Makefile for time efficiency
* Automatic build & deployment through Github Actions

### In progress:
* Cardboard Games library

### Upcoming functionalities: 
* Analysis of electrical consumption
* Better Logging of errors
* Library
* Analysis of water consumption
* Analysis of wood pellet consumption
* Setting up a guest user for demonstration purposes
* Cooking recipes
* ...

## Cronjobs 
* Monthly (first day of the month) cronjob to trigger the command for adding a bank transaction to simulate expenses related to "tickets restaurant"

``0 0 1 * *  docker exec app php artisan budget:add-tickets-resto``

* Daily cronjob to execute a command that checks if the current monthly budget is approaching its expiration date (-7 days) and sends an email to the participants if necessary.

``30 17 * * *  docker exec app php artisan budget:expiration:warn``
