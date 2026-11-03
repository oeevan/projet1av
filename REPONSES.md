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
- Tables créées : 8 tables — users, password_reset_tokens, sessions, cache, cache_locks, jobs, job_batches, failed_jobs
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

---

# Séance 2 — Sous le capot

> Note : le projet a été installé en **Laravel 13** (pas 12) : les fichiers ci-dessous sont ceux de `vendor/laravel/framework/src/Illuminate/` de cette version, le code étudié est identique.

## C1 — Ce que devient `{{ ... }}`

**1.** Ligne compilée de `{{ $game['title'] }}` :
- `<?php echo e($game['title']); ?>`
- (depuis la S3, la vue utilise `$game->title`, donc le fichier actuel contient `<?php echo e($game->title); ?>`)
- Chemin : `storage/framework/views/` → fichier dont la fin est `/**PATH .../resources/views/home.blade.php ENDPATH**/`. Vérifié aussi dans tinker avec `Blade::compileString(...)`.

**2.** Blade ou PHP ?
- C'est du **PHP pur**. Le `@forelse` est devenu un `foreach(...)` avec une variable `$__empty_1` : à la fin, `if ($__empty_1)` affiche le bloc `@empty`.
- Chemin : même fichier compilé.

**3.** Fonction native à la fin de `e()` : **`htmlspecialchars`**
- `return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', $doubleEncode);`
- Chemin : `Illuminate/Support/helpers.php` → fonction `e`.

**4.** `{{ }}` contre `{!! !!}` (titre = `<script>alert('XSS')</script>`) :
- Avec `{{ }}` : le texte `<script>…` s'affiche **tel quel** à l'écran, rien ne s'exécute (`e()` le transforme en `&lt;script&gt;…`).
- Avec `{!! !!}` : la balise est envoyée brute → le navigateur **exécute** l'alerte = faille XSS.
- Différence : `{{ }}` échappe le HTML (sécurisé), `{!! !!}` ne l'échappe pas (à utiliser seulement pour du HTML de confiance).
- Méthode : vérifié dans tinker — `e("<script>alert(\"XSS\")</script>")` renvoie `&lt;script&gt;alert(&quot;XSS&quot;)&lt;/script&gt;`, et `Blade::compileString` montre que `{!! !!}` donne `echo $game["title"]` sans `e()`.
- Chemin : `Illuminate/View/Compilers/Concerns/CompilesEchos.php` → `compileRegularEchos` (ajoute `e(%s)`, constante `DEFAULT_ECHO_FORMAT` de `BladeCompiler.php`) / `compileRawEchos` (pas de `e()`).

**5.** Quand Laravel compile-t-il ?
- Après `view:clear` : le dossier est vide (0 fichier). Après rechargement de l'accueil : 3 fichiers (home, layout, partial nav).
- Déduction : Laravel compile une vue **au premier affichage** (ou quand le `.blade.php` a changé), puis réutilise le fichier compilé.
- Méthode : `./vendor/bin/sail artisan view:clear`, `ls storage/framework/views`, rechargement de `/`.

## C2 — Qui répond à `->whereNumber('id')`

**6.** Le mot-clé est **`trait`** (`trait CreatesRegularExpressionRouteConstraints`). La regex de `whereNumber` est **`[0-9]+`**.
- Chemin : `Illuminate/Routing/CreatesRegularExpressionRouteConstraints.php` → `whereNumber`.

**7.** `assignExpressionToParameters` appelle en fin de compte **`$this->where(...)`** avec le tableau `['id' => '[0-9]+']` : le paramètre `id` est associé à la regex.
- Chemin : même fichier → `assignExpressionToParameters`.

**8.** Ligne 37 de la classe **`Route`** : `use Conditionable, CreatesRegularExpressionRouteConstraints, FiltersControllerMiddleware, Macroable, ResolvesRouteDependencies;`
- Chemin : `Illuminate/Routing/Route.php` → Ctrl+F.

**9.** La contrainte est rangée dans la propriété **`$this->wheres`** (`$this->wheres[$name] = $expression;`).
- Chemin : `Illuminate/Routing/Route.php` → méthode `where`.

**10.** Les voisines :
- `whereAlpha` → `[a-zA-Z]+` → ex. `/game/zelda`
- `whereAlphaNumeric` → `[a-zA-Z0-9]+` → ex. `/game/zelda2`
- `whereUuid` / `whereUlid` : même principe, avec une regex plus longue (format UUID / ULID). `whereIn` construit la regex à partir d'une liste de valeurs collées avec `|` (ex. `pc|ps5`).
- Chemin : trait `CreatesRegularExpressionRouteConstraints`.

**11.** `->where('id', '[0-9]+')` : `/game/1` marche, `/game/abc` → 404, exactement pareil.
- Conclusion : `whereNumber` est juste un **raccourci** pour `where('id', '[0-9]+')`.
- Méthode : modification de `routes/web.php` puis test des 2 URL, et retour à `whereNumber`.

## C3 — De `abort(404)` à la page 404

**12.** `abort` appelle **`app()->abort($code, $message, $headers)`** → sur l'objet **Application** (le conteneur Laravel).
- Chemin : `Illuminate/Foundation/helpers.php` → fonction `abort`.

**13.** Si le code vaut 404 → `throw new NotFoundHttpException(...)`. Sinon → `throw new HttpException($code, ...)`.
- Chemin : `Illuminate/Foundation/Application.php` → méthode `abort`.

**14.** `renderHttpException` fait 2 choses : 1) `registerErrorViewPaths()` (enregistre les dossiers `errors::`), 2) `getHttpExceptionView($e)` (cherche la vue). Pour un 404, le nom construit est **`errors::404`**.
- Chemin : `Illuminate/Foundation/Exceptions/Handler.php` → `renderHttpException`, `getHttpExceptionView`.

**15.** Plan B : `substr($view, 0, -2).'xx'` → **`errors::4xx`**. Si elle n'existe pas non plus → `null`, et Laravel affiche la page d'erreur par défaut (`convertExceptionToResponse`).
- Chemin : même fichier → `getHttpExceptionView`.

**16.** Dossiers de `errors::`, dans l'ordre : 1) **`resources/views/errors`** (chaque dossier de `config('view.paths')` + `/errors`), 2) **`Illuminate/Foundation/Exceptions/views`** (les vues du framework).
- Notre `404.blade.php` gagne car notre dossier est **cherché en premier**.
- Chemin : `Illuminate/Foundation/Exceptions/RegisterErrorViewPaths.php` → `__invoke`.

**17.** C'est **`handleMatchedRoute`** de `Illuminate/Routing/AbstractRouteCollection.php` qui lance `NotFoundHttpException('The route %s could not be found.')` — la **même exception** qu'`abort(404)`.
- Méthode : recherche du message dans `vendor/laravel/framework/src/Illuminate/Routing/`.

**18.** Avec `abort(403)` : la page **403 « Forbidden » de Laravel** s'affiche (pas la nôtre). `errors::403` n'existe pas dans `resources/views/errors/`, donc Laravel la trouve dans le 2ᵉ dossier : `Illuminate/Foundation/Exceptions/views/403.blade.php`.

**19.** Résumé : `abort(404)` (`Foundation/helpers.php`) appelle `Application::abort` (`Foundation/Application.php`) qui lance une `NotFoundHttpException`. Une URL sans route lance la même exception dans `AbstractRouteCollection::handleMatchedRoute`. L'exception remonte jusqu'au `Handler` (`Foundation/Exceptions/Handler.php`) : `renderHttpException` enregistre les dossiers `errors::` (`RegisterErrorViewPaths::__invoke`), puis `getHttpExceptionView` construit le nom `errors::404`. Comme `resources/views/errors` est cherché en premier, c'est notre `404.blade.php` WishFlix qui est rendu, avec le code HTTP 404.

---

# Séance 3 — Sous le capot

## C1 — Comment `Game` devine sa table

**1.** `return $this->table ?? Str::snake(Str::pluralStudly(class_basename($this)));`
- Signification : si le modèle a une propriété `$table`, elle est utilisée ; sinon (null) le nom est **calculé** à partir du nom de la classe. `Game` n'a pas de `$table` → nom calculé.
- Chemin : `Illuminate/Database/Eloquent/Model.php` → `getTable`.

**2.** Les 3 étapes (tinker) :
- `class_basename(App\Models\Game::class)` → `Game`
- `Str::pluralStudly('Game')` → `Games`
- `Str::snake('Games')` → `games`
- Résultat complet : `(new App\Models\Game)->getTable()` → `games`

**3.** Prédiction pour `GameReview` : `game_reviews`. Vérifié : `GameReview` → `GameReviews` → `game_reviews` ✅

**4.** Forcer un autre nom : `protected $table = 'mes_jeux';`

## C2 — Comment `belongsToMany` devine la pivot

**5.** Si aucun nom n'est donné (`is_null($table)`), Laravel le devine en appelant **`$this->joiningTable($related, $instance)`**.
- Chemin : `Illuminate/Database/Eloquent/Concerns/HasRelationships.php` → `belongsToMany`.

**6.** Le commentaire dit : le nom = les modèles en snake_case, **triés par ordre alphabétique**, collés avec `_`. Pour `Game::platforms()`, les segments sont `'platform'` (le modèle lié) et `'game'` (le modèle courant).
- Chemin : même fichier → `joiningTable` (+ `joiningTableSegment`).

**7.** `sort($segments)` trie par ordre alphabétique → `['game', 'platform']`. `strtolower(implode('_', $segments))` colle avec `_` et met en minuscules → `game_platform`.

**8.** Depuis `Platform::games()` : avant le sort `['game', 'platform']`, après `['game', 'platform']`. Depuis `Game::platforms()` : avant `['platform', 'game']`, après `['game', 'platform']`. Le tri garantit que **les 2 côtés obtiennent le même nom** `game_platform`, quel que soit le côté qui appelle.

**9.** `(new App\Models\Game)->joiningTable(App\Models\Platform::class)` → **`game_platform`**

**10.** Pivot wishlist `User` ↔ `Game` : segments `user` et `game` → triés `game`, `user` → **`game_user`** (vérifié dans tinker).

## C3 — `$game->title` et `$game->category`

**11.** PHP appelle `__get($key)` quand on lit une propriété qui n'existe pas. `Model::__get` redirige vers **`$this->getAttribute($key)`**. Donc pas besoin de `public $title`.
- Chemin : `Illuminate/Database/Eloquent/Model.php` → `__get`.

**12.** Le test est `if ($this->hasAttribute($key))` :
- vrai → colonne → `getAttributeValue($key)`
- sinon, si `isRelation($key)` ou `relationLoaded($key)` → relation → `getRelationValue($key)`
- Les colonnes sont stockées dans le tableau **`$this->attributes`**.
- Chemin : `Illuminate/Database/Eloquent/Concerns/HasAttributes.php` → `getAttribute`.

**13.** `$game->title` : `title` est une colonne → `hasAttribute` vrai → `getAttributeValue` (lu dans `$attributes`).
`$game->category` : pas une colonne → `isRelation('category')` vrai → `getRelationValue` → `getRelationshipFromMethod`.

**14.** La ligne `$this->$method()` appelle **notre méthode `category()`** de `App\Models\Game` (celle qui fait `return $this->belongsTo(Category::class);`).
- Chemin : `HasAttributes.php` → `getRelationshipFromMethod`.

**15.** Une **`LogicException`** est levée. Message : `App\Models\Game::category must return a relationship instance, but "null" was returned. Was the "return" keyword used?` → il suggère qu'on a **oublié le `return`**.

**16.** `relationLoaded('category')` : **false** avant, **true** après. La catégorie est maintenant gardée en mémoire dans `$this->relations`, grâce à la ligne `$this->setRelation($method, $results);` (dans le `tap(...)` de `getRelationshipFromMethod`).

**17.** **2 requêtes** (1 pour le jeu, 1 pour la catégorie). La 2ᵉ lecture n'en ajoute pas car `getRelationValue` voit `relationLoaded('category')` = true et renvoie directement `$this->relations['category']`.

**18.** Avec 20 jeux : 1 requête (`Game::all()`) + 1 par jeu (`category`) = **21 requêtes** (vérifié avec `DB::getQueryLog()` → 21). C'est le problème **N+1**.

**19.** Résumé : la vue lit `$game->category` ; la propriété n'existe pas, donc PHP appelle `Model::__get`, qui appelle `getAttribute`. Ce n'est pas une colonne mais une relation, donc on passe dans `getRelationValue` puis `getRelationshipFromMethod`, qui appelle notre méthode `category()` (un `belongsTo`). `getResults()` exécute la requête `SELECT * FROM categories WHERE id = ?`, et `setRelation` range le résultat dans `$this->relations`. Aux lectures suivantes, la catégorie est lue en mémoire, sans nouvelle requête.
