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

## Synthèse — Carte de la requête `GET /`

```mermaid
flowchart TD
    A[Navigateur : GET http://localhost/] --> B[compose.yaml : conteneur laravel.test, port 80]
    B --> C[public/index.php]
    C --> D[vendor/autoload.php]
    C --> E[bootstrap/app.php]
    E --> F[routes/web.php]
    F --> G["closure : view('welcome')"]
    G --> H[resources/views/welcome.blade.php]
    H --> I[Réponse HTML au navigateur]
    J[.env] -.lu par env() dans config/*.php.-> E
```

1. **Navigateur** : envoie `GET /` sur le port 80 de la machine.
2. **`compose.yaml`** : le port 80 est redirigé vers le conteneur `laravel.test`, qui fait tourner PHP.
3. **`public/index.php`** : seul fichier exposé au serveur web, c'est le point d'entrée de toutes les requêtes.
4. **`vendor/autoload.php`** : chargé en premier, il permet de charger les classes de Laravel (autoload de Composer).
5. **`bootstrap/app.php`** : crée l'application et déclare les routes (`web.php`, `console.php`, `/up`).
6. **`.env`** : lu au démarrage, ses valeurs sont récupérées avec `env()` dans `config/*.php` (`APP_NAME`, `DB_*`, etc.).
7. **`routes/web.php`** : la route `/` correspond, et sa closure retourne `view('welcome')`.
8. **`resources/views/welcome.blade.php`** : la vue est compilée en PHP, puis rendue en HTML.
9. **Réponse** : le HTML repart vers le navigateur.

- Méthode : lecture des fichiers dans l'ordre (`public/index.php`, `bootstrap/app.php`, `routes/web.php`), en suivant chaque `require` et chaque appel.

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

## Bonus Séance 2

- **Bonus 1, filtre `?category=`** : pas fait sur le tableau PHP de la S2, car le catalogue est passé sur la base en S3. Il est fait directement en S4 avec `->when($request->query('category'), ...)` sur Eloquent ; `@empty` affiche « Aucun jeu ne correspond à votre recherche. » pour une catégorie inconnue.
- **Bonus 2, liens générés partout** : navbar, hero et cartes passent tous par `route('home')`, `route('wishlist')`, `route('login')` et `route('games.show', $game)`. Plus aucun `href="/..."` interne écrit en dur.
- **Bonus 3, Tailwind et DaisyUI via Vite** : `sail npm install -D daisyui`, puis en tête de `resources/css/app.css` les lignes `@import "tailwindcss";`, les `@source` (vues Blade, vues de pagination de Laravel, vues compilées) et `@plugin "daisyui";`. Les 2 balises CDN sont supprimées du layout. `data-theme="dark"` est mis sur `<html>` pour forcer le thème sombre de DaisyUI ; sans lui, les champs du formulaire étaient blancs sur blanc pour un système en thème clair. Rendu vérifié identique sur les 5 pages.
- **Bonus 4, lien actif** : `@class(['wishflix-shell__link', 'wishflix-shell__link--active' => request()->routeIs('home')])` dans `partials/nav.blade.php`. Le lien s'allume selon la **route courante**, sans que chaque page ait à le préciser.

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

## Bonus Séance 3

### Bonus 1 — Le slug dans l'URL

- Migration `add_slug_to_games_table` (`--table=games`) : `string('slug')->unique()` dans `up()`, `dropColumn('slug')` dans `down()`.
- Slugs générés avec `Str::slug($title)`, dans `GameSeeder` et dans `GameFactory`. Exemple : « The Witcher 3 » → `the-witcher-3`.
- Route : `/game/{game:slug}`. Le `:slug` dit à Laravel de chercher le jeu par sa colonne `slug` au lieu de l'`id`.
- Contrôleur : `show(Game $game)` reçoit le jeu déjà trouvé. C'est le **route model binding** : plus besoin de `findOrFail`, la 404 est automatique.
- Vues : `route('games.show', $game)` génère `/game/the-witcher-3` tout seul.
- Tests :
  - `/game/the-witcher-3` → 200
  - `/game/inconnu` → 404
  - `/game/1` → 404, car l'id n'est plus accepté.
- `whereNumber('id')` est retiré, puisque l'URL contient maintenant du texte.

### Bonus 2 — `status` en enum PHP

- `app/Enums/GameStatus.php` : `enum GameStatus: string` avec les cas `Released`, `Upcoming` et `EarlyAccess`, plus une méthode `label()` qui renvoie Disponible, À venir ou Accès anticipé.
- Modèle : `'status' => GameStatus::class` dans `casts()`. En base, la valeur reste une chaîne (`released`).
- Factory : `fake()->randomElement(GameStatus::cases())`. Seeder : `GameStatus::Released`.
- Vues : `{{ $game->status->label() }}` sur la fiche et sur les cartes de l'accueil.
- Vérifié avec tinker : `get_class(Game::first()->status)` → `App\Enums\GameStatus`, et `->value` → `released`.
- Intérêt : seules 3 valeurs sont possibles, sans faute de frappe, et l'éditeur propose l'autocomplétion.

---

# Séance 4 — N+1

Mesure faite avec la **Debugbar** (onglet *Queries*), et vérifiée avec `DB::enableQueryLog()`. Les chiffres comptent les 3 requêtes de session.

**Avant** (`Game::all()`, `$game->category->name` dans chaque carte, et `$category->games()->count()` par catégorie) :
- **30 requêtes** sur l'accueil.
- La requête qui se répète **20 fois** (1 par jeu) : `select * from categories where categories.id = ? limit 1`
- S'y ajoutent **4 fois** (1 par catégorie, l'US3 de la S3) : `select count(*) as aggregate from games where games.category_id = ?`

**Après** (`with('category')`, `withCount('games')`, `paginate(9)`) :
- **13 requêtes**, et la page affiche en plus la section « Mieux notés » et le classement des catégories.
- La catégorie n'est plus chargée jeu par jeu : une seule requête `select * from categories where categories.id in (1, 2, 3, 4)` la charge pour tous les jeux.
- Le comptage par catégorie passe dans **une** requête, grâce à une sous-requête `(select count(*) …) as games_count`.
- **Gain** : 30 → 13 requêtes (−17), et le nombre ne grandit plus avec le nombre de jeux.
- En une phrase : `with('category')` remplace les N requêtes « 1 catégorie par jeu » par **une seule** requête `WHERE id IN (…)`, puis Eloquent range chaque catégorie dans le bon jeu.
- Garde-fou : `Model::preventLazyLoading(! app()->isProduction())` dans `AppServiceProvider`. En dev, un N+1 oublié lève maintenant une exception au lieu de passer inaperçu.

---

# Séance 4 — Sous le capot

## C1 — D'où vient `when()`

**1.** Chaîne complète :
- `Illuminate/Database/Eloquent/Builder.php` (ligne 43 : `use BuildsQueries, ...`)
- → `Illuminate/Database/Concerns/BuildsQueries.php` (ligne 31 : `use Conditionable;`)
- → `Illuminate/Conditionable/Traits/Conditionable.php` (namespace `Illuminate\Support\Traits`) : c'est ici que vivent `when()` et `unless()`.
- Chemin : Ctrl+F sur `use` en haut de chaque fichier.

**2.** Quand la valeur est « falsy » (`null`, `''`, `0`), on n'entre ni dans le `if` ni dans le `elseif` : `when` fait **`return $this;`**, c'est-à-dire **la requête inchangée**. Comme elle renvoie toujours le builder, la chaîne `->when(null, …)->orderByDesc(…)` continue sans erreur.
- Chemin : `Conditionable.php` → `when`.

**3.** `return $callback($this, $value) ?? $this;` : la closure reçoit **(1) la requête** (`$this`, le builder) et **(2) la valeur testée** (ex. l'id de catégorie lu dans l'URL). Si la closure ne renvoie rien (`null`), `?? $this` renvoie quand même la requête. Comme `where()` modifie le builder lui-même, le filtre est bien appliqué.

**4.** Prédiction : `select * from games where featured = ?`, sans condition sur `rating` puisque la valeur est `null`.
- `toSql()` réel : `` select * from `games` where `featured` = ? `` ✅
- Méthode : `./vendor/bin/sail artisan tinker`.

## C2 — Comment `paginate()` fabrique les pages

**5.** Dans `Illuminate/Database/Eloquent/Builder.php` → `paginate` (ligne 1177) :
- `Paginator::resolveCurrentPage($pageName)` : lit le numéro de page dans l'URL (`?page=`), ou prend `1` par défaut.
- `$this->toBase()->getCountForPagination()` : lance un `select count(*)` pour connaître le **total** de résultats, ce qui permet de calculer le nombre de pages.
- `$this->forPage($page, $perPage)->get()` : récupère **seulement** les lignes de la page demandée (`limit` et `offset`).

**6.** `Game::paginate(5)` : **2 requêtes** :
- `` select count(*) as `aggregate` from `games` ``
- `` select * from `games` limit 5 offset 0 ``
- Méthode : tinker avec `DB::enableQueryLog()` puis `DB::getQueryLog()`.

**7.** `forPage` (`Illuminate/Database/Query/Builder.php`, ligne 3274) : `return $this->offset(($page - 1) * $perPage)->limit($perPage);`
- Pour `?page=3` avec 5 par page : **`limit 5 offset 10`**, car (3 − 1) × 5 = 10.
- Vérifié : `Game::query()->forPage(3, 5)->toSql()` → `` select * from `games` limit 5 offset 10 ``

**8.** `$p = Game::paginate(5)` sur ma base (20 jeux) :
- `total()` = **20**, `currentPage()` = **1**, `lastPage()` = **4**, `perPage()` = **5**
- `get_class($p)` = **`Illuminate\Pagination\LengthAwarePaginator`**

**9.** Résumé : le navigateur demande `/?page=2`. Dans `GameController::index`, `paginate(9)` lit le numéro de page avec `Paginator::resolveCurrentPage`, puis lance un `count(*)` pour connaître le total. `forPage(2, 9)` ajoute `limit 9 offset 9` à la requête, qui ne ramène que les 9 jeux de la page 2. Le résultat est emballé dans un `LengthAwarePaginator`, qui se parcourt comme une Collection dans le `@forelse`. Enfin, `{{ $games->links() }}` dessine les liens de pages, et grâce à `withQueryString()` ces liens gardent `search` et `category`.

## Bonus Séance 4

- **US3, top catégories** : `Category::withAvg('games', 'rating')->orderByDesc('games_avg_rating')->get()`. La moyenne arrive dans `games_avg_rating`, avec **une seule requête** (sous-requête `avg`, vérifiée dans la Debugbar). Elle est affichée sous le catalogue, dans « Catégories les mieux notées ».
- **Défi 1, une « à la une » qui change** : `Game::where('featured', true)->inRandomOrder()->first()`. SQL : `order by RAND() limit 1`, donc un jeu à la une différent à chaque chargement quand plusieurs jeux sont `featured`. Depuis le bonus 1 de la S5, un seul jeu peut être à la une : le tirage n'a donc plus d'effet visible, mais la requête reste correcte.
- **Défi 2, `simplePaginate(9)`** : ne fait **pas** de `count(*)` et n'affiche que « Précédent / Suivant ». C'est un bon choix sur les **très grandes tables**, où compter toutes les lignes coûte cher, ou pour un défilement infini. Je garde `paginate()`, parce que l'accueil affiche le nombre de résultats et les numéros de pages.

---

# Séance 5 — Sous le capot

> Projet en **Laravel 13** : le middleware CSRF a changé de nom par rapport à l'énoncé (Laravel 12). `ValidateCsrfToken` est maintenant une classe vide, marquée `@deprecated`, qui hérite de **`PreventRequestForgery`**. C'est là que se trouve le code.

## C1 — Qui refuse la requête sans jeton ?

**Chemin** : `Illuminate/Foundation/Http/Middleware/ValidateCsrfToken.php` (classe vide, `extends PreventRequestForgery`) → `Illuminate/Foundation/Http/Middleware/PreventRequestForgery.php` → `handle`, `isReading`, `hasValidOrigin`, `tokensMatch`, `getTokenFromRequest`.

- **Conditions qui laissent passer** : `handle()` laisse passer la requête si **une seule** de ces conditions est vraie (des `||`) :
  1. `isReading()` : la méthode est `GET`, `HEAD` ou `OPTIONS`.
  2. `runningUnitTests()` : on est dans les tests automatiques.
  3. `inExceptArray()` : l'URL est exclue de la vérification.
  4. `hasValidOrigin()` : **nouveau en Laravel 13**. L'en-tête `Sec-Fetch-Site` vaut `same-origin`, c'est-à-dire que le navigateur certifie que le formulaire vient du même site.
  5. `tokensMatch()` : le jeton envoyé est identique à celui de la session.
- Sinon : `throw new TokenMismatchException('CSRF token mismatch.')`, que le `Handler` transforme en **`HttpException(419)`** (`Foundation/Exceptions/Handler.php`, ligne 779). C'est la page « 419 Page Expired ».
- **Pourquoi un GET n'est jamais vérifié** : `isReading()` renvoie vrai pour GET, HEAD et OPTIONS. Ces méthodes sont censées seulement **lire**, sans rien modifier. Une attaque CSRF n'a donc rien à y gagner, et c'est pour ça qu'on n'utilise jamais de lien GET pour supprimer.
- **D'où vient le jeton comparé** : `tokensMatch()` compare avec `hash_equals()` le jeton **de la session** (`$request->session()->token()`) à celui de la requête. `getTokenFromRequest()` cherche ce dernier dans le champ `_token` (écrit par `@csrf`), sinon dans l'en-tête `X-CSRF-TOKEN`, sinon dans l'en-tête `X-XSRF-TOKEN` (cookie chiffré, utilisé par JavaScript).
- **Constaté en vrai** (curl, POST `/games` **sans** jeton) :
  - sans en-tête → **419**
  - `Sec-Fetch-Site: cross-site` → **419**
  - `Sec-Fetch-Site: same-origin` → **302**, la requête passe la protection et arrive à la validation.
- C'est pour ça qu'à l'étape A2, après avoir retiré `@csrf`, l'envoi depuis Chrome marchait quand même : Chrome envoie `Sec-Fetch-Site: same-origin`. Laravel 13 protège donc par l'**origine** en plus du jeton. On voit le 419 avec un client qui n'envoie pas cet en-tête (curl, ancien navigateur).

## C2 — Comment `_method=PUT` devient un PUT ?

**Chemin** : `vendor/symfony/http-foundation/Request.php` → `getMethod`, `enableHttpMethodParameterOverride` ; activé par `Illuminate/Foundation/Http/Kernel.php` → `handle` (ligne 142 : `$request->enableHttpMethodParameterOverride();`).

- **Condition** : `_method` n'est lu que si :
  1. la **vraie** méthode est **`POST`** : `if ('POST' !== $this->method ...) return $this->method;`
  2. **et** l'override par paramètre est activé : `self::$httpMethodParameterOverride`, mis à `true` par le Kernel de Laravel à chaque requête.
- La valeur est d'abord cherchée dans l'en-tête `X-HTTP-METHOD-OVERRIDE`, puis dans le champ `_method` (corps, puis query string). Elle est passée en majuscules.
- Elle est **refusée** si elle vaut `GET`, `HEAD`, `CONNECT` ou `TRACE`. Si elle contient autre chose que des lettres, une `SuspiciousOperationException` est levée.
- `@method('PUT')` écrit `<input type="hidden" name="_method" value="PUT">` : le formulaire part en POST, et Laravel le traite comme un PUT.
- **Un GET avec `_method=DELETE`** : il est **ignoré**, car la vraie méthode n'est pas POST, et la requête reste un GET. On ne peut donc pas supprimer un jeu avec un simple lien. Vérifié : `GET /games/1?_method=DELETE` → **405 Method Not Allowed** (aucune route GET sur `/games/{game}`) : rien n'est supprimé.

## C3 — Comment `{game}` devient un `Game` ?

**Chemin** : `Illuminate/Routing/ImplicitRouteBinding.php` → `resolveForRoute` (appelé par le middleware `SubstituteBindings`) ; `Illuminate/Database/Eloquent/Model.php` → `resolveRouteBinding`, `resolveRouteBindingQuery`, `getRouteKeyName`.

- **Comment Laravel sait que c'est un modèle** : `resolveForRoute` parcourt les paramètres de **la signature de la méthode** du contrôleur : `$route->signatureParameters(['subClass' => UrlRoutable::class])`. Il garde ceux dont le **type** implémente `UrlRoutable`, ce qui est le cas de `Model`. `edit(Game $game)` est donc reconnu grâce au type `Game`, et le nom `$game` doit correspondre à `{game}` (`getParameterName`).
- Il crée une instance vide (`$container->make(Game::class)`) et appelle **`resolveRouteBinding($valeur, $champ)`**.
- **Méthode qui charge l'enregistrement** : `Model::resolveRouteBinding()` → `resolveRouteBindingQuery($this, $value, $field)->first()`, c'est-à-dire `where($field ?? $this->getRouteKeyName(), $value)->first()`. `getRouteKeyName()` vaut `id` par défaut. Avec `{game:slug}` (la fiche), `$field` vaut `slug`.
- **La 404** : si `first()` renvoie `null`, `resolveForRoute` fait `throw (new ModelNotFoundException)->setModel(...)`. Le `Handler` la transforme en **`NotFoundHttpException`** (`Foundation/Exceptions/Handler.php`, ligne 773 : `$e instanceof ModelNotFoundException => new NotFoundHttpException(...)`), donc en 404 avec notre page `errors/404`. Vérifié : `/games/999/edit` → **404**.
- Sinon, `$route->setParameter($parameterName, $model)` remplace l'id par le modèle chargé, et le contrôleur reçoit directement `Game $game`, sans `findOrFail`.

## Partie B et bonus Séance 5

- **US1, CRUD des catégories** : `Route::resource('categories', ...)->except(['show'])`, avec `StoreCategoryRequest` (`required|string|max:50|unique:categories,name`) et `UpdateCategoryRequest` (`Rule::unique('categories', 'name')->ignore($this->route('category'))`, pour qu'une catégorie puisse garder son propre nom). Une catégorie qui contient des jeux n'est pas supprimée (`$category->games()->exists()`) : retour à la liste avec un message `error`.
- **US2, CRUD des plateformes** : mêmes principes ; `games()->detach()` avant `delete()`, sinon la clé étrangère de `game_platform` bloque.
- **Bonus 1, jeu à la une unique** : dans `GameService::save()`, si le jeu est `featured`, `Game::whereKeyNot($game->id)->where('featured', true)->update(['featured' => false])`. S'applique à la création et à l'édition, dans une transaction.
- **Bonus 2, `GameService`** : `app/Services/GameService.php` avec `create()`, `update()` et `delete()` (slug unique, plateformes, jaquette, règle « à la une »). Il est injecté dans le constructeur : `public function __construct(private GameService $games) {}`. Le conteneur l'instancie, sans `new`. `store()` et `update()` ne font plus qu'un appel au service puis la redirection.
- **Bonus 3, upload de la jaquette** :
  - champ `cover` dans un formulaire `enctype="multipart/form-data"`, validé par `image|max:2048`
  - stocké avec `->store('covers', 'public')` dans une nouvelle colonne `cover_path` ; `cover_url` devient facultative (`required_without:cover`)
  - l'accesseur `cover_src` affiche le fichier en priorité, sinon l'URL
  - `storage:link` est ajouté à `composer setup`
  - l'ancien fichier est supprimé quand on le remplace, ou quand on supprime le jeu
- **Choix** : les champs du formulaire sont dans `games/fields.blade.php`, partagé par `create` et `edit` au lieu d'être dupliqué. `UpdateGameRequest` hérite de `StoreGameRequest` pour ne pas recopier les règles.
