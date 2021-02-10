# delete_doicooker_instances.php

Ce script supprime l'enregistrement du plugin *doicooker* dans la base de chaque site indiqué en argument ainsi que son enregistrement dans la table mainplugins du site maître.  
Avec l'argument all, tous les sites sont ciblés.  
On peut exlure du traitement certains sites en les ajoutant dans le tableau $exclude.

## Installation

Placer le script `delete_doicooker_instances.php` à la racine de l'installation lodel et lancer :

```
php delete_doicooker_instance.php nom_du_site
```

Le script peut être supprimé après l'exécution.
