### Normalisation [De l'application vers l'extérieur] :
> - D'un objet Doctrine vers un objet JSON
> - D'une collection Doctrine ver une collection JSON
> - Récupération de données auprès de l'API
> - Seules les données du groupe choisi sont exportées

### Dénormalisation [De   l'extérieur vers l'application] :
> - D'un objet JSON vers un objet Doctrine
> - D'une collection JSON vers une collection Doctrine
> - Envoi de données vers l'API
> - Seules les données du groupe choisi sont intégrées


### Notion de groupes :
>Permettre de définir quelles sont les donn"es visibles lors de laa normalisation et quelles sont les données
modifiables lors de la dénormalisation.


### Opérarions de collection :
> Ce sont des opérations qui vont permettre de recevoir la listes des ressources ou d'en créer SANS PRENDRE D'IDENTIFIANT.

### Opérations de ressource :
> Ce sont des opérations qui vont permettre de recevoir une ressource ou de la modifier, ILS OPÈRENT SUR UNE RESSOURCE.