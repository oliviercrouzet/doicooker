# Plugin DOIcooker pour Lodel
Ce plugin permet de produire un fichier de dépot de DOI au format XML à destination de l'organisme CROSSREF. Ce fichier peut être créé pour déclarer un numéro de revue complet ou pour un article isolé. Il est possible de choisir dans les paramètres le type d'entité que l'on souhaite moissonner (article, compte-rendu, etc.). Par défaut, seuls les articles le sont. 

> **IMPORTANT** :  
Le choix a été fait d'utiliser comme suffixe l'id d'enregistrement de l'article (ou du numéro) dans la base de données. Celui-ci est automatiquement ajouté au préfixe du doi renseigné en paramètre du plugin pour former le DOI complet de l'entité. Il est donc essentiel qu'il ne soit jamais modifié dès lors que le DOI est déclaré. En cas de modification ultérieure apportée au contenu de l'article, il ne faudra surtout pas procéder par une suppression suivie d'un nouvel ajout du document pour le mettre à jour mais simplement utiliser la fonction de Lodel *Recharger le document*.

## Prérequis
- Lodel 1.0
- Modèle éditorial >= 1.0.0
## Installation
Dans le répertoire `share/plugins/custom/` de votre installation, dézippez l'archive du plugin ou clonez le dépôt :
```
git clone https://github.com/oliviercrouzet/doicooker/.git
```
## Activation
> Le niveau _Administrateur Lodel_ est requis pour l'activation et la configuration décrite dans cette section.

Accédez à l'administration des plugins de votre installation lodel (Administrer/Plugins).  
> url =>  `https://votreinstallation/lodeladmin/index.php?do=list&lo=mainplugins`  

Vous pouvez alors le « copier » sur chaque site après avoir renseigné les paramètres communs à tous les sites.  
Ces paramètres sont éditables via le lien *Configurer*.

  * Si on clique sur *Installer sur tous les sites*, le plugin est non seulement copié mais aussi directement activé au niveau de chaque site, ce qui n'est pas forçément souhaitable.
  * Si, on clique sur *Activer*, le plugin est installé mais pas activé (oui, c'est un rien contre-intuitif !). Sur chaque site, on retrouve le plugin dans le tableau des plugins (Administration/Plugins).

On peut, si nécessaire, modifier les paramètres au niveau du site en cliquant sur *Configurer* . L'astérisque indique que le champ est obligatoire.  

## Usage

Une fois le plugin activé sur un site, et dans la mesure où l'utilisateur dispose des permissions suffisantes définies dans le paramètre _Droits utilisateurs_ de la config, un lien apparait en édition de numéro ou d'article dans l'encadré *Fonctions.* 

   * Le lien *Afficher* permet de donner une vue indentée et facilement lisible à la sortie xml afin de vérifier si tout est correct.
    A noter que, si une extension de type blocage de publicité est installée sur votre navigateur, il vous faudra la désactiver ou placer l'url du site sur liste blanche afin que l'indentation puisse s'appliquer.
   * Le lien *Télécharger* exporte le résultat dans un fichier xml prêt à être déposé sur le site de CROSSREF.

## Désinstallation

Pour modifier la **nature** des paramètres (leur caractère obligatoire ou non par exemple) et en général toutes données que l'on trouve dans le fichier du plugin _config.xml_, on est obligé de désinstaller sur tous les sites.  
La désinstallation complète d'un plugin ne peut pas se faire au niveau d'un site particulier via l'interface graphique.  
A priori, on serait donc tenté d'utiliser la fonction présente au niveau de l'administration générale (*Désinstaller sur tous les sites*) mais elle ne semble pas très bien fonctionner.  
De toute manière, il faut encore supprimer, dans la base sql principale, la ligne qui concerne le plugin dans la table _mainplugins_.

Le mieux est de procéder de la manière suivante :

1. Désactiver le plugin d'abord dans l'Administration générale.
2. Si vous avez plusieurs sites à traiter, utilisez plutôt le script [*delete\_doicooker\_instances.php*](https://github.com/oliviercrouzet/doicooker/tree/outils) qui va effacer l'enregistrement du plugin sur chaque site ainsi que sur l'administration générale.    
      Sinon, via mysql directement > effacer directement la ligne *doicooker* dans la table *plugins* du site puis dans la table *mainplugins* du site principal.
3. Vous pourrez alors modifier le fichier *config.xml* puis recharger la page Plugins de l'Administration générale.

## Crédits
Plugin réalisé d'après la documentation fournie par Jean-François Rivière (merci à lui) :  
https://github.com/OpenEdition/lodel/wiki/Plugins
