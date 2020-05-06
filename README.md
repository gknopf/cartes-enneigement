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

 Principe


Partie extraction

L'exportation des 10 cartes 3D des Alpes   est réalisée grace au plugin Qgis2Treejs vrsion 2.4. de Qgis en 1 minute.

Le fichier necessaire pour réaliser cette tâche est carte3D.py présenté sur github .
Explication

Désactiver toutes les cartes FSC<massif><date>.tif qui sont les cartes d'enneigements des alpes au format TIF importées du site Cryoland.

Importer une nouvelle carte TIF

Gerer une grande boucle qui va traiter  tous les massifs de la liste <listebassin>

Dans cette boucle, redimensionner le massif à la taille de l'écran(canvas)

Calculer la taille du canvas et son centre

exporter la scene avec l'API exporter.export de qgis2threejs

exporter la texture au format JPEG par la commande canvas.saveImage

reecriture de scenejson dans le répertoiure /data. Remplacer  a0.png par <texture>.jpeg

Les fichiers a0.png sont ensuite supprimés.
Partie affichage

fichiers necessaires: recouvreneige.php( fichier principal) listefsc , fsc.php(fichier de transition , Qgis2threejs.js qui est le fichier original de qgis2threejs modifié pour extraire la position de la caméra.
explication

Le fichier recouvreneige.php va récuperer toutes les cartes  disponibles d'un massif pour une saison donnée et les afficher sous forme d'un diaporama avec les boutons de commandes <precedent> et <suivant> . Le bouton <figer les cartes>  fixe la camera pour orienter chaque carte selon le meme angle de vue.

Commande précédant et suivant

Pour la visualisation d'une carte le programme va calculer la carte precédente et suivante en mémoire cache pour favoriser la fluidité de l'affichage. La 1ere carte sera plus longue a afficher  mais c'est le prix a payer pour la fluidité.

 Commade camera

Dans le fichier Qgis2Threejs.js la position de la caméra est calculée dans la fonction currentViewURL() qui renvoie une URL Je vais utiliser cette fonction dans l'évenement <mouseUP>( lignes 790 et suivantes) pour envoer cette URL par une commande AJAX  au fichier fsc.php qui la transmet au fichier principal comme variable de session.

Je vais ainsi orienter les autres cartes du diaporama avec la même position de la camera.
