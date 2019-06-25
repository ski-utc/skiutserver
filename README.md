# skiutserver
Backend de ski utc

## TODO

- Refactoring du setup de base => passage en python ce semestre

- Page d'accueil
- Tombola
- Shotgun
- Shotgun physique (pour que les gens puissent rentrer leurs infos plus facilement)
- Entrée des infos + paiement pour les personnes qui ont shotgun
- Interface pour l'envoi de mails (Reprendre sur le serv de l'UTC)
- Shotgun des chambres
- Entrée des infos pour les tremplins et tout aussi
- Page pour choisir les animations
- Page pour les viennoiseries


## API

Ginger est l'API pour obtenir toutes les info sur les gens grâce à leur login UTC.
https://github.com/simde-utc/ginger  
Pour l'utiliser il faut prendre les fichiers du client :
https://github.com/simde-utc/ginger-client

## SCSS
Pour faciliter l'écriture et la lecture du code CSS, nous utilisons Sass : https://sass-lang.com/install  
Après avoir installé Sass, pour générer le script css, taper en ligne de commande :  
~~~
sass 2019/css/homepage.scss 2019/css/index.css
~~~

## Serveur OVH
L'exécution des scripts Node.js se fait sur un serveur distant:
```
$ ssh root@51.75.200.68
```

Le serveur possède la version 10 de Node, serveur Apache2 et MySQL consultable depuis `51.75.200.68/phpmyadmin`
La base de données locale contient une trace des transactions effectuées avec payutc.

## Python
Liste des choses à prévoir pour l'API : 
+ Connexion à la BDO
+ Login cas, login tremplin
+ Logout
+ Calcul des options pour un user ( cours, pack, bouf etc )
+ Shotgun ( pack, chambre et physique)
+ Tombola


setup : 
pip install bottle


Apache2 et mod_wsgi : 
une .conf 
````buildoutcfg
<VirtualHost *:80>
    ServerName assos.utc.fr

    WSGIDaemonProcess skiutserver user=_www group=_www processes=1 threads=5
    WSGIScriptAlias /v1 //!-PathToDefine-!//skiutserver/bottle.wsgi

    <Directory /!-PathToDefine-!/skiutserver>
        WSGIProcessGroup skiutserver
        WSGIApplicationGroup %{GLOBAL}
        Order deny,allow
        Allow from all
    </Directory>
</VirtualHost>
````

un .wsgi
