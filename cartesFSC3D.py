
import os
import base64 
from PyQt5.QtCore import *
from PyQt5.QtGui import *
from qgis.core import *
from qgis.gui import *
from qgis.utils import *
from Qgis2threejs.export import ThreeJSExporter, ImageExporter    # or ModelExporter

# texture base size
TEX_WIDTH, TEX_HEIGHT = (1024, 600)

path_to_settings = "/home/knobuntu/QGis/python/export.qto3settings"   # path to .qto3settings file




#from Qgis2threejs import Exporter

#chargement du fichier FSC a partir du repertoire /home/knobuntu/QGis/FSC
#initialisation
#desactiver toutes les cartes FSC
for layer in QgsProject.instance().mapLayers().values():
    print (layer)
    couchename= layer.name()
    
    x = couchename.find ('FSC')
    if x >-1 :
        #lyr=QgsMapLayerRegistry.instance().mapLayerByName(couchename)[0]
        print (couchename)
        QgsProject.instance().layerTreeRoot().findLayer(layer.id()).setItemVisibilityChecked(False)
       # layer.setItemVisibilityChecked(False)
      # qgis.utils.iface.legendInterface().setLayerVisible(layer, False)
      #iface.legendInterface().setLayerVisible(layer,False)
    x=couchename .find('dem')
    if x> -1:
        iface.setActiveLayer(layer)
        print (couchename)
    
qfd=QFileDialog()
title='ouvrir un fichierraster FSC'
path="/home/knobuntu/QGis/FSC"
f=QFileDialog.getOpenFileName(qfd,title,path)
print (f)
liste=f[0].split('/')
inter=liste[-1]
liste=inter.split('.')
fscusuel=liste[0]
print (fscusuel)

layer= iface.addRasterLayer(f[0],fscusuel)

#chargement du fichier stylefsc.qml des distinction des couches de neige
layer.loadNamedStyle('/home/knobuntu/QGis/styles/stylefsc.qml')
layer.triggerRepaint()

# fichier de configuration dans /home/knobuntu/QGis/cryoland/FSC3D/config/config.qto3settings a reexporter lors de changements
settingsPath = "/home/knobuntu/QGis/cryoland/config/config.qto3settings"

#

#grandboucle
listebassin=['arc','tarentaise','maurienne','chamonix','deux-alpes','vesubie','tinee','ubaye','le_guil','ecrins']
#listebassin=['vesubie','arc']

for bassin in listebassin:
    print (bassin)
    # centrage sur la couche vectorielle 'tinee ' charge dans le projet
    layer = QgsProject.instance().mapLayersByName(bassin)[0]
    layer.updateExtents()
    canvas=iface.mapCanvas()
    canvas.setExtent(layer.extent())
    canvas.refresh()
    w=QWidget()

    info="Attendre le rafraichissement et le repositionnement sur  "+ bassin
    QMessageBox.information(w, "Rafraichissement en cours","attendre le rafraichissement complet des couches")    
    #mesures  du canvas
       
    e= canvas.extent()
    xmax=e.xMaximum()
    xmin= e.xMinimum()
    ymin=e.yMinimum()
    ymax=e.yMaximum()
    print (xmax/2)
    centrex= xmin+(xmax-xmin)/2
    centrey=ymin+(ymax-ymin)/2
    etendueX=(xmax-xmin)
    etendueY=(ymax-ymin)
    xpixel=canvas.size().width()
    ypixel=canvas.size().height()

    places = [(bassin, QgsPoint(centrex,centrey))]
    # fichier de sortie html
    path_tmpl = "/home/knobuntu/QGis/cryoland/FSC3D/" +bassin+fscusuel+ ".html"
    print (path)


    # Get map settings from the map canvas
    mapSettings = canvas.mapSettings()

  # apply the above extent to map settings
    #RotatedRect(center, width, height, rotation).toMapSettings(mapSettings)

 

    #
    # 1. export scene as web page
    #

    filename = "/home/knobuntu/QGis/cryoland/FSC3D/" + bassin+fscusuel+".html"

    exporter = ThreeJSExporter()
    exporter.loadSettings(path_to_settings)     # export settings (for scene, layers, decorations and so on)
    exporter.setMapSettings(mapSettings)        # extent, texture size, layers to be rendered and so on
    exporter.export(filename)



    # 2. export scene as image
    #

    filename = "/home/knobuntu/QGis/cryoland/FSC3D/image.png"

    # camera position and camera target in world coordinate system (y-up)
    CAMERA = {"position": {"x": -50, "y": 30, "z": 50},   # above left front of (central) DEM block
              "target": {"x": 0, "y": 0, "z": 0}}         # below (or above) center of (central) DEM block

    exporter = ImageExporter()
    exporter.loadSettings(path_to_settings)
    exporter.setMapSettings(mapSettings)

    #exporter.initWebPage(1024, 768)                       # output image size
    exporter.export(filename, cameraState=CAMERA)
    # Coordonnees de transformation de wgs en lambert (non utilise lesysteme de reference est deja 2154)

    
    # exportation de la texture
    couvertureUri="/home/knobuntu/QGis/cryoland/FSC3D/data/" + bassin + fscusuel +"/"+bassin+fscusuel+".jpeg"
    mapsettings =canvas.mapSettings()
    
    mapsettings.setDevicePixelRatio(1.5)
    job = QgsMapRendererSequentialJob(mapsettings)
    job.start()
    job.waitForFinished()
    image = job.renderedImage()
    image.save(couvertureUri,"jpeg")       # meilleure qualité d'image 150dpi

    # canvas.saveAsImage( couvertureUri,None,"JPEG")
    print (couvertureUri)
    # tableau des lieux a exporter

    #reecriture de scenejson
    fichier="/home/knobuntu/QGis/cryoland/FSC3D/data/" + bassin + fscusuel +"/scene.json"
  
    ficsortie="/home/knobuntu/QGis/cryoland/sortie.js"
    ficjs=open(fichier,'r')
    ficsid=open(ficsortie,'w')

    for ligne in ficjs:
        lg= ligne.split('a0.png')
        if len(lg)==2:
            txt= lg[0]+bassin+fscusuel+'.jpeg'+lg[1]
        else :
            txt=lg[0]
        
        
        lg= txt.split('"visible": false')
        if len(lg)==2 :
           
            txt= lg[0]+ '"visible": true'+lg[1]
        else :
            txt=lg[0]

        
        
        
        ficsid.write(txt)
    ficjs.close()
    ficsid.close()
    os.rename(ficsortie,fichier)


  
QMessageBox.information(w, "Fin", "Les fichiers html et js sont a transferer sur le site du repertoire QGis/cryoland/FSC3D vers /image/cryoland/cartes3d" )    

























