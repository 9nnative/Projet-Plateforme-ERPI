# ProjectInnov
## Plateforme de gestion de projet d'innovation

## Structure du projet


## Procédure de démarrage pour le DEV
1. Démarrage des containers `docker-compose up -d --build`
2. identifier l'id du container `docker ps`
3. Acceder au terminal du container `docker exec -it XXXX bash`
4. Acceder au projet symfony `cd projectinnov`
5. Démarrer le server local de test 
``` sh  
compose install 
symfony server:start
```

## Commandes Composer
``` sh
composer install --no-scripts
# 
```



## Commandes Docker

Demarrer le container PHP
``` sh
docker-compose up -d --build
# -d pour detacher, c'est à dire que les logs ne sont pas affiché. Le container est actif en tâche de fond.
# --build recreer le container depuis zero, necessaire sur les parametre d'un Dockerfile a été modifié

```

Pour avoir acces au container (notament celui acceuillant PHP)
``` sh
docker ps #liste les containers actifs
docker exec -it XXXX bash 
# exec : executer une commande, ici 'bash'
# -it pour avoir une connection direct avec le terminal du container
# XXXX les premiers chiffres de l'identifiant du container 
```

Arreter les containers
``` sh
docker-compose down
```

# Sources et documentation

## Documentation officiel
[Symfony deployment](https://symfony.com/doc/current/deployment.html)

## exemples Docker + Symfony
[](https://www.clemdesign.fr/blog/2020/docker-configurer-un-projet-symfony-partie-1-deployer-php-fpm-et-nginx)
[](https://webdevpro.net/utiliser-symfony-dans-docker/)

