# Fiche cours — Laravel (S1 → S5)

## S1 — Introduction, Docker et architecture

### 01 · Lancer l'installation
- **Prérequis** — `docker compose version`, `docker ps` OK ; ports 80 et 3306 libres.
- **Windows** — terminal WSL2, pas PowerShell.
- **Plan B** — Podman IUT (`localhost:8080`), PC perso, ou Herd (`wishflix.test`).
```bash
curl -s "https://laravel.build/wishflix?with=mariadb" | bash
```

### 02 · Pourquoi un framework ?
- **Framework** — architecture + outils + conventions ; il appelle votre code.
- **Librairie** — vous l'appelez, vous gardez le contrôle du flux.
- **Inversion de contrôle** — le framework pilote le flux, pas votre code.
- **Laravel** — framework PHP open source (2011) ; cours : Laravel 12, PHP 8.3+.

### 03 · Installer Laravel avec Docker
- **Docker** — même environnement pour tous : PHP, MariaDB, serveur web.
- **Composer** — gestionnaire de dépendances PHP, installe dans `vendor/`.
- **Artisan** — console Laravel : tâches (migrations, cache) et génération de code.
- **Sail** — `compose.yaml` + script `sail` qui pilote les conteneurs.
- **Piège** — `migrate` oublié → erreur « no such table: sessions ».
```bash
./vendor/bin/sail up -d
alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'
```

### 04 · Suivre une requête dans Laravel
- **Trajet** — `public/index.php` → `bootstrap/app.php` → `routes/web.php` → vue.
- **Front controller** — `public/index.php`, seul fichier PHP accessible depuis le web.
- **bootstrap/app.php** — déclare routes `web`, `commands`, et URL de santé `/up`.
- **.env** — `APP_ENV`, `APP_DEBUG` ; secrets, jamais versionné (`.env.example` oui).
- **env('X', défaut)** — lit le `.env`, sinon valeur par défaut.
- **Piège** — `View [welcom] not found` : lire la première ligne pointant votre code.
- **Votre code** — `app/`, `routes/`, `resources/views/`, `database/migrations/` ; `vendor/` intouchable.

## S2 — Routing, contrôleurs et Blade

### 01 · Routing
- **Route** — URL + verbe HTTP + action à exécuter.
- **Verbes** — GET lire, POST créer, PUT/PATCH modifier, DELETE supprimer.
- **Paramètre** — `{id}` capture un segment ; `{x?}` optionnel.
- **Contrainte** — `whereNumber('id')` : sinon 404 avant l'action.
- **Route nommée** — `->name('quests.show')` ; URL via `route('quests.show', 3)`.
- **Règle** — jamais d'URL en dur dans `href`, toujours `route()`.
- **Piège** — nom différent entre `{id}` et `$id`.
```php
Route::get('/quests/{id}', fn ($id) => "Quête n°{$id}")
    ->whereNumber('id')->name('quests.show');
```

### 02 · Blade : la vue
- **Blade** — moteur de templates : HTML + directives `@` + `{{ }}`, compilé en PHP.
- **view('quests.index', [...])** — point = slash ; clés du tableau = variables.
- **{{ }}** — affichage échappé, protection XSS automatique ; `{!! !!}` = brut.
- **{{-- --}}** — commentaire invisible dans le HTML envoyé.
- **Directives** — `@if/@elseif/@else/@endif`, `@forelse/@empty/@endforelse`, `@foreach`.

### 03 · Contrôleurs
- **Contrôleur** — classe regroupant les actions (méthodes publiques) d'une ressource.
- **Route → contrôleur** — `[QuestController::class, 'index']` remplace la closure.
- **Request** — injecté : `$request->query('difficulty')` lit la query string.
- **Réponses** — `view()` HTML, `response()->json()` JSON, `redirect()->route()` redirection.
```bash
sail artisan make:controller QuestController
```

### 04 · Blade : les layouts
- **Layout** — coquille commune (`layouts/app.blade.php`), écrite une seule fois.
- **@yield('nom')** — emplacement nommé dans le layout.
- **@extends / @section** — la page choisit le layout, remplit les emplacements.
- **@include('partials.nav')** — insère un fragment partagé.
- **Piège** — `@section('contenu')` vs `@yield('content')` : contenu vide, aucune erreur.
```blade
@extends('layouts.app')
@section('title', 'Quêtes')
@section('content') ... @endsection
```

### 05 · Vite
- **Vite** — compile, sert et recharge CSS/JS en développement.
- **@vite([...])** — charge la CSS commune dans le layout ; `@yield('styles')` par page.
- **input** — chaque CSS/JS doit être déclaré dans `vite.config.js`.
- **Piège** — « Vite manifest not found » : `sail npm run dev` pas lancé.
- **Piège** — « Unable to locate file in Vite manifest » : fichier absent de `input`.
```bash
sail npm install && sail npm run dev   # ou sail npm run build
```

### 06 · Pages d'erreur
- **abort(404)** — interrompt l'action, affiche `resources/views/errors/404.blade.php`.
- **abort_unless(cond, 404)** — 404 si la condition est fausse.
- **404 perso** — peut `@extends('layouts.app')` ; sert aussi pour toute URL inconnue.

## S3 — Eloquent : modèles, relations, migrations

### 01 · Une table, un objet
- **ORM** — traduit objets PHP ↔ lignes SQL, sans écrire de SQL.
- **Active Record** — 1 classe = 1 table, 1 instance = 1 ligne ; `save()`.
- **Conventions** — `Location` → table `locations`, PK `id`, `created_at`/`updated_at`.
- **Migration** — fichier PHP versionné : `up()` fait, `down()` annule.
- **Types** — `id`, `string`, `text`, `unsignedInteger`, `decimal`, `boolean`.
- **Modificateurs** — `nullable()`, `default()`, `unique()`, `index()`.
- **Piège** — Tinker « Class not found » : `App\Models\X` ou `sail composer dump-autoload`.
```bash
sail artisan make:model Location -m
sail artisan migrate        # migrate:status, migrate:rollback
```

### 02 · Relation un-à-plusieurs
- **Clé étrangère** — colonne stockant la PK d'une autre table, côté « plusieurs ».
- **foreignId()->constrained()** — colonne + contrainte ; la base refuse un id inexistant.
- **Suppression** — `cascadeOnDelete()` supprime les enfants, `nullOnDelete()` met NULL.
- **$fillable** — liste blanche des colonnes remplissables par `create([...])`.
- **casts()** — conversion à la volée (`featured` 0/1 → booléen).
- **belongsTo / hasMany** — porte la FK / possède plusieurs ; déclarés des deux côtés.
- **Piège** — table pointée d'abord : timestamp du fichier = ordre des migrations.
- **Piège** — sans `$fillable` : `MassAssignmentException` ; colonne absente : ignorée en silence.
- **Propriété vs méthode** — `$c->quests` = Collection ; `$c->quests()` = requête chaînable.

### 03 · Relation plusieurs-à-plusieurs
- **Table pivot** — table intermédiaire : une ligne par couple (quête, lieu).
- **Nom pivot** — deux modèles au singulier, ordre alphabétique : `location_quest`.
- **Clé composite** — `primary([...])` : le même couple interdit deux fois.
- **belongsToMany** — des deux côtés, pivot devinée par convention.
- **attach / detach / sync** — ajoute / retire / remplace exactement les liens.
- **Piège** — écrire via `locations()` (méthode), pas `$quest->locations`.
```php
$table->foreignId('location_id')->constrained();
$table->foreignId('quest_id')->constrained();
$table->primary(['location_id', 'quest_id']);
```

### 04 · Seeders et factory : peupler la base
- **Seeder** — classe versionnée qui remplit la base, identique pour l'équipe.
- **Factory** — fabrique des modèles avec fausses données `fake()` ; `use HasFactory`.
- **make() / create()** — en mémoire sans INSERT / sauvegardé en base.
- **État** — variante nommée (`featured()`) via `$this->state(...)`.
- **recycle()** — réutilise des modèles existants au lieu d'en créer.
- **DatabaseSeeder** — `$this->call([...])` dans l'ordre : référencé avant référençant.
- **Piège** — `db:seed` rejoué sans `fresh` duplique ; mauvais ordre → FK violée.
```bash
sail artisan migrate:fresh --seed
```

### 05 · Brancher le catalogue sur la base
- **Quest::all()** — remplace le tableau PHP ; renvoie une Collection.
- **findOrFail($id)** — 404 automatique si absent (`find()` renvoie `null`).
- **Vues** — `$quest['title']` devient `$quest->title`, `$quest->category->name`.
- **N+1** — 1 requête pour la liste + 1 par ligne affichée.
- **Piège** — 30 quêtes = 31 requêtes ; constaté via `DB::enableQueryLog()`, corrigé en S4.

### Annexe · Modifier une table déjà jouée
- **Règle** — nouvelle migration, jamais retoucher un fichier déjà joué.
- **Schema::table** — modifie une table ; `Schema::create` la crée.
- **Piège** — colonne ajoutée sur table remplie : `->nullable()` ; `down()` = `dropColumn`.
```bash
sail artisan make:migration add_address_to_locations_table --table=locations
```

## S4 — Requêtes Eloquent et optimisation

### 01 · Filtrer et trier
- **where** — `where('xp', 50)`, `where('xp', '>=', 100)`, `whereIn(...)` ; empilés = AND.
- **like** — `"%{$search}%"` : contient ; `%` = joker.
- **Binding** — valeur `?` envoyée à part du SQL : anti-injection.
- **orderBy / orderByDesc / limit** — tri puis coupe des n premiers.
- **first()** — premier résultat ou `null` (pas d'exception).
- **get()** — exécute ; rien ne part en base avant.
- **dd()** — dump and die : affiche SQL + bindings, à retirer.

### 02 · Les filtres optionnels
- **$request->query('x')** — lit la query string ; absent = `null`, valeurs en chaînes.
- **when(valeur, fn)** — ajoute le `where` seulement si le paramètre est présent.
- **Formulaire GET** — champs dans l'URL ; `name` ↔ `query('name')`.
- **Mémoire** — `value="{{ request('search') }}"`, `@selected(...)`.
- **Piège** — `$categories` non passée → « Undefined variable $categories ».
```php
->when($request->query('category'), fn ($q, $id) => $q->where('category_id', $id))
```

### 03 · Mesurer avec la Debugbar
- **Debugbar** — profiler : requêtes, SQL réel, durées, doublons.
- **--dev** — installée en `require-dev`, jamais en production.
- **Rituel** — recharger → onglet Queries → compter → noter avant/après.
- **Désactiver** — `DEBUGBAR_ENABLED=false` dans `.env`.
```bash
sail composer require fruitcake/laravel-debugbar --dev
```

### 04 · Eager loading — corriger le N+1
- **Lazy loading** — requête au premier accès à une relation : source du N+1.
- **with('category')** — 1 requête de plus avec `IN`, pas une jointure.
- **with([...])** — plusieurs relations d'un coup.
- **load()** — même chargement, après coup, sur un modèle déjà récupéré.
- **preventLazyLoading** — lève `LazyLoadingViolationException` hors production.
- **Piège** — `with()` inutile = requête gaspillée ; toujours mesurer.
```php
Model::preventLazyLoading(! app()->isProduction()); // AppServiceProvider::boot()
```

### 05 · Agrégats et classement
- **count / sum / avg / max** — calcul en base, un seul chiffre revient.
- **Piège** — `$quests->count()` sur Collection compte en PHP, lignes chargées pour rien.
- **withCount / withSum / withAvg / withMax** — sous-requête, attribut sans charger la relation.
- **Nommage** — `{relation}_{fonction}_{colonne}` : `quests_count`, `quests_sum_xp`.
```php
Category::withSum('quests', 'xp')->orderByDesc('quests_sum_xp')->get();
```

### 06 · Pagination
- **paginate(n)** — remplace `get()` ; 2 requêtes : `count(*)` + `limit/offset`.
- **?page=** — page courante lue automatiquement dans l'URL.
- **links()** — génère les liens de pages (markup Tailwind).
- **withQueryString()** — conserve filtres et recherche dans les liens.
- **simplePaginate()** — sans total, Précédent / Suivant seulement.
- **Piège** — sans `withQueryString()`, page 2 perd les filtres ; `limit` ≠ pagination.

### Annexe · Query Builder ou Eloquent ?
- **Query Builder** — `DB::table('quests')` : même chaîne, renvoie des `stdClass`.
- **Eloquent** — renvoie des modèles : casts, relations, `save()` ; choix par défaut.
- **whereHas** — filtre par relation (`EXISTS`) ; `with` charge, ne filtre pas.
- **groupBy / having** — agrégat par groupe ; `having` = `where` des groupes.

## S5 — Formulaires, validation et services

### 01 · Le formulaire de création
- **GET / POST** — GET lit (URL, favori) ; POST modifie (corps de requête).
- **Deux routes** — `GET /quests/create` affiche, `POST /quests` traite.
- **name** — clé de la saisie côté serveur ; absent = champ jamais reçu.
- **name="location_ids[]"** — tableau de valeurs ; case décochée n'envoie rien.
- **Piège** — `view()` après POST : F5 renvoie le formulaire, doublon ; rediriger (PRG).
- **with('success', ...)** — donnée flash : lue par `session('success')`, une seule requête.
- **Piège** — `create()` exige `$fillable` (S3) ; `/quests/create` protégée par `whereNumber('id')`.

### 02 · Le jeton CSRF
- **CSRF** — site tiers fait envoyer par votre navigateur une requête authentifiée.
- **@csrf** — champ caché `_token`, jeton aléatoire propre à la session.
- **ValidateCsrfToken** — middleware actif par défaut ; compare `_token` au jeton de session.
- **Règle** — tout formulaire POST contient `@csrf` ; GET non vérifié.
- **Piège** — 419 Page Expired : `@csrf` oublié sous la balise `<form>`.

### 03 · La validation
- **$request->validate([...])** — règles par champ ; renvoie uniquement les données validées.
- **Règles** — `required`, `string`, `integer`, `min`/`max`, `in`, `exists:table,col`, `unique`.
- **Échec** — `ValidationException` → 302 vers le formulaire, erreurs et saisie flashées.
- **old('title')** — saisie ratée ; `<textarea>` entre balises, sinon `@selected` / `@checked`.
- **@error('title')** — affiche `$message` seulement si ce champ a échoué.
- **Piège** — case décochée : champ caché `featured=0` avant la case.
- **Piège** — `required` HTML contournable ; seule la validation serveur protège la base.
```blade
<input name="title" value="{{ old('title') }}">
@error('title') <p class="error">{{ $message }}</p> @enderror
```

### 04 · Éditer et supprimer
- **Routes** — `GET /quests/{id}/edit`, `PUT /quests/{id}`, `DELETE /quests/{id}`.
- **Method spoofing** — `@method('PUT')` → champ caché `_method` dans un POST.
- **old('title', $quest->title)** — saisie ratée, sinon valeur actuelle.
- **Supprimer** — formulaire POST + `@csrf` + `@method('DELETE')`, jamais un lien GET.
- **Piège** — `<form method="PUT">` part en GET : HTML ne connaît que GET/POST.
- **Piège** — `delete()` sans `locations()->detach()` → 500, clé étrangère du pivot.
```blade
<form method="POST" action="{{ route('quests.update', $quest) }}">
    @csrf
    @method('PUT')
```

### 05 · Route::resource et route model binding
- **Route::resource** — sept routes nommées : index, create, store, show, edit, update, destroy.
- **only / except** — ne générer qu'une partie des sept routes.
- **Route model binding** — `{quest}` + `Quest $quest` : modèle chargé, 404 si absent.
- **--resource --model=Quest** — génère les sept méthodes vides, signatures typées.
- **Piège** — `{quest}` ≠ `$quete` : pas de binding, `Quest` vide sans erreur.
```php
Route::resource('quests', QuestController::class)->where(['quest' => '[0-9]+']);
```

### 06 · Les Form Requests
- **Form Request** — classe : autorisation + règles + messages, injectée à la place de `Request`.
- **authorize()** — `true` pour l'instant ; `false` → 403 ; vraie autorisation en S6.
- **rules() / messages()** — règles du chapitre 03 déplacées, messages `champ.règle`.
- **$request->validated()** — données validées ; échec = `store()` jamais exécutée.
- **Store / Update** — une classe par formulaire, règles ajustables séparément.
```bash
sail artisan make:request StoreQuestRequest
```

### Annexe · Les services
- **Service** — classe de règles métier, hors contrôleur, sans `Request` ni `redirect`.
- **app/Services/** — créé à la main, pas de `make:service`.
- **Injection** — `__construct(private QuestService $quests)` ; le conteneur instancie, jamais `new`.
- **Contrôleur fin** — HTTP en entrée → service travaille → HTTP en sortie.
- **Piège** — surdimensionné pour un CRUD sans règle métier.

## Commandes à connaître

| Commande | Effet |
|---|---|
| `sail up -d` / `sail down` | Démarrer / arrêter les conteneurs |
| `sail artisan about` | Résumé : versions Laravel, PHP, environnement |
| `sail artisan route:list` | Lister toutes les routes |
| `sail artisan tinker` | Console PHP interactive dans l'application |
| `sail artisan make:controller X` | Générer un contrôleur |
| `sail artisan make:model X -m` | Générer modèle + migration |
| `sail artisan make:migration ...` | Générer une migration seule |
| `sail artisan make:seeder X` / `make:factory X` | Générer seeder / factory |
| `sail artisan migrate` | Jouer les migrations en attente |
| `sail artisan migrate:status` | Voir migrations jouées / en attente |
| `sail artisan migrate:rollback` | Annuler le dernier lot (batch) |
| `sail artisan migrate:fresh --seed` | Tout supprimer, rejouer, peupler (dev uniquement) |
| `sail artisan db:seed --class=X` | Lancer un seeder |
| `sail npm run dev` / `run build` | Serveur Vite / compilation des assets |
| `sail composer require pkg --dev` | Installer un paquet en dépendance de dev |
| `sail artisan make:request X` | Générer une Form Request (`app/Http/Requests/`) |
| `sail artisan make:controller X --resource --model=Y` | Contrôleur aux sept méthodes CRUD |
| `sail artisan route:list --name=quests` | Lister les routes d'une ressource |
| `sail artisan lang:publish` | Publier les fichiers de traduction |

## Lexique express

- **Framework** — cadre qui structure l'application et appelle votre code.
- **Front controller** — point d'entrée unique de toutes les requêtes.
- **Route** — association URL + verbe → code qui la traite.
- **Contrôleur** — classe qui reçoit la requête et retourne la réponse.
- **Closure** — fonction anonyme, souvent passée à une route.
- **Directive** — instruction Blade préfixée par `@`.
- **Escaping** — neutralisation du HTML spécial contre l'injection.
- **ORM / Eloquent** — couche objets ↔ tables ; Eloquent = ORM de Laravel.
- **Migration** — script versionné de changement de schéma.
- **Foreign key** — colonne pointant la PK d'une autre table.
- **Pivot table** — table intermédiaire d'une relation N-N.
- **Mass assignment** — remplissage d'un modèle via un tableau d'attributs.
- **Seeder / Factory** — données de départ / fausses données plausibles.
- **N+1** — une requête pour la liste, plus une par ligne.
- **Eager loading** — relations chargées à l'avance, une requête par relation.
- **CSRF** — attaque : requête authentifiée envoyée à l'insu de l'utilisateur.
- **Validation rule** — contrainte qu'une donnée doit satisfaire pour être acceptée.
- **Flash data** — donnée de session qui ne survit qu'à la requête suivante.
- **Post/Redirect/Get** — répondre à un POST par une redirection vers un GET.
- **Method spoofing** — champ caché `_method` simulant PUT, PATCH ou DELETE.
- **Resource controller** — contrôleur aux sept actions CRUD conventionnelles.
