# AGENTS.md

## Objectif

Développer un site vitrine Symfony propre, maintenable et optimisé SEO.
Le travail se fait par actions atomiques, validées une par une.

## Règle principale

- Une action = un changement logique complet.
- L'agent implémente mais ne commit jamais automatiquement.
- L'agent attend la validation explicite de l'utilisateur avant commit.

## Workflow obligatoire

Pour chaque action :

1. Lire TASK.md
2. Implémenter uniquement cette action
3. Vérifier
4. Résumer
5. Proposer un commit
6. STOP (attente validation)

## Interdictions

- Ne jamais enchaîner plusieurs actions
- Ne jamais commit sans validation
- Ne pas faire de refactor non demandé
- Ne pas modifier hors périmètre

## Stack

- Symfony 7
- PHP 8+
- Twig
- Tailwind CSS + DaisyUI
- Doctrine ORM

## Bonnes pratiques

- Réutiliser les patterns existants
- Code simple et lisible
- Respect des conventions Symfony
- Ne pas sur-ingénierer

## Vérifications possibles

- php bin/console lint:twig templates
- php bin/console lint:container
- php bin/console doctrine:schema:validate
- php bin/phpunit
- php bin/console cache:clear

## Définition de terminé

- Fonctionnel
- Cohérent
- Vérifié
- Relisible
- Aucun commit encore créé

## Format de réponse attendu

- Résumé
- Fichiers modifiés
- Vérifications
- Points d’attention
- Message de commit proposé
- Attente validation
