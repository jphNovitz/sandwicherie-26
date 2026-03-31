# Regles medias V1

Cadre de reference pour les medias du socle Sandwicherie V1.  
Objectif : garder un rendu propre, credible et administrable sans introduire une media library complexe en V1.

## Principes generaux

- La V1 manipule uniquement des references media simples : URL ou chemin stocke en base.
- Les formats acceptes doivent rester courants et simples a fournir par un client non technique.
- Chaque type de media a un usage distinct et ne doit pas etre reutilise au hasard.
- Le front doit rester robuste si un media manque.
- Une image correcte mais simple vaut mieux qu'un media trop lourd, trop petit ou mal cadre.

## Medias couverts

La V1 couvre ces medias :

- logo
- image hero principale
- images de galerie
- images de categories
- images de produits

## Formats autorises

Formats recommandes :

- `jpg` / `jpeg`
- `png`
- `webp`

Formats a eviter en V1 :

- `svg` pour les photos
- `gif` anime
- `bmp`
- `tiff`
- fichiers non optimises issus directement d'un appareil photo

## Regles par type de media

### Logo

Usage principal :

- identifier l'enseigne
- apparaitre dans le header
- apparaitre dans le footer
- pouvoir servir plus tard a d'autres usages de marque

Recommandations :

- format horizontal ou quasi horizontal
- bonne lisibilite sur fond clair
- peu de details fins
- fond transparent prefere si `png`, sinon fond propre et neutre

Ratio recommande :

- libre, avec preference pour une composition horizontale stable

Dimensions conseillees :

- recommande : `400 x 160 px`
- minimum conseille : `240 x 96 px`

Poids conseille :

- cible : `<= 200 KB`
- maximum recommande : `300 KB`

Fallback V1 :

- si le logo est absent, afficher le nom de l'enseigne en texte

### Image hero principale

Usage principal :

- grand visuel d'accueil
- premiere impression du site
- renfort d'identite et de qualite percue

Recommandations :

- photo lumineuse, nette, chaleureuse
- privilegier un cadrage paysage
- eviter les visuels surcharges ou trop publicitaires
- le sujet doit rester lisible meme en recadrage mobile

Ratio recommande :

- `16:9` prioritaire
- `3:2` acceptable

Dimensions conseillees :

- recommande : `1600 x 900 px`
- minimum conseille : `1280 x 720 px`

Poids conseille :

- cible : `<= 500 KB`
- maximum recommande : `800 KB`

Fallback V1 :

- si l'image hero est absente, conserver un hero textuel avec fond de couleur, gradient ou surface sobre

### Galerie

Usage principal :

- montrer l'ambiance
- illustrer le lieu, le comptoir, le service ou quelques produits
- credibiliser le commerce

Recommandations :

- conserver une cohérence de traitement entre les images
- preferer 4 a 8 visuels utiles plutot qu'une galerie trop longue
- melanger ambiance, produit, comptoir et service avec moderation
- eviter les images trop semblables

Ratio recommande :

- `4:3` ou `3:2`
- un melange limite est acceptable si l'ensemble reste visuellement coherent

Dimensions conseillees :

- recommande : `1200 x 900 px`
- minimum conseille : `900 x 675 px`

Poids conseille :

- cible : `<= 350 KB` par image
- maximum recommande : `500 KB`

Fallback V1 :

- si la galerie est vide, masquer la section

### Image de categorie

Usage principal :

- illustrer une categorie de la carte
- enrichir une mise en avant
- aider a differencier les blocs de contenu

Recommandations :

- image simple, lisible, representative
- ne pas dependre d'elle pour comprendre la categorie
- garder une coherence de style entre categories

Ratio recommande :

- `4:3`
- `1:1` acceptable si le front l'utilise en carte compacte

Dimensions conseillees :

- recommande : `960 x 720 px`
- minimum conseille : `800 x 600 px`

Poids conseille :

- cible : `<= 300 KB`
- maximum recommande : `450 KB`

Fallback V1 :

- si l'image de categorie est absente, afficher uniquement le nom, la description et la structure de carte

### Image de produit

Usage principal :

- enrichir certaines mises en avant
- illustrer ponctuellement des produits
- rester secondaire sur la carte

Recommandations :

- priorite a la lisibilite du produit
- cadrage simple, sans decor parasite
- ne pas rendre le front dependant d'une image pour chaque produit

Ratio recommande :

- `4:3`
- `1:1` acceptable

Dimensions conseillees :

- recommande : `800 x 600 px`
- minimum conseille : `640 x 480 px`

Poids conseille :

- cible : `<= 250 KB`
- maximum recommande : `400 KB`

Fallback V1 :

- si l'image produit est absente, conserver un affichage purement textuel sans bloc casse

## Coherence visuelle attendue

- pas de logos flous ou illisibles
- pas d'images etirees
- pas de medias trop petits reutilises en grand format
- pas de melange fort entre photos tres sombres et photos tres saturees
- pas de galerie surchargee
- pas d'usage systematique d'images produits sur chaque fiche si le rendu texte suffit

## Regles de fallback

- logo absent : texte de l'enseigne
- hero absent : hero editorial sans image
- galerie vide : section masquee
- image de categorie absente : bloc textuel standard
- image produit absente : produit affiche normalement sans visuel

Le site doit rester presentable meme avec peu de medias.

## Recommandations d'administration client

- montrer une aide de format sous chaque champ media dans l'admin
- indiquer le ratio recommande plutot qu'une contrainte trop rigide
- rappeler le poids cible pour eviter les uploads trop lourds
- encourager un texte alternatif sur la galerie
- ne pas bloquer la publication d'un contenu si le media optionnel est absent

## Consequences pour la suite du projet

Ces regles serviront ensuite a :

- ajouter des aides de saisie dans EasyAdmin
- definir les validations simples sur les champs media
- choisir les fallbacks du front
- documenter la prise en main client
- cadrer une future gestion d'upload ou de redimensionnement

## Hors perimetre V1

Ne fait pas partie de cette V1 :

- media library avancee
- recadrage interactif
- variantes automatiques multiples
- compression serveur poussee
- CDN
- workflow editorial complexe
- DAM ou gestion documentaire
