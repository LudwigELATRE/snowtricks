# Projet : Site Collaboratif de Snowboard

Jimmy Sweat, un entrepreneur ambitieux et passionné de snowboard, a pour objectif de créer un site collaboratif destiné à promouvoir ce sport auprès du grand public et à aider à l'apprentissage des figures (tricks). Il souhaite exploiter du contenu généré par les internautes pour développer un site riche et attractif. À terme, Jimmy envisage de monétiser le site en établissant des partenariats avec des marques de snowboard, profitant du trafic généré par le contenu.

## Prérequis

Assurez-vous d'avoir installé les outils suivants sur votre machine :
- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)
- [Symfony CLI](https://symfony.com/download)

## Installation

1. Clonez ce dépôt sur votre machine locale :

    ```bash
    git clone https://github.com/votre-utilisateur/projet-symfony-simple.git
    cd projet-symfony-simple
    ```

2. Installez les dépendances PHP avec Composer :

    ```bash
    composer install
    ```

## Utilisation

### Démarrer le serveur Symfony

Pour démarrer le serveur de développement Symfony, utilisez la commande suivante :

```bash
symfony server:start
```

## Utiliser Docker

Ce projet inclut une configuration Docker pour exécuter l'application dans un conteneur. Utilisez les commandes suivantes pour démarrer et arrêter le conteneur.

### Démarrer le conteneur

Pour démarrer le conteneur Docker, utilisez la commande suivante :
```bash
docker-compose up -d
```
