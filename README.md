# Plugin Lodel DOICOOKER
Ce plugin permet de produire un fichier de dépot de DOI au format XML à destination de l'organisme CROSSREF. Ce fichier peut être créé pour un numéro complet ou pour un article isolé. Il est possible de choisir dans les paramètres le type d'entité que l'on souhaite moissonner (article, compte-rendu). Par défaut seuls les articles le sont.

## Installation
Dans le répertoire `share/plugins/custom/` de votre installation, dézippez l'archive du plugin ou clonez le dépôt :
```
git clone https://github.com/oliviercrouzet/doicooker/.git
```
## Activation
 Accédez à l'administration des plugins de votre installation lodel (Administrer/Plugins). 
     url =>  `https://votreinstallation/lodeladmin/index.php?do=list&lo=mainplugins`  
 Vous pouvez alors le « copier » sur chaque site après avoir renseigné les paramètres communs à tous les sites.  
Ces paramètres sont éditables via le lien *Configurer*.

  * Si on clique sur *Installer sur tous les sites*, le plugin est non seulement copié mais aussi directement activé au niveau de chaque site, ce qui n'est pas forçément souhaitable.
  * Si, on clique sur *Activer*, le plugin est installé mais pas activé (oui, c'est un rien contre-intuitif !). Sur chaque site, on retrouve le plugin dans le tableau des plugins (Administration/Plugins).

On peut, si nécessaire, modifier les paramètres au niveau du site en cliquant sur *Configurer* . L'astérisque indique que le champ est obligatoire.  
Lorsque la maquette Nova est utilisée le préfixe DOI est cherché d'abord dans l'option Extra/Préfixe des DOI.   
Sinon, il faut bien évidemment remplir ce champ.

## Usage

Une fois le plugin activé sur un site, un lien apparait en édition de numéro ou d'article dans l'encadré *Fonctions.* 

   * Le lien *Afficher* permet de donner une vue indentée et lisible à la sortie xml et vérifier si tout est correct.
    A noter que, si une extension de type blocage de publicité est installée sur votre navigateur, il vous faudra la désactiver ou placer l'url du site sur liste blanche afin que l'indentation puisse s'appliquer.
   * Le lien *Télécharger* exporte le résultat dans un fichier xml prêt à être déposé sur le site de CROSSREF.

## Désinstallation

Pour modifier la **nature** des paramètres (leur caractère obligatoire ou non par exemple) et en général toutes données que l'on trouve dans le fichier du plugin _config.xml_, on est obligé de désinstaller sur tous les sites.  
La désinstallation complète d'un plugin ne peut pas se faire au niveau d'un site particulier via l'interface graphique.  
A priori, on serait donc tenté d'utiliser la fonction présente au niveau de l'administration générale (*Désinstaller sur tous les sites*) mais elle ne semble pas très bien fonctionner.  
De toute manière, il faut encore supprimer dans la base sql principale la ligne qui concerne le plugin dans la table _mainplugins_.

Le mieux est de procéder de la manière suivante :

1. Désactiver le plugin d'abord dans l'Administration générale.
2. Si vous avez plusieurs sites à traiter, le mieux est d'utiliser le script *delete\_doicooker\_instances.php* qui va effacer l'enregistrement du plugin sur chaque site ainsi que sur l'administration générale.    
      Sinon, via mysql directement > effacer directement la ligne *doicooker* au plugin dans la table *plugins* du site puis dans la table *mainplugins* du site principal.
3. On peut alors modifier son fichier *config.xml* puis recharger la page Plugins de l'Administration générale.

## Crédits
Plugin réalisé d'après la documentation fournie par Jean-François Rivière (merci à lui) :  
[Plugins](https://github.com/OpenEdition/lodel/wiki/Plugins)
