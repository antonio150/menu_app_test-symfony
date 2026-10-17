# Menu App

Application web construite avec Symfony 7.2 et PHP 8.2 ou supérieur. Le projet contient des pages de démonstration accessibles depuis un menu, ainsi qu'un endpoint HTTP pour envoyer un fichier.

## Fonctionnalités

- Menu commun avec les liens Accueil, À propos et Contact ; la page courante est mise en évidence.
- Pages d'accueil (`/`), À propos (`/about`) et Contact (`/contact`). Elles affichent actuellement le contenu de démonstration Symfony.
- Réception d'un fichier via l'endpoint `/uploadimage`. Le fichier est transmis au service `UploadImageBundle`, qui détermine son emplacement en fonction de son type.
- Configuration prête à utiliser Doctrine avec PostgreSQL. Les pages et l'endpoint d'upload ne nécessitent actuellement aucune donnée en base.

## Prérequis

- PHP 8.2 ou supérieur, avec Composer.
- Symfony CLI pour lancer le serveur de développement (facultatif, mais recommandé).
- Docker Compose uniquement si vous souhaitez lancer le PostgreSQL ou Mailpit fournis avec le projet.

## Installation et lancement

À la racine du projet, installez les dépendances :

```bash
composer install
```

Lancez ensuite le serveur Symfony :

```bash
symfony server:start
```

Ouvrez l'adresse affichée par Symfony CLI, généralement <http://127.0.0.1:8000>.

Sans Symfony CLI, le serveur PHP intégré peut être utilisé avec le contrôleur frontal Symfony :

```bash
php -S 127.0.0.1:8000 -t public public/index.php
```

## Base de données et services Docker (facultatifs)

Le fichier Compose fournit PostgreSQL et Mailpit. Pour les lancer :

```bash
docker compose up -d
```

Dans `compose.override.yaml`, les ports hôtes sont attribués automatiquement. Pour connaître le port PostgreSQL publié :

```bash
docker compose port database 5432
```

La connexion à la base est lue depuis `DATABASE_URL` dans `.env`. Si le port publié diffère de celui de cette URL, définissez la valeur adéquate dans `.env.local` (ce fichier n'est pas versionné). Les identifiants par défaut du conteneur sont définis dans `compose.yaml` et peuvent être remplacés par des variables `POSTGRES_DB`, `POSTGRES_USER` et `POSTGRES_PASSWORD`.

Mailpit expose une interface web sur le port hôte attribué à son port interne `8025` et SMTP sur celui attribué à `1025`. Pour connaître ces ports, utilisez `docker compose port mailer 8025` et `docker compose port mailer 1025`. Pour envoyer des messages à Mailpit, configurez `MAILER_DSN` dans `.env.local` avec le port SMTP obtenu.

## Utilisation de l'upload

Envoyez une requête `multipart/form-data` avec le champ `file` :

```bash
curl.exe -X POST -F "file=@C:\chemin\vers\image.jpg" http://127.0.0.1:8000/uploadimage
```

En cas de succès, l'endpoint renvoie en JSON le résultat fourni par le bundle d'upload. Si aucun fichier n'est fourni, il renvoie une erreur JSON avec le statut HTTP 400. Les erreurs d'upload renvoyées par le bundle produisent également un statut 400.

Les dossiers de destination sont définis dans [`config/packages/upload_image_bundle.yaml`](config/packages/upload_image_bundle.yaml) : `public/uploads/images`, `public/uploads/documents` et `public/uploads/videos`. Leurs chemins sont actuellement absolus et propres à l'environnement de développement utilisé pour ce dépôt. Après avoir déplacé ou cloné le projet dans un autre emplacement, adaptez ces chemins à votre environnement avant d'utiliser l'upload.

## Commandes utiles

Afficher les routes déclarées :

```bash
php bin/console debug:router
```

Vérifier la configuration Symfony :

```bash
php bin/console lint:container
```

Exécuter les tests :

```bash
php bin/phpunit
```

Arrêter les services Docker :

```bash
docker compose down
```

## Structure du projet

- `src/Controller/` : contrôleurs des pages et de l'upload.
- `src/Twig/Components/` : composant Twig du menu.
- `templates/` : vues Twig.
- `config/` : configuration Symfony, Doctrine et du bundle d'upload.
- `public/uploads/` : dossiers publics de destination des fichiers envoyés.
