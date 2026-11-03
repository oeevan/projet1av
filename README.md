# 🎮 WishFlix

> Catalogue de jeux vidéo avec wishlist. Projet fil rouge du cours **Programmation avancée** (Laravel).

![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.5-777BB4?logo=php&logoColor=white)
![MariaDB](https://img.shields.io/badge/MariaDB-11-003545?logo=mariadb&logoColor=white)
![Docker](https://img.shields.io/badge/Sail-Docker-2496ED?logo=docker&logoColor=white)

---

## ✨ Fonctionnalités

- 🏠 **Accueil** : jeu « à la une », catégories avec leur nombre de jeux, grille de 20 jeux
- 📄 **Fiche jeu** : catégorie, plateformes et statut, sur une URL lisible (`/game/the-witcher-3`)
- ❤️ **Wishlist** et 🔐 **Connexion** (pages statiques pour l'instant)
- 🚫 **Page 404** personnalisée

---

## 🚀 Démarrer

> Prérequis : **Docker Desktop** lancé.

```bash
./vendor/bin/sail up -d                      # 1. démarre PHP et MariaDB
./vendor/bin/sail artisan migrate:fresh --seed # 2. crée les tables et les remplit
./vendor/bin/sail npm run dev                # 3. sert le CSS (laisser ouvert)
```

👉 Ouvre **http://localhost**

Pour arrêter : `./vendor/bin/sail down`

> 💡 Astuce : `alias sail='./vendor/bin/sail'` pour taper simplement `sail ...`.

---

## 🗺️ Pages

| URL | Page |
|---|---|
| `/` | Catalogue |
| `/game/{slug}` | Fiche d'un jeu (ex. `/game/elden-ring`) |
| `/wishlist` | Ma wishlist |
| `/login` | Connexion |
| `/n-importe-quoi` | Page 404 |

---

## 🗄️ Base de données

```
categories ──< games >──< platforms
   (1)        (N)  (N)      (N)
               └── game_platform (pivot)
```

| Relation | Code |
|---|---|
| Un jeu a **une** catégorie | `Game::category()` → `belongsTo` |
| Une catégorie a **plusieurs** jeux | `Category::games()` → `hasMany` |
| Un jeu a **plusieurs** plateformes, et inversement | `belongsToMany`, avec la pivot `game_platform` |

Statut d'un jeu : enum `GameStatus`, qui vaut `Released`, `Upcoming` ou `EarlyAccess`.

---

## 📁 Où est quoi ?

```
app/
├── Enums/GameStatus.php          → les statuts possibles
├── Http/Controllers/GameController.php → prépare les données des pages
└── Models/                       → Game, Category, Platform
database/
├── migrations/                   → structure des tables
├── factories/                    → fausses données (fake())
└── seeders/                      → remplissage de la base
resources/
├── views/                        → pages Blade (layouts/, partials/, errors/)
└── css/                          → un fichier CSS par page
routes/web.php                    → les URL
REPONSES.md                       → réponses aux questions du cours
```

---

## 🛠️ Commandes utiles

| Commande | Ça fait quoi |
|---|---|
| `sail artisan migrate:fresh --seed` | remet la base à zéro avec des données |
| `sail artisan route:list` | liste les routes |
| `sail artisan tinker` | ouvre une console PHP pour tester |
| `sail artisan view:clear` | vide le cache des vues |

---

## 📚 Avancement du cours

- [x] **Séance 1** : installation (Sail, Git, `.env`)
- [x] **Séance 2** : routes nommées, contrôleur, layout Blade, 404
- [x] **Séance 3** : Eloquent (modèles, relations, factories, seeders) et bonus (slug, enum)
- [ ] **Séance 4** : corriger le N+1 (eager loading)
