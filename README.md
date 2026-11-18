# 🎮 WishFlix

> Catalogue de jeux vidéo avec wishlist. Projet fil rouge du cours **Programmation avancée — Framework Laravel** (IUT Metz).

![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.5-777BB4?logo=php&logoColor=white)
![MariaDB](https://img.shields.io/badge/MariaDB-11-003545?logo=mariadb&logoColor=white)
![Docker](https://img.shields.io/badge/Sail-Docker-2496ED?logo=docker&logoColor=white)

---

## 🚀 Installation (3 commandes)

> **Seul prérequis : Docker Desktop lancé.** Pas besoin de PHP, Composer ni Node sur ta machine : tout tourne dans Docker.
> Les commandes sont **les mêmes sur Mac, Linux et Windows** (PowerShell ou le terminal de VS Code).

Depuis le dossier du projet :

```bash
# 1. Installe les dépendances PHP et crée le .env
docker run --rm -v "${PWD}:/app" -w /app composer:2 sh -c "composer install --ignore-platform-reqs && cp -n .env.example .env"

# 2. Démarre PHP + MariaDB (la 1re fois : ~5 min, le temps de construire l'image)
docker compose up -d --wait

# 3. Prépare tout : clé, base remplie (20 jeux), CSS compilés
docker compose exec -u sail laravel.test composer setup
```

👉 Ouvre **http://localhost**

| Ensuite | Commande |
|---|---|
| Arrêter | `docker compose down` |
| Relancer | `docker compose up -d` |
| Remettre la base à zéro | `docker compose exec laravel.test php artisan migrate:fresh --seed` |

> 💡 **Le port 80 ou 3306 est déjà pris ?** Ajoute `APP_PORT=8080` ou `FORWARD_DB_PORT=3307` dans `.env`, puis relance l'étape 2 (le site sera sur http://localhost:8080).

### Pour développer (CSS rechargé en direct)

```bash
docker compose exec -u sail laravel.test npm run dev   # laisser ouvert
```

> Sur Mac/Linux, le raccourci de Sail marche aussi : `./vendor/bin/sail up -d`, `./vendor/bin/sail artisan ...`, `./vendor/bin/sail npm run dev`.

---

## ✨ Fonctionnalités

- 🏠 **Accueil** : jeu « à la une », les 6 mieux notés, catalogue **paginé** (9 par page)
- 🔎 **Recherche** par titre + **filtre** par catégorie (avec le nombre de jeux), combinables, conservés d'une page à l'autre
- 🏆 Catégories classées par **note moyenne**
- 📄 **Fiche jeu** sur une URL lisible (`/game/the-witcher-3`) : catégorie, plateformes, statut
- ❤️ **Wishlist** et 🔐 **Connexion** (pages statiques pour l'instant)
- 🚫 **Page 404** aux couleurs de WishFlix
- 🐞 **Debugbar** en bas de page (en dev) pour voir les requêtes SQL

---

## 🗺️ Pages

| URL | Page |
|---|---|
| `/` | Catalogue |
| `/?search=zelda&category=2&page=2` | Catalogue filtré |
| `/game/{slug}` | Fiche d'un jeu (ex. `/game/elden-ring`) |
| `/wishlist` | Ma wishlist |
| `/login` | Connexion |
| `/n-importe-quoi` | Page 404 |

---

## 🗄️ Base de données

```
categories ──< games >──< platforms
                  └── game_platform (pivot)
```

| Relation | Code |
|---|---|
| Un jeu a **une** catégorie | `Game::category()` → `belongsTo` |
| Une catégorie a **plusieurs** jeux | `Category::games()` → `hasMany` |
| Un jeu a **plusieurs** plateformes, et inversement | `belongsToMany`, avec la pivot `game_platform` |

Statut d'un jeu : enum `GameStatus` (`Released`, `Upcoming`, `EarlyAccess`).

---

## 📁 Où est quoi ?

```
app/
├── Enums/GameStatus.php                → les statuts possibles
├── Http/Controllers/GameController.php → requêtes du catalogue et de la fiche
├── Models/                             → Game, Category, Platform
└── Providers/AppServiceProvider.php    → preventLazyLoading (alerte N+1)
database/
├── migrations/                         → structure des tables
├── factories/                          → fausses données (fake())
└── seeders/                            → remplissage de la base
resources/
├── views/                              → pages Blade (layouts/, partials/, errors/)
└── css/                                → app.css (Tailwind + DaisyUI) + un CSS par page
routes/web.php                          → les URL
tests/Feature/                          → tests du catalogue
REPONSES.md                             → réponses aux questions des séances
FICHE-COURS.md                          → fiche de révision du cours
```

---

## 🛠️ Commandes utiles

Préfixe : `docker compose exec laravel.test` (ou `./vendor/bin/sail` sur Mac/Linux).

| Commande | Ça fait quoi |
|---|---|
| `php artisan migrate:fresh --seed` | remet la base à zéro avec des données |
| `php artisan route:list` | liste les routes |
| `php artisan tinker` | console PHP pour tester |
| `php artisan test` | lance les tests |
| `php artisan view:clear` | vide le cache des vues |

---

## 📚 Avancement du cours

- [x] **Séance 1** : installation (Sail, Git, `.env`), lecture d'une requête
- [x] **Séance 2** : routes nommées, contrôleur, layout Blade, Vite, 404 + bonus (Tailwind/DaisyUI via Vite, lien actif)
- [x] **Séance 3** : Eloquent (modèles, relations, factories, seeders) + bonus (slug, enum)
- [x] **Séance 4** : à la une, filtres, recherche, Debugbar, N+1 corrigé (30 → 13 requêtes), `withCount`, pagination + bonus (`withAvg`, `inRandomOrder`)
