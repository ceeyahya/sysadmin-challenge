# Sysadmin Challenge

## Objectif :
L'objectif de cet exercice est d'évaluer les compétences en administration système et DevOps du candidat dans la configuration, le déploiement et la résolution de problèmes liés à une infrastructure hébergeant à la fois une application Drupal et une application React. L'accent est mis sur l'automatisation, la sécurité, les performances et la résolution de problèmes avancés.

L'exercice est réparti en trois grands chapitres :

 1. Configuration et provisionnement d'une VM avec Ansible
 2. Configuration et installation d'une application Drupal PHP avec son infrastructure Docker
 3. Installation d'une application React connecté avec l'API Drupal
  
Livrables attendus pour chaque chapitre :
 1. Fichiers de configuration Ansible
 2. Procédure d'installation (docker-compose.yml / Dockerfile / commande d'exécution...), rapport détaillons la resolution des problèmes dédié à ce chapitre
 3. Même chose que les livrables du chapitre 2
### Chapitre 1:  Configuration et provisionnement de la VM avec Ansible
Automatiser le processus de provisionnement et configuration d'un serveur vierge avec Ansible:

- Utilisation de Virtual Box comme hyperviseur.
- Utilisation d'Ansible pour automatiser la configuration de la VM.
- Utilisation d'Ansible pour installer Docker sur la VM.
- Configuration du log Docker en utilisant le driver json-file avec une limite de taille de 100m.
- Mise en place d'un Cronjob pour purger et supprimer les données inutilisées par Docker (volumes, containers, networks, images).

### Chapitre 2: Configuration et installation d'une application Drupal PHP avec son infrastructure Docker
Drupal est un système de gestion de contenu (CMS) populaire écrit en PHP. Avant de récupérer les fichiers Drupal et de les installer dans le répertoire Web, nous devons préparer notre système avec quelques pré-requis, une LAMP / LEMP stack est primordiale pour faire tourner Drupal.

#### Créer une image Docker
Le premier objectif de ce chapitre est de créer une image Docker avec les points suivant:
	- *Image de base*: Alpine
	- *Version PHP*: 8.3
	- Dernière version de Drush https://www.drush.org/12.x/
	- Dernière version de Composer https://getcomposer.org/download/
	- Extensions PHP: php-mbstring, php-pecl-memcached, php-gd, php-pecl-zip, php-intl
	- Installation de l'extension PHP Memory Profiler depuis la source (https://github.com/arnaud-lb/php-memory-profiler).

**Spécifications techniques:**
	- Utilisé uniquement php-fpm (NGINX doit se branché avec une socket php-fpm)
	- Les services doivent s'exécutée par un utilisateur non privilégié (ex: www-data).
#### Mettre en place une image NGINX
Le deuxième objectif à mettre en place une image NGINX:
	- *Image de base*: Alpine
	 - Version NGINX : 1.25.5

**Spécifications techniques:**
	- NGINX doit se branché avec une socket php-fpm
	- NGINX doit s'exécutée par un utilisateur non privilégié (ex: www-data).
	- l'accès à NGINX doit être protégé par un schéma d'authentification basique (Basic Auth): test/test

#### Décrire la stack applicatif
Le troisième objectif consiste à relier et à décrire ces services via un fichier `docker-compose.yml`. Le dernier service qui va se rajouter c'est la base de données MySQL 8.3.

Le fichier `docker-compose.yml` doit contenir les services suivants: php, nginx et mysql. Il doit gérer l'inter-communication entre ces services; la persistance des données de la base de données ainsi que le dossier Drupal `sites/default/files`.

#### Challenges
Une fois ces éléments sont mis en place, nous pouvons procéder à l'installation du Projet et à la résolution des problèmes suivants:

**Gestion des erreurs**
- Sur Drupal la page /broken renvoie une erreur 500 sans message d'indication. Identifier le message d'erreur et proposer une solution.

**Optimisation de la mémoire**
- Identifier et corriger le code responsable de la consommation excessive de mémoire sur la page /heavy à l'aide de l'extension PHP Memory Profiler.
- Proposer une valeur memory_limit à configurer sur php

**Optimisation des Requêtes SQL**
 - Identifier la requête SQL problématique sur la page /slow à l'aide d'un outil de profilage des requêtes lentes (ex: slow_query).
 - Optimiser la requête SQL pour réduire son temps d'exécution.

**Gestion des connexions simultanées**
- Effectuer un test Apache Benchmark avec 500 utilisateurs sur la page /crash pour simuler des connexions simultanées.
- Identifier l'erreur ou le goulot d'étranglement qui provoque le crash de la base de données.
- Proposer et mettre en œuvre une solution pour résoudre le problème de crash de la base de données.
### Chapitre 2: Installation d'une application React connecté avec l'API Drupal

 Une fois la partie back-end déployer, nous pouvons procéder au déploiement de l'application React qui va se connecté à l'api fournis par Drupal (/api e.g http://localhost:8080/api).

- L'application React doit être déployer avec Docker
- Configurer la variable d'environnement API_URL pour quelle pointe sur l'endpoint `/api` fournis par Drupal.
- Cette variable d'environnement doit être contrôlé par le runtime Docker (éphémère). c'est-à-dire lancer le même container avec une autre valeur API_URL doit être pris en considération sans intervention niveau code (`.env`).
#### Challenges
**Appel Fetch / XHR qui ne passent pas sur la route /fetch :**

- Analyser pourquoi les appels XHR ne passent pas sur la route /fetch.
- Identifier les éventuelles erreurs de configuration ou de réseau.
- Proposer une solution pour résoudre ce problème sans intervention côté Drupal.

**Problème des appels XHR sur la Page /users :**
- Examiner pourquoi les appels XHR ne passent pas sur la page /users.
- Identifier les éventuelles erreurs de configuration ou de permissions.
- Proposer une solution pour résoudre ce problème.

 **Optimisation du téléchargement des Assets :**
- Analyser pourquoi les assets sont téléchargés à chaque recharge de page.
- Proposer des méthodes pour optimiser le téléchargement des assets, comme la mise en cache côté client (ce cache doit prendre en considération le rafraîchissement des différents type d'assets: fonts, css, images et JavaScript). L'application sera déployer régulièrement chaque jours et souvent c'est du code JS qui sera modifié. 

**Correction de la Faille XSS sur la Page /security :**
- Identifier la faille XSS présente sur la page /security.
- Proposer une solution en mettant en place une politique de sécurité des contenus (CSP) pour prévenir les attaques XSS.
