# REPONSES — Exercice 01

## Palier 1

**1.**
> Ouvrez `routes/web.php`. Quelle URL est définie ? Que retourne-t-elle ? Quel fichier (chemin complet) contient cette vue ?

- URL définie : / donc il s'agit de la racine du site : http://localhost/
- Ce qu'elle retourne : elle retourne la page welcome
- Fichier de la vue (chemin complet) : resources/views/welcome.blade.php

- Méthode : ouverture de fichier et lecture du code

**2.**
> Ouvrez `.env`. Notez les valeurs de `APP_ENV`, `DB_CONNECTION` et `SESSION_DRIVER`.

- APP_ENV : local
- DB_CONNECTION : mariadb
- SESSION_DRIVER : database
- Méthode : ouverture du fichier env et lecture du code

**3.**
> Ouvrez `compose.yaml`. Listez les services définis. Lequel sert la page `http://localhost` ? Sur quel port de votre machine la base de données est-elle exposée ?

- Services définis : mariadb & laravel.test
- Service qui sert http://localhost : laravel.test
- Port de la base de données sur ma machine : 3306
- Méthode : ouverture du fichier compose yaml et lecture

**4.**
> Combien de fichiers y a-t-il dans `database/migrations/` ? Quelles tables créent-ils ? Vérifiez qu'ils ont bien été exécutés avec `./vendor/bin/sail artisan migrate:status`.

- Nombre de fichiers : 3 fichiers
- Tables créées : Il y a 8 tables créées
- Résultat de migrate:status : les 3 migrations sont en Ran
- Méthode : ls du dossier, grep "Schema::create" et migrate:status

## Palier 2

**5.**
> Dans `public/index.php`, quel est le **premier** fichier chargé par `require` ? À quel dossier appartient-il et qui a généré ce dossier ?

- Premier fichier chargé par require : /../vendor/autoload.php
- Dossier : vendor
- Qui a généré ce dossier : Composer
- Méthode : ouvert public/index.php, lu les require dans l'ordre. Celui du mode maintenance est dans un if donc pas toujours exécuté, le premier require qui s'exécute à coup sûr est vendor/autoload.php

**6.**
> Ouvrez `bootstrap/app.php`. Quels fichiers de routes y sont déclarés ? Une URL de « santé » (*health*) y est aussi configurée : laquelle ? Visitez-la dans votre navigateur.

- Fichiers de routes déclarés : web et console
- URL de santé : /up
- Méthode : lecture du fichier app de bootstrap

**7.**
> Exécutez `./vendor/bin/sail artisan route:list`. Combien de routes existent ? Retrouvez-vous l'URL de la question 6 ?

- Nombre de routes : 4
- URL de la question 6 présente : oui, /up (GET|HEAD up)
- Méthode : lancé ./vendor/bin/sail artisan route:list

**8.**
> Dans `config/app.php`, trouvez la ligne qui définit `'name'`. Quelle fonction est utilisée pour lire la valeur ? Que se passerait-il si `APP_NAME` était absent du `.env` ?

- Ligne qui définit 'name' : 'name' => env('APP_NAME', 'Laravel'),
- Fonction utilisée : env()
- Si APP_NAME est absent du .env : Laravel utilise la valeur par défaut, le second argument, donc 'Laravel'
- Méthode : ouvert config/app.php et lu la ligne 'name'

## Palier 3

**9.**
> `app/Models/` contient un seul modèle. Lequel ? À quelle table de la base correspond-il ? Comment Laravel fait-il le lien entre le nom de la classe et le nom de la table ?

- Modèle : User
- Table correspondante : users
- Lien entre le nom de la classe et le nom de la table : convention Laravel, nom de la classe en minuscules et au pluriel (User → users)
- Méthode : ls app/Models, ouvert User.php (pas de $table), tinker : (new App\Models\User)->getTable() retourne "users"

**10.**
> `app/Http/Controllers/` contient un fichier. Est-il utilisé par la route `/` ? Si non, comment la route produit-elle quand même une réponse ?

- Fichier présent : Controller.php
- Utilisé par la route / : non
- Comment la route produit une réponse : avec une fonction anonyme (closure) qui retourne view('welcome')
- Méthode : ls app/Http/Controllers, ouvert Controller.php (classe vide), relu routes/web.php

**11.**
> Ouvrez `.gitignore`. Pourquoi `.env` y figure-t-il alors que `.env.example` n'y figure pas ? Pourquoi `vendor/` y figure-t-il ? Quelle commande un collègue devra-t-il lancer après avoir cloné votre dépôt pour reconstituer ce dossier ?

- Pourquoi .env est dans .gitignore : il contient des secrets (APP_KEY, mot de passe BDD), il ne doit pas aller sur GitHub
- Pourquoi .env.example n'y est pas : c'est un modèle sans secret, les collègues le copient pour créer leur .env
- Pourquoi vendor/ y est : trop lourd, et il se reconstitue à partir de composer.json
- Commande à lancer après un clone : composer install
- Méthode : ouvert .gitignore, comparé .env et .env.example, relu le lexique du cours (Composer)

**12.**
> Le dossier `public/` est le **seul** exposé au serveur web. Quel problème de sécurité cela évite-t-il par rapport à un projet PHP « from scratch » où tous les fichiers sont à la racine du site ?

- Problème de sécurité évité : sans ça, on pourrait ouvrir .env ou le code source depuis le navigateur (http://localhost/.env) et voler les mots de passe. Seul public/ est accessible.
- Méthode : réflexion à partir de l'indice, vérifié le contenu de public/

## Bonus

**1.**
> Dans la liste affichée par `artisan list`, repérez une commande qui **vide un cache** et une qui **indique l'état des migrations**. Quelles sont leurs noms exacts ?

- Commande qui vide un cache : cache:clear
- Commande qui indique l'état des migrations : migrate:status
- Méthode : ./vendor/bin/sail artisan list

**2.**
> Exécutez `./vendor/bin/sail artisan make:controller --help`. Quelles options permettent de générer **d'autres fichiers** en même temps que le contrôleur ? Choisissez-en une, générez un contrôleur, puis observez (et nettoyez) les fichiers créés.

- Options qui génèrent d'autres fichiers : --requests, --test / --pest / --phpunit
- Option choisie : --model=User --requests
- Fichiers créés : UserController.php, StoreUserRequest.php, UpdateUserRequest.php
- Nettoyage : supprimé les 3 fichiers et le dossier Requests (rm)
- Méthode : ./vendor/bin/sail artisan make:controller --help puis make:controller UserController --model=User --requests

**3.**
> Lancez `./vendor/bin/sail artisan tinker`. Essayez d'y afficher la valeur `APP_NAME` de votre `.env` sans ouvrir le fichier — une fonction d'aide de Laravel permet de lire la configuration.

- Commande tinker : config('app.name')
- Résultat : Laravel
- Méthode : ./vendor/bin/sail artisan tinker puis config('app.name')
