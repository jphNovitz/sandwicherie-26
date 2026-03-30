# Dictionnaire de donnees V1

Reference de travail pour le socle Sandwicherie V1.  
Ce document fige les champs utiles, leur statut, leurs contraintes minimales et leur usage attendu cote front/admin.

## Conventions transversales

| Champ | Type | Regle V1 | Commentaire |
| --- | --- | --- | --- |
| `position` | entier | Obligatoire, defaut `0`, valeur attendue `>= 0` | Sert a l'ordre manuel. Plus petit = plus haut. |
| `isActive` | booleen | Obligatoire, defaut `true` | Utilise pour les contenus/objets administrables qui peuvent etre desactives sans suppression. |
| `isVisible` | booleen | Obligatoire, defaut `true` | Utilise pour des contenus affiches publiquement mais masquables editorialement, comme les avis. |
| `createdAt` | datetime immutable | Renseigne automatiquement a la creation | Champ technique, non modifiable par le client. |
| `updatedAt` | datetime immutable | Mis a jour a chaque modification utile | Champ technique, non modifiable par le client. |
| `slug` | chaine | Obligatoire si expose en URL | Genere automatiquement si vide, puis editable. |
| `seoTitle` | chaine | Facultatif | Si vide, fallback sur le titre/nom visible. |
| `metaDescription` | texte court | Facultatif | Si vide, fallback gere au niveau du rendu. |

## `SiteSettings`

Reglages globaux du site. Ne doit pas contenir de contenu editorial de page.

| Champ | Libelle fonctionnel | Type | Obligatoire | Defaut | Visible front | Visible admin | Modifiable client | Regle metier | Commentaire |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `businessName` | Nom de l'enseigne | string(255) | Oui | Aucune | Oui | Oui | Oui | Nom principal du commerce | Affichage marque global |
| `slogan` | Accroche globale | string(255) nullable | Non | `null` | Oui | Oui | Oui | Doit rester court | Identite globale, pas contenu editorial |
| `phone` | Telephone | string(50) nullable | Non | `null` | Oui | Oui | Oui | Format libre en V1 | Utilise dans header/footer/contact |
| `email` | Email public | string(180) nullable | Non | `null` | Oui | Oui | Oui | Email valide recommande | Contact public |
| `whatsapp` | WhatsApp | string(50) nullable | Non | `null` | Oui | Oui | Oui | Format libre en V1 | Lien/contact public optionnel |
| `fullAddress` | Adresse complete | text nullable | Non | `null` | Oui | Oui | Oui | Peut rester vide | Utile pour contact, legal, carte |
| `shortAddress` | Adresse courte | string(255) nullable | Non | `null` | Oui | Oui | Oui | Peut rester vide | Variante compacte pour header/footer |
| `latitude` | Latitude | decimal(10,7) nullable | Non | `null` | Indirectement | Oui | Oui | Doit former une paire valide avec `longitude` pour la carte | Carte et itineraire |
| `longitude` | Longitude | decimal(10,7) nullable | Non | `null` | Indirectement | Oui | Oui | Doit former une paire valide avec `latitude` pour la carte | Carte et itineraire |
| `logo` | Logo | string(255) nullable | Non | `null` | Oui | Oui | Oui | Reference media simple en V1 | Pas d'upload complexe dans cette V1 |
| `heroImage` | Image hero globale | string(255) nullable | Non | `null` | Oui | Oui | Oui | Image globale, acceptable en V1 | Peut evoluer plus tard vers `Page(home)` |
| `notificationEmail` | Email de reception des messages | string(180) nullable | Non | `null` | Non | Oui | Oui | Email valide recommande | Purement technique/admin |
| `emailNotificationsEnabled` | Notifications email | bool | Oui | `true` | Non | Oui | Oui | Active/desactive l'envoi de notification | Purement technique/admin |
| `generalNotes` | Notes generales | text nullable | Non | `null` | Non | Oui | Oui | A reserver aux notes techniques internes | Ne doit pas devenir un champ fourre-tout |
| `createdAt` | Date de creation | datetime immutable | Oui | auto | Non | Oui | Non | Technique | Lecture seule |
| `updatedAt` | Date de mise a jour | datetime immutable | Oui | auto | Non | Oui | Non | Technique | Lecture seule |

## `Page`

Pages editoriales et SEO du site. Porte les contenus propres a `home`, `about`, `contact`, `legal`, `privacy`, `cookies`, `menu`.

| Champ | Libelle fonctionnel | Type | Obligatoire | Defaut | Visible front | Visible admin | Modifiable client | Regle metier | Commentaire |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `siteSettings` | Site de rattachement | relation many-to-one | Oui | Aucune | Non | Oui | Oui | Une page appartient a un site | Necessaire pour le socle multi-pages |
| `code` | Code de page | enum `PageCode` | Oui | Aucune | Indirectement | Oui | Oui | Unique, controle parmi `home/menu/about/contact/legal/privacy/cookies` | Cle fonctionnelle de la page |
| `title` | Titre visible | string(255) | Oui | Aucune | Oui | Oui | Oui | Doit toujours exister | Utilise aussi en fallback SEO |
| `slug` | Slug | string(255) | Oui | Genere a partir du titre | Oui | Oui | Oui | Unique, non vide | URL publique editable |
| `seoTitle` | Titre SEO | string(255) nullable | Non | `null` | Oui | Oui | Oui | Si vide, fallback sur `title` | SEO page par page |
| `metaDescription` | Meta description | string(500) nullable | Non | `null` | Oui | Oui | Oui | Si vide, fallback de rendu | SEO page par page |
| `introduction` / `intro` | Intro / chapeau | text nullable | Non | `null` | Oui | Oui | Oui | Utilisee surtout sur `home`, `about`, `contact` | Champ editorial court |
| `content` | Contenu principal | text nullable | Non | `null` | Oui | Oui | Oui | Peut etre vide pour certaines pages legeres | Corps editorial / legal |
| `isActive` | Active | bool | Oui | `true` | Indirectement | Oui | Oui | Desactive la page sans suppression | Reste structurelle dans le socle |
| `createdAt` | Date de creation | datetime immutable | Oui | auto | Non | Oui | Non | Technique | Lecture seule |
| `updatedAt` | Date de mise a jour | datetime immutable | Oui | auto | Non | Oui | Non | Technique | Lecture seule |

## `Category`

Categories de la carte.

| Champ | Libelle fonctionnel | Type | Obligatoire | Defaut | Visible front | Visible admin | Modifiable client | Regle metier | Commentaire |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `name` | Nom | string(255) | Oui | Aucune | Oui | Oui | Oui | Non vide | Libelle de la categorie |
| `description` | Description courte | text nullable | Non | `null` | Oui | Oui | Oui | Peut rester vide | Utilisee en front et en fallback de mise en avant |
| `image` | Image | string(255) nullable | Non | `null` | Oui | Oui | Oui | Reference media optionnelle | Pas d'upload complexe en V1 |
| `position` | Position | int | Oui | `0` | Indirectement | Oui | Oui | `>= 0` recommande | Ordre manuel |
| `isActive` | Active | bool | Oui | `true` | Oui | Oui | Oui | Une categorie inactive reste en base | Pilotage editorial |
| `slug` | Slug | string(255) | Oui | Genere a partir du nom | Oui | Oui | Oui | Unique, non vide | URL de categorie |
| `seoTitle` | Titre SEO | string(255) nullable | Non | `null` | Oui | Oui | Oui | Fallback sur `name` si vide | SEO categorie |
| `metaDescription` | Meta description | string(500) nullable | Non | `null` | Oui | Oui | Oui | Peut rester vide | SEO categorie |
| `createdAt` | Date de creation | datetime immutable | Oui | auto | Non | Oui | Non | Technique | Lecture seule |
| `updatedAt` | Date de mise a jour | datetime immutable | Oui | auto | Non | Oui | Non | Technique | Lecture seule |

## `Product`

Produits de la carte.

| Champ | Libelle fonctionnel | Type | Obligatoire | Defaut | Visible front | Visible admin | Modifiable client | Regle metier | Commentaire |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `category` | Categorie | relation many-to-one nullable | Non | `null` | Oui | Oui | Oui | Peut etre vide techniquement, mais une categorie est fortement recommandee pour une carte propre | Suppression de categorie restreinte si produits lies |
| `name` | Nom | string(255) | Oui | Aucune | Oui | Oui | Oui | Non vide | Libelle principal |
| `ingredients` / `description` | Ingredients | text nullable | Non | `null` | Oui | Oui | Oui | Peut rester vide | Texte libre V1 |
| `price` | Prix | decimal(10,2) | Oui | Aucune | Oui | Oui | Oui | Doit etre strictement positif en validation metier | Un seul prix simple en V1 |
| `image` | Image | string(255) nullable | Non | `null` | Oui | Oui | Oui | Optionnelle | Reference media simple |
| `position` | Position | int | Oui | `0` | Indirectement | Oui | Oui | `>= 0` recommande | Ordre manuel |
| `isAvailable` / `isActive` | Disponible | bool | Oui | `true` | Oui | Oui | Oui | Un produit indisponible ne doit pas remonter dans la carte publique | Champ d'activation du produit |
| `allergens` | Allergene(s) | many-to-many | Non | collection vide | Oui | Oui | Oui | Aucun allergene obligatoire, mais la liste doit etre fiable si renseignee | Relation de referentiel |
| `createdAt` | Date de creation | datetime immutable | Oui | auto | Non | Oui | Non | Technique | Lecture seule |
| `updatedAt` | Date de mise a jour | datetime immutable | Oui | auto | Non | Oui | Non | Technique | Lecture seule |

## `Allergen`

Referentiel d'allergenes.

| Champ | Libelle fonctionnel | Type | Obligatoire | Defaut | Visible front | Visible admin | Modifiable client | Regle metier | Commentaire |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `code` | Code | string(50) | Oui | Aucune | Non | Oui | Oui | Unique, non vide | Cle stable du referentiel |
| `name` | Libelle | string(255) | Oui | Aucune | Oui | Oui | Oui | Non vide | Libelle affiche au client |
| `position` | Position | int | Oui | `0` | Indirectement | Oui | Oui | `>= 0` recommande | Ordre manuel dans les listes |
| `isActive` | Actif | bool | Oui | `true` | Indirectement | Oui | Oui | Permet de masquer un allergene sans le supprimer | Referentiel cadrable |

## `OpeningHour`

Horaires du site.

| Champ | Libelle fonctionnel | Type | Obligatoire | Defaut | Visible front | Visible admin | Modifiable client | Regle metier | Commentaire |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `siteSettings` | Site de rattachement | relation many-to-one | Oui | Aucune | Non | Oui | Oui | Un horaire appartient a un site | Structure centrale |
| `dayOfWeek` | Jour | enum `DayOfWeek` | Oui | Aucune | Oui | Oui | Oui | Valeur controlee du lundi au dimanche | Base de l'ordre semantique |
| `isClosed` | Ferme | bool | Oui | `false` | Oui | Oui | Oui | Si `true`, les plages horaires peuvent rester vides | Jour ferme gere explicitement |
| `firstOpeningAt` | Premiere ouverture | time nullable | Non | `null` | Oui | Oui | Oui | A renseigner avec `firstClosingAt` si plage 1 ouverte | Optionnel |
| `firstClosingAt` | Premiere fermeture | time nullable | Non | `null` | Oui | Oui | Oui | Doit etre coherent avec `firstOpeningAt` | Optionnel |
| `secondOpeningAt` | Deuxieme ouverture | time nullable | Non | `null` | Oui | Oui | Oui | Seulement si deuxieme plage | Optionnel |
| `secondClosingAt` | Deuxieme fermeture | time nullable | Non | `null` | Oui | Oui | Oui | Doit etre coherent avec `secondOpeningAt` | Optionnel |
| `position` | Position | int | Oui | `0` | Indirectement | Oui | Oui | Derivable du jour mais stocke pour garder l'ordre manuel/simple | V1 simple |

## `SocialLink`

Liens sociaux/publics du site.

| Champ | Libelle fonctionnel | Type | Obligatoire | Defaut | Visible front | Visible admin | Modifiable client | Regle metier | Commentaire |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `siteSettings` | Site de rattachement | relation many-to-one | Oui | Aucune | Non | Oui | Oui | Un lien appartient a un site | Structure centrale |
| `type` | Type | enum `SocialLinkType` | Oui | Aucune | Oui | Oui | Oui | Valeur controlee (`facebook`, `instagram`, `tiktok`, `other`) | Evite les valeurs libres incoherentes |
| `label` | Libelle | string(255) nullable | Non | `null` | Oui | Oui | Oui | Peut rester vide | Libelle personnalise si necessaire |
| `url` | URL | string(500) | Oui | Aucune | Oui | Oui | Oui | URL valide requise en validation metier | Cible publique |
| `position` | Position | int | Oui | `0` | Indirectement | Oui | Oui | `>= 0` recommande | Ordre manuel |
| `isActive` | Actif | bool | Oui | `true` | Oui | Oui | Oui | Permet de masquer sans supprimer | Pilotage editorial |
| `createdAt` | Date de creation | datetime immutable | Oui | auto | Non | Oui | Non | Technique | Lecture seule |
| `updatedAt` | Date de mise a jour | datetime immutable | Oui | auto | Non | Oui | Non | Technique | Lecture seule |

## `GalleryImage`

Visuels de la galerie.

| Champ | Libelle fonctionnel | Type | Obligatoire | Defaut | Visible front | Visible admin | Modifiable client | Regle metier | Commentaire |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `siteSettings` | Site de rattachement | relation many-to-one | Oui | Aucune | Non | Oui | Oui | Un visuel appartient a un site | Structure centrale |
| `image` | Image | string(255) | Oui | Aucune | Oui | Oui | Oui | Reference media obligatoire | Pas d'upload complexe en V1 |
| `altText` | Texte alternatif | string(255) nullable | Non | `null` | Oui | Oui | Oui | Fortement recommande pour accessibilite, mais non bloquant en V1 | Peut rester vide |
| `position` | Position | int | Oui | `0` | Indirectement | Oui | Oui | `>= 0` recommande | Ordre manuel |
| `isActive` | Actif | bool | Oui | `true` | Oui | Oui | Oui | Un visuel inactif est conserve sans affichage public | Pilotage editorial |
| `createdAt` | Date de creation | datetime immutable | Oui | auto | Non | Oui | Non | Technique | Lecture seule |
| `updatedAt` | Date de mise a jour | datetime immutable | Oui | auto | Non | Oui | Non | Technique | Lecture seule |

## `Review`

Avis clients geres manuellement.

| Champ | Libelle fonctionnel | Type | Obligatoire | Defaut | Visible front | Visible admin | Modifiable client | Regle metier | Commentaire |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `siteSettings` | Site de rattachement | relation many-to-one | Oui | Aucune | Non | Oui | Oui | Un avis appartient a un site | Structure centrale |
| `firstName` | Prenom | string(100) | Oui | Aucune | Oui | Oui | Oui | Non vide | Auteur affiche sous forme courte |
| `lastInitial` | Initiale | string(5) | Oui | Aucune | Oui | Oui | Oui | Non vide, max 5, normalise en majuscules | Pas de nom complet en V1 |
| `content` | Texte | text | Oui | Aucune | Oui | Oui | Oui | Non vide | Contenu principal de l'avis |
| `rating` | Note | int | Oui | `5` | Oui | Oui | Oui | Entier entre 1 et 5 | Pas de demi-points |
| `sourceType` | Type de source | enum `ReviewSourceType` | Oui | `direct` | Oui | Oui | Oui | Valeur controlee (`google`, `facebook`, `direct`, `other`) | Source credibilisee |
| `sourceLabel` | Libelle de source | string(100) nullable | Non | `null` | Oui | Oui | Oui | Si vide, fallback sur le libelle par defaut du `sourceType` | Souplesse editoriale |
| `sourceUrl` | Lien de verification | string(500) nullable | Non | `null` | Oui | Oui | Oui | URL valide si renseignee | Jamais obligatoire en V1 |
| `position` | Position | int | Oui | `0` | Indirectement | Oui | Oui | `>= 0` | Ordre manuel |
| `isVisible` | Visible | bool | Oui | `true` | Oui | Oui | Oui | Permet de masquer sans supprimer | Utiliser `isVisible` plutot que `isActive` |
| `createdAt` | Date de creation | datetime immutable | Oui | auto | Non | Oui | Non | Technique | Lecture seule |
| `updatedAt` | Date de mise a jour | datetime immutable | Oui | auto | Non | Oui | Non | Technique | Lecture seule |

## `ContactMessage`

Messages issus du formulaire de contact.

| Champ | Libelle fonctionnel | Type | Obligatoire | Defaut | Visible front | Visible admin | Modifiable client | Regle metier | Commentaire |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `siteSettings` | Site de rattachement | relation many-to-one nullable | Non | `null` | Non | Oui | Non | Peut rester `null` si message recu hors contexte ou apres suppression du site | Technique |
| `name` | Nom | string(255) | Oui | Aucune | Non apres envoi | Oui | Oui au moment du formulaire public | Champ obligatoire du formulaire public | Donnee de contact |
| `email` | Email | string(180) | Oui | Aucune | Non apres envoi | Oui | Oui au moment du formulaire public | Email valide attendu | Donnee de contact |
| `phone` | Telephone | string(50) nullable | Non | `null` | Non apres envoi | Oui | Oui au moment du formulaire public | Facultatif, aucun format strict impose en V1 | Donnee secondaire |
| `message` | Message | text | Oui | Aucune | Non apres envoi | Oui | Oui au moment du formulaire public | Non vide | Contenu du message |
| `status` | Statut | enum `ContactMessageStatus` | Oui | `new` | Non | Oui | Oui en admin uniquement | Cycle attendu : `new` -> `read` -> `processed` | Suivi administratif |
| `createdAt` | Date de creation | datetime immutable | Oui | auto | Non | Oui | Non | Technique | Horodatage de reception |
| `updatedAt` | Date de mise a jour | datetime immutable | Oui | auto | Non | Oui | Non | Technique | Horodatage de suivi |

## `FeaturedItem`

Mises en avant d'accueil.

| Champ | Libelle fonctionnel | Type | Obligatoire | Defaut | Visible front | Visible admin | Modifiable client | Regle metier | Commentaire |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `siteSettings` | Site de rattachement | relation many-to-one | Oui | Aucune | Non | Oui | Oui | Une mise en avant appartient a un site | Structure centrale |
| `type` | Type | enum `FeaturedItemType` | Oui | Aucune | Indirectement | Oui | Oui | Valeur controlee `product` ou `category` | Conditionne la cible |
| `product` | Produit lie | relation many-to-one nullable | Non selon type | `null` | Indirectement | Oui | Oui | Obligatoire si `type = product`, interdit si `type = category` | Cible V1 possible |
| `category` | Categorie liee | relation many-to-one nullable | Non selon type | `null` | Indirectement | Oui | Oui | Obligatoire si `type = category`, interdit si `type = product` | Cible V1 possible |
| `customTitle` | Titre personnalise | string(255) nullable | Non | `null` | Oui | Oui | Oui | Si vide, fallback sur le nom de la cible | Editorial leger |
| `shortText` / `customText` | Texte personnalise | text nullable | Non | `null` | Oui | Oui | Oui | Si vide, fallback sur ingredients produit ou description categorie | Editorial leger |
| `showPrice` / `displayPrice` | Afficher le prix | bool | Oui | `false` | Oui selon cible | Oui | Oui | N'a de sens que pour un produit, force a `false` pour une categorie | Option d'affichage |
| `position` | Position | int | Oui | `0` | Indirectement | Oui | Oui | `>= 0` et ordre manuel | Affichage homepage |
| `isActive` | Actif | bool | Oui | `true` | Oui | Oui | Oui | Maximum 6 elements actifs par site | Pilotage editorial |
| `createdAt` | Date de creation | datetime immutable | Oui | auto | Non | Oui | Non | Technique | Lecture seule |
| `updatedAt` | Date de mise a jour | datetime immutable | Oui | auto | Non | Oui | Non | Technique | Lecture seule |

## `AboutHighlight`

Points forts de la page A propos.

| Champ | Libelle fonctionnel | Type | Obligatoire | Defaut | Visible front | Visible admin | Modifiable client | Regle metier | Commentaire |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `siteSettings` | Site de rattachement | relation many-to-one | Oui | Aucune | Non | Oui | Oui | Un point fort appartient a un site | Structure centrale |
| `title` | Titre | string(255) | Oui | Aucune | Oui | Oui | Oui | Non vide | Intitule principal |
| `shortText` | Texte | text | Oui | Aucune | Oui | Oui | Oui | Non vide | Explication courte |
| `position` | Position | int | Oui | `0` | Indirectement | Oui | Oui | `>= 0` recommande | Ordre manuel |
| `isActive` | Actif | bool | Oui | `true` | Oui | Oui | Oui | Permet de masquer sans supprimer | Pilotage editorial |
| `createdAt` | Date de creation | datetime immutable | Oui | auto | Non | Oui | Non | Technique | Lecture seule |
| `updatedAt` | Date de mise a jour | datetime immutable | Oui | auto | Non | Oui | Non | Technique | Lecture seule |

## Regles metier minimales a retenir

- `Page.code` doit rester unique et controle via enum.
- `Category.slug` et `Page.slug` doivent rester uniques et non vides.
- `Product.price` doit etre strictement positif pour un affichage carte propre.
- `Review.rating` doit etre un entier entre 1 et 5.
- `Review.sourceUrl` et `SocialLink.url` doivent etre des URL valides si renseignees.
- `FeaturedItem` doit toujours cibler soit un `product`, soit une `category`, jamais les deux.
- `FeaturedItem` ne doit pas depasser 6 elements actifs par site.
- `Category` ne doit pas etre supprimable si des produits y sont rattaches.
- `Allergen` ne doit pas etre supprimable s'il est encore lie a des produits.
- `SiteSettings` et les pages structurantes ne sont pas supprimes depuis l'admin V1.
