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

## Procédure de démarrage pour la PROD

!!!!! pas si simple il faut regener le dossier vendor en installant les packages !!!!!! 
!!!!! procédure à améliorer !!!!!! 
1. Charger les variable d'environnement `.env.php.prod` et `.env.mysql.prod`
2. Démarrage des containers `docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d`

[doc symfony](https://symfony.com/doc/current/deployment.html)


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

``` 
docker-compose up --force-recreate
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

Arreter les containers et supprimer les volumes !!! uniquement pour les testes 
```
docker-compose down -v
```

# Sources et documentation

## Documentation officiel
[Symfony deployment](https://symfony.com/doc/current/deployment.html)

## exemples Docker + Symfony
[Symfony, mysql & docker](https://www.clemdesign.fr/blog/2020/docker-configurer-un-projet-symfony-partie-1-deployer-php-fpm-et-nginx)
[Symfony & docker](https://webdevpro.net/utiliser-symfony-dans-docker/)
[](https://itnext.io/containerizing-symfony-application-a2a5a3bd5edc)
[](https://www.goetas.com/blog/how-do-i-deploy-my-symfony-api-part-1-development/)

## mysql initialization
[mysql official docker image](https://hub.docker.com/_/mysql)
[Stackoverflow mysql initialisation](https://stackoverflow.com/questions/38504257/mysql-scripts-in-docker-entrypoint-initdb-are-not-executed/52715521)