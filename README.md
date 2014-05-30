# Insannu

L'Insannu est un annuaire étudiant pour l'INSA de Rennes. Il ne peut être installé que sur le réseau interne de l'école. Il présente uniquement les données de l'annuaire officiel avec plus de possibilités de recherches.


## Comment l'installer ?

L'Insannu nécessite un serveur HTTP tel que Apache ou Nginx avec PHP >5.0.
Les modules suivants doivent être installés :
* pdo_sqlite
* sqlite3
* date
* curl (pour l'installation uniquement)
Le site est prévu pour être installé sur un Raspberry Pi.

Une fois le site installé, la base de données peut être mise à jour depuis le dossier /install/.
Il est conseillé de bloquer l'accès à ce dossier à toutes les adresses externes.


## Comment contribuer ?

* Vous pouvez rapporter un bug en ouvrant un issue.
* Toutes les contributions sont les bienvenues. Il est conseillé d'en discuter d'abord avec les mainteneurs du projet.
