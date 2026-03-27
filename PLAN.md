# PLAN.md

## Règles

- Une action = un commit
- Une action doit être autonome, complète et stable
- Aucun commit sans validation utilisateur

---

## PHASE 1 — Base technique

### Action 1 — Initialiser layout Twig global

[DONE]

Objectif :
Créer un layout principal avec Tailwind + DaisyUI

À faire :

- base.html.twig
- structure HTML propre
- header simple
- footer simple

Critères :

- page s'affiche
- pas d'erreur Twig

Commit :
feat(layout): create base layout with header and footer

---

### Action 2 — Intégrer Tailwind + DaisyUI

[SKIPPED - déjà implémenté]

Objectif :
Avoir un styling fonctionnel

À faire :

- config Tailwind
- DaisyUI actif
- classes test visibles

Critères :

- styles visibles

Commit :
feat(ui): integrate tailwind and daisyui

---

## PHASE 2 — Structure pages

### Action 3 — Créer page Home (route + controller)

[DONE]

Objectif :
Afficher une page d'accueil simple

À faire :

- HomeController
- route /
- template Twig

Critères :

- page accessible

Commit :
feat(home): create homepage controller and template

---

### Action 4 — Créer sections homepage (structure)

[DONE]

Objectif :
Structurer la homepage

À faire :

- hero
- présentation
- CTA principal

Critères :

- structure visible sans design avancé

Commit :
feat(home): add homepage sections structure

---

## PHASE 3 — Menu / contenu

### Action 5 — Créer entité Produit

[DONE]

Objectif :
Gérer les produits

À faire :

- entité Product
- migration

Critères :

- schema valide

Commit :
feat(product): create product entity

---

### Action 6 — CRUD admin produits (minimal)

[DONE]

Objectif :
Ajouter des produits via admin

À faire :

- CRUD simple

Critères :

- ajout produit fonctionnel

Commit :
feat(admin): add basic product management

---

### Action 7 — Affichage menu en front

[DONE]

Objectif :
Afficher les produits

À faire :

- récupérer produits
- afficher en cards

Critères :

- produits visibles

Commit :
feat(menu): display products on homepage

---

## PHASE 4 — Contact

### Action 8 — Formulaire contact (structure)

[DONE]

Objectif :
Créer le formulaire

À faire :

- FormType
- template

Critères :

- form affiché

Commit :
feat(contact): create contact form structure

---

### Action 9 — Traitement formulaire

[DONE]

Objectif :
Gérer soumission

À faire :

- validation
- message succès

Critères :

- envoi fonctionnel

Commit :
feat(contact): handle form submission

---

## PHASE 5 — SEO / UX

### Action 10 — Meta SEO de base

[DONE]

Objectif :
Ajouter SEO

À faire :

- title dynamique
- meta description

Commit :
feat(seo): add basic meta tags

---

### Action 11 — Responsive mobile

[DONE]

Objectif :
Optimiser mobile

À faire :

- ajustements Tailwind

Commit :
feat(ui): improve mobile responsiveness

---

## PHASE 6 — Finalisation

### Action 12 — Nettoyage final

[DONE]

Objectif :
Stabiliser

À faire :

- vérifier erreurs
- corriger incohérences

Commit :
chore: cleanup and stabilization
chore: cleanup and stabilization
