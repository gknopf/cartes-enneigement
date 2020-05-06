# cartes-enneigement

Objectif
Afficher en cartes 3D  les limites d'enneigement des massifs Alpins sous forme de diaporama, pour visualiser l'évolution du domaine enneigé au cours d'une saison hivernale.

Exemple de réalisation https://nivo06.knobuntu.fr/index.php/cryoland


Contraintes

    L'extraction doit etre rapide:
    .
    Les cartes ne doivent pas etre trop volumineuses pour un affichage fluide en diaporama.
    .
    Toute la série des cartes du diaporama d'une saison pour un massif donné doivent etre vues sous un meme angle
    .
    Le programme doit etre réalisé avec les dernières versions de Qgis et Qgis2threejs
    .

Resolution des contraintes

    Les cartes sont extraites de Qgis avec un script python  traitant les 10 massifs des Alpes en 1 minute.
    .
    Qgis2threejs exportes des fichier de couverture au format PNG qui pèsent 2.7 Mo. je vais les adapter au format JPEC 350 Ko  soit 7 fois moins volumineuses . En adaptant le facteur de grossissement on arrive à un résultat tout à fait correct .
    .
    Qgis2threejs.js est construit avec une fonction qui calcule la position de la camera et la transmet sous forme d'URL. Je vais utiliser la fonction app.currentViewUrl() avec l'évenement "mouseUp" pour  transferer l'URL via Ajax au programme principal.
    .
    Pour extraire les cartes 3D avec la version 1.4 de Qgis2threejs j'ai cosacré une partition de mon disque uniquement a cette tâche.Ce n'était pas viable et j'ai réécrit les programmes avec  les nouvelles version de QGIs3.6, Python3 et Qgis2threejs 2.4

 
