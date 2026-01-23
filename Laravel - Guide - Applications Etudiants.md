# Guide Laravel FINAL - Projet Gestion Étudiants

## 🎯 Guide ULTRA-COMPLET 100% fonctionnel

**Tout est inclus dans ce guide :**

- ✅ Installation complète
- ✅ Bootstrap 5.3 avec SCSS
- ✅ Toutes les vues (étudiants, classes, formateurs)
- ✅ Vues Jetstream modifiées pour Bootstrap
- ✅ Menu vertical admin + menu horizontal front
- ✅ Thème clair/sombre

---

# Table des matières

0. [Pour les développeurs CodeIgniter 4](#pour-les-développeurs-codeigniter-4)
1. [Installation](#1-installation)
2. [Jetstream et Bootstrap](#2-jetstream-et-bootstrap)
3. [Configuration](#3-configuration)
4. [Base de données](#4-base-de-données)
5. [Contrôleurs](#5-contrôleurs)
6. [Routes](#6-routes)
7. [Layouts](#7-layouts)
8. [Vues Front](#8-vues-front)
9. [Vues Admin](#9-vues-admin)
10. [Vues Auth (Jetstream modifiées)](#10-vues-auth-jetstream-modifiées)
11. [Vues Profil](#11-vues-profil)
12. [Seeders](#12-seeders)
13. [Tests](#13-tests)

---

# Pour les développeurs CodeIgniter 4

## 🔄 Principales différences CI4 vs Laravel

### Architecture générale

| Aspect                     | CodeIgniter 4              | Laravel                                |
| -------------------------- | -------------------------- | -------------------------------------- |
| **Philosophy**             | Simple, léger, pragmatique | Convention over configuration, complet |
| **Courbe d'apprentissage** | Douce                      | Plus raide mais très documenté         |
| **ORM**                    | Query Builder manuel       | Eloquent ORM (Active Record)           |
| **CLI**                    | `spark`                    | `artisan`                              |
| **Templates**              | PHP natif                  | Blade (plus puissant)                  |
| **Migrations**             | Simples                    | Plus complètes avec rollback           |
| **Package manager**        | Manuel                     | Composer (natif)                       |

---

## 📁 Structure des dossiers

### CodeIgniter 4

```
app/
├── Controllers/
├── Models/
├── Views/
├── Config/
└── Database/
    └── Migrations/
```

### Laravel

```
app/
├── Http/
│   ├── Controllers/
│   └── Middleware/
├── Models/
└── ...
resources/
├── views/
└── ...
database/
├── migrations/
└── seeders/
routes/
└── web.php
```

**💡 À retenir :**

- Laravel sépare les vues dans `resources/views/` au lieu de `app/Views/`
- Les contrôleurs sont dans `app/Http/Controllers/`
- Les routes sont dans des fichiers dédiés (`routes/web.php`, `routes/api.php`)

---

## 🛠️ Artisan vs Spark

### CodeIgniter 4 (Spark)

```bash
php spark serve
php spark migrate
php spark make:model User
php spark make:controller Users
```

### Laravel (Artisan)

```bash
php artisan serve
php artisan migrate
php artisan make:model User -m    # -m crée aussi la migration
php artisan make:controller UserController --resource
```

**💡 Artisan est BEAUCOUP plus puissant :**

```bash
# Créer un modèle avec migration, controller, factory, seeder en UNE commande
php artisan make:model Student -mcr
# -m = migration
# -c = controller
# -r = resource controller (CRUD)

# Autres commandes utiles
php artisan tinker              # Console interactive (super utile !)
php artisan route:list          # Liste toutes les routes
php artisan migrate:fresh       # Réinitialise la DB
php artisan make:middleware     # Créer un middleware
php artisan optimize            # Optimiser pour la production
php artisan cache:clear         # Vider le cache
```

---

## 💾 Base de données : Query Builder vs Eloquent

### CodeIgniter 4 - Query Builder

```php
// Dans un modèle CI4
class UserModel extends Model
{
    protected $table = 'users';

    public function getActiveUsers()
    {
        return $this->where('status', 'active')
                    ->findAll();
    }
}

// Dans un controller
$userModel = new UserModel();
$users = $userModel->getActiveUsers();
```

### Laravel - Eloquent ORM

```php
// Le modèle Laravel
class User extends Model
{
    protected $fillable = ['name', 'email'];

    // Pas besoin de méthode, Eloquent est magique !
}

// Dans un controller - BEAUCOUP plus simple
$users = User::where('status', 'active')->get();

// Ou encore mieux avec les scopes
class User extends Model
{
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}

$users = User::active()->get();
```

**💡 Eloquent c'est de la magie :**

```php
// Créer
$user = User::create([
    'name' => 'John',
    'email' => 'john@example.com'
]);

// Lire
$user = User::find(1);
$users = User::all();
$users = User::where('age', '>', 18)->get();

// Mettre à jour
$user = User::find(1);
$user->name = 'Jane';
$user->save();

// Ou directement
User::where('id', 1)->update(['name' => 'Jane']);

// Supprimer
$user->delete();
User::destroy(1);
User::where('age', '<', 18)->delete();
```

---

## 🔗 Relations : CI4 vs Laravel

### CodeIgniter 4 - Relations manuelles

```php
class UserModel extends Model
{
    public function getPosts($userId)
    {
        $postModel = new PostModel();
        return $postModel->where('user_id', $userId)->findAll();
    }
}
```

### Laravel - Relations automatiques

```php
// Modèle User
class User extends Model
{
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}

// Modèle Post
class Post extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

// Utilisation - C'EST MAGIQUE !
$user = User::find(1);
$posts = $user->posts;  // Récupère tous les posts de l'utilisateur

$post = Post::find(1);
$author = $post->user;  // Récupère l'auteur du post

// Avec eager loading (optimisation)
$users = User::with('posts')->get();
```

**💡 Types de relations Laravel :**

```php
// One to One
public function phone() {
    return $this->hasOne(Phone::class);
}

// One to Many
public function posts() {
    return $this->hasMany(Post::class);
}

// Many to Many
public function roles() {
    return $this->belongsToMany(Role::class);
}

// Has Many Through (indirect)
public function posts() {
    return $this->hasManyThrough(Post::class, Country::class);
}
```

---

## 🗺️ Routes : CI4 vs Laravel

### CodeIgniter 4

```php
// app/Config/Routes.php
$routes->get('/', 'Home::index');
$routes->get('users', 'Users::index');
$routes->get('users/(:num)', 'Users::show/$1');
$routes->post('users/create', 'Users::create');
```

### Laravel

```php
// routes/web.php
Route::get('/', [HomeController::class, 'index']);
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);

// Ou ENCORE MIEUX - Route Resource (TOUTES les routes CRUD en 1 ligne !)
Route::resource('users', UserController::class);
// Crée automatiquement :
// GET    /users           -> index
// GET    /users/create    -> create
// POST   /users           -> store
// GET    /users/{id}      -> show
// GET    /users/{id}/edit -> edit
// PUT    /users/{id}      -> update
// DELETE /users/{id}      -> destroy

// Groupes de routes
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::resource('students', StudentController::class);
    Route::resource('classes', ClasseController::class);
});

// Nommage des routes
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// Utilisation dans les vues : route('dashboard')
```

---

## 🎨 Vues : PHP natif vs Blade

### CodeIgniter 4 - PHP natif

```php
<!-- app/Views/users.php -->
<?php foreach ($users as $user): ?>
    <p><?= esc($user['name']) ?></p>
<?php endforeach; ?>
```

### Laravel - Blade (beaucoup plus élégant)

```blade
<!-- resources/views/users.blade.php -->
@foreach ($users as $user)
    <p>{{ $user->name }}</p>
@endforeach

<!-- Directives Blade super utiles -->
@if ($users->count() > 0)
    <p>Il y a des utilisateurs</p>
@else
    <p>Aucun utilisateur</p>
@endif

@auth
    <p>Vous êtes connecté</p>
@endauth

@guest
    <p>Veuillez vous connecter</p>
@endguest

<!-- Layouts et sections -->
@extends('layouts.app')

@section('content')
    <h1>Mon contenu</h1>
@endsection

<!-- Composants -->
<x-alert type="success">
    Opération réussie !
</x-alert>

<!-- Inclusion de sous-vues -->
@include('partials.header')

<!-- Boucles avec $loop -->
@foreach ($users as $user)
    @if ($loop->first)
        <p>Premier élément</p>
    @endif

    <p>{{ $user->name }}</p>

    @if ($loop->last)
        <p>Dernier élément</p>
    @endif
@endforeach
```

**💡 Blade échappe automatiquement les données avec `{{ }}` (sécurité XSS) :**

```blade
{{ $user->name }}        <!-- Échappé (sécurisé) -->
{!! $html_content !!}    <!-- Non échappé (dangereux, à utiliser avec précaution) -->
```

---

## 📝 Migrations : CI4 vs Laravel

### CodeIgniter 4

```php
class CreateUsers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
```

### Laravel - Plus fluide

```php
class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();  // created_at et updated_at automatiques !
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
```

**💡 Types de colonnes Laravel (plus de 30 types disponibles) :**

```php
$table->id();                           // BIGINT UNSIGNED AUTO_INCREMENT
$table->string('name', 100);            // VARCHAR(100)
$table->text('description');            // TEXT
$table->integer('age');                 // INT
$table->decimal('price', 8, 2);         // DECIMAL(8,2)
$table->boolean('is_active');           // BOOLEAN
$table->date('birthdate');              // DATE
$table->timestamps();                   // created_at + updated_at
$table->softDeletes();                  // deleted_at (suppression douce)
$table->foreignId('user_id')            // Clé étrangère
      ->constrained()
      ->onDelete('cascade');
```

---

## 🔐 Validation : CI4 vs Laravel

### CodeIgniter 4

```php
// Dans le controller
$validation = \Config\Services::validation();

$validation->setRules([
    'email' => 'required|valid_email',
    'name'  => 'required|min_length[3]'
]);

if (!$validation->withRequest($this->request)->run()) {
    return redirect()->back()->withInput()->with('errors', $validation->getErrors());
}
```

### Laravel - Plus simple et puissant

```php
// Dans le controller
$validated = $request->validate([
    'email' => 'required|email|unique:users,email',
    'name'  => 'required|min:3|max:255',
    'age'   => 'required|integer|min:18',
    'birthdate' => 'required|date|before:today'
]);

// Si validation échoue, Laravel redirige automatiquement avec les erreurs !

// Dans la vue, les erreurs sont automatiquement disponibles
@error('email')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

// Ou avec Bootstrap
<input type="email" class="form-control @error('email') is-invalid @enderror">
@error('email')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
```

**💡 Règles de validation Laravel (plus de 60 règles) :**

```php
'email' => 'required|email|unique:users,email',
'password' => 'required|min:8|confirmed',  // password_confirmation doit exister
'age' => 'required|integer|between:18,65',
'terms' => 'accepted',
'avatar' => 'nullable|image|max:2048',  // 2MB max
'tags' => 'array|min:1',
'tags.*' => 'string|distinct',
'website' => 'url',
'phone' => 'regex:/^[0-9]{10}$/',
```

---

## 🎯 Tinker - La console interactive (n'existe pas dans CI4)

**Tinker** est un outil EXTRAORDINAIRE de Laravel pour tester du code en temps réel.

```bash
php artisan tinker
```

**Exemples d'utilisation :**

```php
// Créer un utilisateur
>>> $user = new App\Models\User();
>>> $user->name = 'John Doe';
>>> $user->email = 'john@example.com';
>>> $user->password = Hash::make('password');
>>> $user->save();
=> true

// Ou plus court
>>> $user = App\Models\User::create(['name' => 'Jane', 'email' => 'jane@test.com', 'password' => Hash::make('pass')]);

// Récupérer des données
>>> $users = App\Models\User::all();
>>> $users->count();
=> 10

>>> $user = App\Models\User::find(1);
>>> $user->name;
=> "John Doe"

// Tester des relations
>>> $user = App\Models\User::find(1);
>>> $user->posts;  // Voir tous les posts de l'utilisateur
>>> $user->posts->count();

// Mettre à jour
>>> $user->name = 'John Smith';
>>> $user->save();

// Supprimer
>>> $user->delete();

// Exécuter des requêtes
>>> App\Models\User::where('role', 'admin')->get();

// Tester des méthodes
>>> $student = App\Models\Student::first();
>>> $student->full_name;
=> "Pierre Durand"

// Vider une table
>>> App\Models\Student::truncate();

// Quitter
>>> exit
```

**💡 Tinker est parfait pour :**

- Tester rapidement du code
- Créer des données de test
- Déboguer des problèmes
- Expérimenter avec Eloquent
- Créer un utilisateur admin rapidement

---

## 🔧 Environnement : CI4 vs Laravel

### CodeIgniter 4

```env
# .env
CI_ENVIRONMENT = development

database.default.hostname = localhost
database.default.database = mydb
database.default.username = root
database.default.password = 
```

### Laravel - Plus structuré

```env
# .env
APP_NAME=Laravel
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mydb
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
```

**💡 Accès aux variables d'environnement :**

```php
// CodeIgniter 4
env('database.default.hostname');

// Laravel
env('DB_HOST');
config('database.connections.mysql.host');  // Depuis config/database.php
```

---

## 📦 Middleware : CI4 vs Laravel

### CodeIgniter 4 - Filters

```php
// app/Filters/AuthFilter.php
class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
    }
}
```

### Laravel - Middleware (plus flexible)

```php
// app/Http/Middleware/CheckRole.php
class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!auth()->user() || auth()->user()->role !== $role) {
            abort(403);
        }

        return $next($request);
    }
}

// Utilisation dans les routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index']);
});
```

---

## 🎨 Helpers et Facades

### CodeIgniter 4 - Helpers

```php
// Charger un helper
helper('form');
helper(['form', 'url']);

// Utiliser
echo form_open('login');
echo base_url('users');
```

### Laravel - Facades (super pratiques)

```php
// Pas besoin de charger, disponibles partout !

// Routes
Route::get('/', function () {});

// Auth
Auth::user();
Auth::check();

// DB
DB::table('users')->get();

// Storage
Storage::put('file.txt', 'content');

// Cache
Cache::put('key', 'value', 60);
Cache::get('key');

// Session
Session::put('key', 'value');
Session::get('key');

// Mail
Mail::to('user@example.com')->send(new WelcomeEmail());

// Hash
Hash::make('password');
Hash::check('password', $hash);
```

---

## 🧪 Testing : CI4 vs Laravel

### CodeIgniter 4

```php
class ExampleTest extends CIUnitTestCase
{
    public function testExample()
    {
        $this->assertTrue(true);
    }
}
```

### Laravel - Plus complet

```php
class UserTest extends TestCase
{
    use RefreshDatabase;  // Réinitialise la DB entre chaque test

    public function test_user_can_be_created()
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', [
            'email' => $user->email
        ]);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }
}

// Exécuter les tests
php artisan test
```

---

## 📊 Collections : La puissance de Laravel

En CI4, vous travaillez avec des arrays. En Laravel, vous avez les **Collections** qui sont des arrays sous stéroïdes !

```php
// CI4 - Arrays classiques
$users = $userModel->findAll();
$names = [];
foreach ($users as $user) {
    $names[] = $user['name'];
}

// Laravel - Collections (magiques !)
$users = User::all();  // Retourne une Collection
$names = $users->pluck('name');  // ['John', 'Jane', 'Bob']

// Filtrer
$admins = $users->filter(function ($user) {
    return $user->role === 'admin';
});

// Map
$emails = $users->map(function ($user) {
    return $user->email;
});

// Grouper
$byRole = $users->groupBy('role');

// Compter
$count = $users->count();

// Trier
$sorted = $users->sortBy('name');

// Prendre les 5 premiers
$first5 = $users->take(5);

// Chaîner les méthodes
$adminEmails = User::all()
    ->filter(fn($u) => $u->role === 'admin')
    ->sortBy('name')
    ->pluck('email');
```

---

## 🚀 Packages et Composer

### CodeIgniter 4

Installation manuelle de la plupart des bibliothèques.

### Laravel - Écosystème énorme

```bash
# Installer un package
composer require laravel/jetstream
composer require barryvdh/laravel-debugbar

# Laravel a des packages officiels géniaux :
composer require laravel/sanctum      # API authentication
composer require laravel/horizon      # Queue monitoring
composer require laravel/telescope    # Debugging tool
composer require spatie/laravel-permission  # Gestion des permissions
```

---

## 🎓 Conseils pour bien débuter avec Laravel

### 1. **Utilisez Tinker constamment**

```bash
php artisan tinker
>>> App\Models\User::count();  # Tester rapidement
```

### 2. **Lisez la documentation officielle**

https://laravel.com/docs - C'est la MEILLEURE documentation de tous les frameworks PHP.

### 3. **Utilisez les conventions Laravel**

```php
// ✅ BON - Laravel devine tout automatiquement
class User extends Model
{
    // La table est automatiquement "users"
    // La clé primaire est "id"
    // Les timestamps sont automatiques
}

// ❌ À éviter - Surcharger inutilement
class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = true;
}
```

### 4. **Maîtrisez Eloquent progressivement**

```php
// Niveau 1 - Basique
User::all();
User::find(1);
User::where('role', 'admin')->get();

// Niveau 2 - Relations
$user->posts;
$user->posts()->where('published', true)->get();

// Niveau 3 - Eager Loading (performance)
User::with('posts')->get();

// Niveau 4 - Scopes et accesseurs
User::active()->verified()->get();
$user->full_name;  // Accesseur
```

### 5. **Utilisez les commandes Artisan**

```bash
php artisan route:list        # Voir toutes les routes
php artisan make:model Post -mcr  # Créer modèle + migration + controller
php artisan migrate:fresh --seed  # Réinitialiser la DB avec données
php artisan optimize:clear        # Vider tous les caches
```

### 6. **Installez Laravel Debugbar** (indispensable en dev)

```bash
composer require barryvdh/laravel-debugbar --dev
```

Affiche toutes les requêtes SQL, le temps d'exécution, les variables, etc.

### 7. **Utilisez VS Code avec les bonnes extensions**

- Laravel Extension Pack
- Laravel Blade Snippets
- Laravel Artisan
- PHP Intelephense

### 8. **N'ayez pas peur des "magic methods"**

Laravel utilise beaucoup de magie PHP (facades, eloquent, etc.). Au début c'est déroutant, mais c'est ce qui rend Laravel si productif.

---

## 📚 Ressources d'apprentissage

### Documentation officielle

- **Laravel Docs** : https://laravel.com/docs
- **Laracasts** : https://laracasts.com (vidéos excellentes, certaines gratuites)

### Cours gratuits

- Laracasts "Laravel from Scratch"
- FreeCodeCamp Laravel sur YouTube
- Grafikart Laravel (en français)

### Cheatsheets

- Laravel Cheat Sheet : https://learnxinyminutes.com/docs/laravel/
- Eloquent Cheat Sheet : devhints.io/laravel-eloquent

---

## 🆚 Tableau récapitulatif CI4 vs Laravel

| Fonctionnalité             | CodeIgniter 4        | Laravel                         |
| -------------------------- | -------------------- | ------------------------------- |
| **ORM**                    | Query Builder manuel | Eloquent (automatique)          |
| **Routes**                 | Routes.php           | web.php, api.php, Resource      |
| **Vues**                   | PHP natif            | Blade (plus puissant)           |
| **Validation**             | Manuelle             | Automatique avec redirection    |
| **CLI**                    | Spark (basique)      | Artisan (très puissant)         |
| **Console interactive**    | ❌                    | ✅ Tinker                        |
| **Migrations**             | Basiques             | Avancées avec rollback          |
| **Relations**              | Manuelles            | Automatiques (Eloquent)         |
| **Collections**            | Arrays               | Collections (super puissantes)  |
| **Tests**                  | PHPUnit basique      | PHPUnit + helpers Laravel       |
| **Packages**               | Manuels              | Composer + écosystème énorme    |
| **Auth**                   | Shield (ajout)       | Jetstream, Breeze, Sanctum      |
| **Courbe d'apprentissage** | Douce                | Plus raide mais très productive |

---

## 💡 Les "WOW moments" de Laravel

### 1. **Eloquent est magique**

```php
// CI4
$builder = $db->table('users');
$builder->join('posts', 'posts.user_id = users.id');
$query = $builder->get();

// Laravel - Une ligne !
$users = User::with('posts')->get();
```

### 2. **Les Relations sont automatiques**

```php
$user->posts;           // Tous les posts
$post->user;            // L'auteur
$user->posts()->latest()->first();  // Dernier post
```

### 3. **Les Routes Resource**

```php
// Une ligne pour toutes les routes CRUD
Route::resource('students', StudentController::class);
```

### 4. **Blade est élégant**

```blade
@foreach ($users as $user)
    <p>{{ $user->name }}</p>
@endforeach

@auth
    <p>Bienvenue</p>
@endauth
```

### 5. **Tinker pour tout tester**

```bash
php artisan tinker
>>> User::count()
=> 42
```

# Guide complet Laravel + Bootstrap + Jetstream

## Gestion des étudiants - Version corrigée

---

## 0. PRÉPARATION DE L'ENVIRONNEMENT

### 0.1. Vérification et mise à jour des outils

#### macOS (avec Homebrew)

```bash
# Mettre à jour Homebrew
brew update
brew upgrade

# Mettre à jour PHP
brew upgrade php

# Mettre à jour Composer
brew upgrade composer
# OU mise à jour via Composer lui-même
composer self-update

# Mettre à jour Node.js et npm
brew upgrade node

# Vérifier les versions
php --version       # Requis: PHP 8.2+
composer --version  # Requis: Composer 2.x
node --version      # Requis: Node 18+
npm --version       # Requis: npm 9+
mysql --version     # Requis: MySQL 8+
```

#### Windows

```powershell
# Si Composer installé globalement
composer self-update

# Vérifier les versions
php --version
composer --version
node --version
npm --version

# Pour mettre à jour Node.js sur Windows :
# Télécharger depuis https://nodejs.org/
# Ou avec Chocolatey:
choco upgrade nodejs

# Pour mettre à jour npm
npm install -g npm@latest
```

#### Linux (Ubuntu/Debian)

```bash
# Mettre à jour les paquets système
sudo apt update
sudo apt upgrade

# Mettre à jour Composer
sudo composer self-update

# Mettre à jour Node.js via nvm (recommandé)
nvm install node
nvm use node

# OU avec apt
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt-get install -y nodejs

# Vérifier les versions
php --version
composer --version
node --version
npm --version
mysql --version
```

### 0.2. Installation de Laravel Herd (optionnel mais recommandé)

**Laravel Herd** simplifie énormément le développement local Laravel.

#### Windows

```powershell
# Télécharger depuis https://herd.laravel.com
# Installer l'exécutable
# Herd installe automatiquement PHP, Composer, et configure tout
```

#### macOS

```bash
# Télécharger depuis https://herd.laravel.com
# OU avec Homebrew
brew install --cask herd
```

**Avantages de Herd :**

- PHP, Composer, MySQL inclus
- Configuration automatique
- Domaines locaux automatiques (.test)
- Interface graphique pour gérer les sites

### 0.3. Nettoyage des caches npm (si problèmes)

```bash
# Nettoyer le cache npm
npm cache clean --force

# Supprimer node_modules et package-lock.json si nécessaire
rm -rf node_modules package-lock.json
npm install
```

---

## 1. Installation

### 1.1. Créer le projet

```bash
# Avec Herd (Windows)
cd C:\Herd
herd composer create-project laravel/laravel gestion-etudiants
cd gestion-etudiants

# Avec Herd (macOS/Linux)
cd ~/Herd
herd composer create-project laravel/laravel gestion-etudiants
cd gestion-etudiants

# Sans Herd
composer create-project laravel/laravel gestion-etudiants
cd gestion-etudiants
```

### 1.2. Configuration .env

```bash
# Windows
copy .env.example .env

# macOS/Linux
cp .env.example .env

php artisan key:generate
```

**Modifier `.env` :**

```env
APP_NAME="Gestion Étudiants"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_etudiants
DB_USERNAME=root
DB_PASSWORD=root
```

### 1.3. Créer la base de données

```bash
# Se connecter à MySQL
mysql -u root -p

# Dans MySQL, créer la base
CREATE DATABASE gestion_etudiants CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit;

# OU en une ligne (selon votre mot de passe)
mysql -u root -p -e "CREATE DATABASE gestion_etudiants CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

---

## 2. Jetstream et Bootstrap

### 2.1. Installer Jetstream

```bash
composer require laravel/jetstream
php artisan jetstream:install livewire
```

⚠️ **Important** : Choisir `livewire` (pas `inertia`)

### 2.2. Supprimer Tailwind

```bash
npm uninstall tailwindcss postcss autoprefixer @tailwindcss/forms @tailwindcss/typography
```

**Windows (PowerShell) :**

```powershell
if (Test-Path tailwind.config.js) { Remove-Item tailwind.config.js }
if (Test-Path postcss.config.js) { Remove-Item postcss.config.js }
```

**macOS/Linux :**

```bash
rm -f tailwind.config.js postcss.config.js
```

### 2.3. Installer Bootstrap

```bash
npm install bootstrap @popperjs/core bootstrap-icons sass --save-dev
```

### 2.4. Configurer Vite

**Créer/modifier `vite.config.js` :**

```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
```

### 2.5. Créer le dossier SASS

```bash
# Windows (PowerShell)
New-Item -ItemType Directory -Force -Path resources\sass

# macOS/Linux
mkdir -p resources/sass
```

### 2.6. Créer les fichiers CSS/JS

**Créer `resources/sass/app.scss` :**

```scss
// Variables Bootstrap
$primary: #0d6efd;
$secondary: #6c757d;
$success: #198754;
$danger: #dc3545;
$info: #0dcaf0;
$warning: #ffc107;
$font-family-sans-serif: 'Nunito', sans-serif;

// Import Bootstrap
@import 'bootstrap/scss/bootstrap';
@import 'bootstrap-icons/font/bootstrap-icons.css';

// ========================================
// Styles globaux
// ========================================
body {
    font-family: $font-family-sans-serif;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

main {
    flex: 1;
}

// ========================================
// Navigation
// ========================================
.navbar {
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.navbar-brand {
    font-weight: 700;
    font-size: 1.25rem;
}

// ========================================
// Sidebar Admin
// ========================================
.sidebar-admin {
    min-height: 100vh;
    background-color: #212529;
    color: white;
    padding: 0;
    position: sticky;
    top: 0;

    .nav-link {
        color: rgba(255, 255, 255, 0.8);
        padding: 1rem 1.5rem;
        border-left: 3px solid transparent;
        transition: all 0.3s;

        &:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        &.active {
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: $primary;
            color: white;
        }

        i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }
    }

    .sidebar-header {
        padding: 1.5rem;
        background-color: rgba(0, 0, 0, 0.2);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);

        h5 {
            margin: 0;
            font-weight: 700;
            font-size: 1rem;
        }

        small {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.875rem;
        }
    }
}

// ========================================
// Cards
// ========================================
.card {
    border: none;
    border-radius: 0.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    margin-bottom: 1.5rem;

    &:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }
}

.card.text-white {
    transition: all 0.3s;

    &:hover {
        transform: translateY(-4px);
    }
}

// ========================================
// Tables
// ========================================
.table {
    thead {
        background-color: #f8f9fa;

        th {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.875rem;
        }
    }

    tbody tr:hover {
        background-color: rgba($primary, 0.05);
    }
}

// ========================================
// Boutons
// ========================================
.btn {
    font-weight: 600;
    transition: all 0.3s;

    &:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
}

// ========================================
// Formulaires
// ========================================
.form-label {
    font-weight: 600;
}

.form-control,
.form-select {
    border: 2px solid #e9ecef;
    padding: 0.75rem 1rem;

    &:focus {
        border-color: $primary;
        box-shadow: 0 0 0 0.25rem rgba($primary, 0.25);
    }
}

// ========================================
// Auth pages
// ========================================
.auth-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, $primary 0%, darken($primary, 20%) 100%);

    .auth-card {
        max-width: 450px;
        width: 100%;
    }

    .auth-logo {
        text-align: center;
        margin-bottom: 2rem;

        h2 {
            color: $primary;
            font-weight: 700;
        }
    }
}

// ========================================
// Thème sombre
// ========================================
[data-bs-theme="dark"] {
    body {
        background-color: #1a1a1a;
    }

    .card {
        background-color: #212529;
        border-color: #495057;
    }

    .table {
        color: #e0e0e0;

        thead {
            background-color: #2d3238;
        }
    }

    .form-control,
    .form-select {
        background-color: #2d3238;
        border-color: #495057;
        color: #e0e0e0;
    }
}
```

**Créer `resources/js/app.js` :**

```javascript
import './bootstrap';
import 'bootstrap';

// Gestion du thème
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle');
    const htmlElement = document.documentElement;

    const savedTheme = localStorage.getItem('theme') || 'light';
    htmlElement.setAttribute('data-bs-theme', savedTheme);
    updateThemeIcon(savedTheme);

    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            const currentTheme = htmlElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';

            htmlElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        });
    }

    function updateThemeIcon(theme) {
        if (themeToggle) {
            const icon = themeToggle.querySelector('i');
            if (icon) {
                icon.className = theme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
            }
        }
    }
});
```

### 2.7. Compiler les assets

```bash
npm install
npm run build
```

---

## 3. Configuration Laravel

### 3.1. JetstreamServiceProvider

**Modifier `app/Providers/JetstreamServiceProvider.php` :**

```php
<?php

namespace App\Providers;

use App\Actions\Jetstream\DeleteUser;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;
use Laravel\Fortify\Fortify;

class JetstreamServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->configurePermissions();
        Jetstream::deleteUsersUsing(DeleteUser::class);

        // Redirection après login
        Fortify::redirects('login', function () {
            $user = auth()->user();

            if ($user && ($user->isAdmin() || $user->isFormateur())) {
                return route('admin.dashboard');
            }

            return route('front.students.index');
        });
    }

    protected function configurePermissions(): void
    {
        Jetstream::defaultApiTokenPermissions(['read']);
        Jetstream::permissions(['create', 'read', 'update', 'delete']);
    }
}
```

---

## 4. Base de données

### 4.1. ⚠️ ORDRE DES MIGRATIONS (IMPORTANT)

**L'ordre est crucial à cause des clés étrangères !**

1. Migration `users` (déjà existante)
2. Migration `add_role_to_users_table`
3. Migration `classes` (doit être créée AVANT students)
4. Migration `students` (dépend de classes)

### 4.2. Migration : Ajouter le rôle aux users

```bash
php artisan make:migration add_role_to_users_table --table=users
```

**Fichier : `database/migrations/XXXX_XX_XX_add_role_to_users_table.php` :**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'formateur', 'etudiant'])
                  ->default('etudiant')
                  ->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
```

### 4.3. Migration : Classes (AVANT Students)

```bash
php artisan make:model Classe -m
```

**Fichier : `database/migrations/XXXX_XX_XX_create_classes_table.php` :**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('formateur_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
```

### 4.4. Migration : Students (APRÈS Classes)

```bash
php artisan make:model Student -m
```

**Fichier : `database/migrations/XXXX_XX_XX_create_students_table.php` :**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique();
            $table->date('birthdate');
            $table->foreignId('classe_id')
                  ->nullable()
                  ->constrained('classes')
                  ->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
```

### 4.5. Modèles

**Modèle User : `app/Models/User.php` :**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Méthodes de rôle
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isFormateur(): bool
    {
        return $this->role === 'formateur';
    }

    public function isEtudiant(): bool
    {
        return $this->role === 'etudiant';
    }

    // Relations
    public function classes()
    {
        return $this->hasMany(Classe::class, 'formateur_id');
    }
}
```

**Modèle Student : `app/Models/Student.php` :**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'birthdate',
        'classe_id',
    ];

    protected function casts(): array
    {
        return [
            'birthdate' => 'date',
        ];
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->firstname} {$this->lastname}";
    }
}
```

**Modèle Classe : `app/Models/Classe.php` :**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'formateur_id',
    ];

    public function formateur()
    {
        return $this->belongsTo(User::class, 'formateur_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
```

### 4.6. Exécuter les migrations

```bash
php artisan migrate
```

**Si la BDD n'existe pas, Laravel vous demandera si vous voulez la créer → répondre `yes`**

---

## 5. Contrôleurs

### 5.1. Créer les contrôleurs

```bash
php artisan make:controller Admin/StudentController --resource
php artisan make:controller Admin/ClasseController --resource
php artisan make:controller Admin/FormateurController --resource
php artisan make:controller Front/StudentController
```

### 5.2. StudentController Admin

**Fichier : `app/Http/Controllers/Admin/StudentController.php` :**

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $students = Student::with('classe')->latest()->paginate(15);
        } elseif ($user->isFormateur()) {
            $classIds = $user->classes->pluck('id');
            $students = Student::whereIn('classe_id', $classIds)
                ->with('classe')
                ->latest()
                ->paginate(15);
        } else {
            abort(403);
        }

        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $classes = Classe::all();
        } elseif ($user->isFormateur()) {
            $classes = $user->classes;
        } else {
            abort(403);
        }

        return view('admin.students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'birthdate' => 'required|date|before:today',
            'classe_id' => 'nullable|exists:classes,id',
        ]);

        $user = Auth::user();
        if ($user->isFormateur() && $validated['classe_id']) {
            if (!$user->classes->contains($validated['classe_id'])) {
                abort(403);
            }
        }

        Student::create($validated);

        return redirect()->route('admin.students.index')
            ->with('success', 'Étudiant créé avec succès.');
    }

    public function show(Student $student)
    {
        $this->authorizeStudentAccess($student);
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $this->authorizeStudentAccess($student);

        $user = Auth::user();
        if ($user->isAdmin()) {
            $classes = Classe::all();
        } else {
            $classes = $user->classes;
        }

        return view('admin.students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, Student $student)
    {
        $this->authorizeStudentAccess($student);

        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'birthdate' => 'required|date|before:today',
            'classe_id' => 'nullable|exists:classes,id',
        ]);

        $user = Auth::user();
        if ($user->isFormateur() && $validated['classe_id']) {
            if (!$user->classes->contains($validated['classe_id'])) {
                abort(403);
            }
        }

        $student->update($validated);

        return redirect()->route('admin.students.index')
            ->with('success', 'Étudiant mis à jour avec succès.');
    }

    public function destroy(Student $student)
    {
        $this->authorizeStudentAccess($student);
        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Étudiant supprimé avec succès.');
    }

    private function authorizeStudentAccess(Student $student)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return;
        }

        if ($user->isFormateur()) {
            $classIds = $user->classes->pluck('id');
            if (!$classIds->contains($student->classe_id)) {
                abort(403);
            }
        } else {
            abort(403);
        }
    }
}
```

### 5.3. ClasseController Admin

**Fichier : `app/Http/Controllers/Admin/ClasseController.php` :**

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClasseController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $classes = Classe::with(['formateur', 'students'])->latest()->paginate(15);
        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $formateurs = User::where('role', 'formateur')->get();
        return view('admin.classes.create', compact('formateurs'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'formateur_id' => 'required|exists:users,id',
        ]);

        Classe::create($validated);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Classe créée avec succès.');
    }

    public function edit(Classe $classe)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $formateurs = User::where('role', 'formateur')->get();
        return view('admin.classes.edit', compact('classe', 'formateurs'));
    }

    public function update(Request $request, Classe $classe)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'formateur_id' => 'required|exists:users,id',
        ]);

        $classe->update($validated);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Classe mise à jour avec succès.');
    }

    public function destroy(Classe $classe)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $classe->delete();

        return redirect()->route('admin.classes.index')
            ->with('success', 'Classe supprimée avec succès.');
    }
}
```

### 5.4. FormateurController Admin

**Fichier : `app/Http/Controllers/Admin/FormateurController.php` :**

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class FormateurController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $formateurs = User::where('role', 'formateur')
            ->withCount('classes')
            ->latest()
            ->paginate(15);

        return view('admin.formateurs.index', compact('formateurs'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        return view('admin.formateurs.create');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'formateur',
        ]);

        return redirect()->route('admin.formateurs.index')
            ->with('success', 'Formateur créé avec succès.');
    }

    public function edit(User $formateur)
    {
        if (!Auth::user()->isAdmin() || $formateur->role !== 'formateur') {
            abort(403);
        }

        return view('admin.formateurs.edit', compact('formateur'));
    }

    public function update(Request $request, User $formateur)
    {
        if (!Auth::user()->isAdmin() || $formateur->role !== 'formateur') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $formateur->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $formateur->name = $validated['name'];
        $formateur->email = $validated['email'];

        if (!empty($validated['password'])) {
            $formateur->password = Hash::make($validated['password']);
        }

        $formateur->save();

        return redirect()->route('admin.formateurs.index')
            ->with('success', 'Formateur mis à jour avec succès.');
    }

    public function destroy(User $formateur)
    {
        if (!Auth::user()->isAdmin() || $formateur->role !== 'formateur') {
            abort(403);
        }

        $formateur->delete();

        return redirect()->route('admin.formateurs.index')
            ->with('success', 'Formateur supprimé avec succès.');
    }
}
```

### 5.5. StudentController Front

**Fichier : `app/Http/Controllers/Front/StudentController.php` :**

```php
<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Student;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('classe')->latest()->paginate(20);
        return view('front.students.index', compact('students'));
    }

    public function show(Student $student)
    {
        return view('front.students.show', compact('student'));
    }
}
```

---

## 6. Routes

**Fichier : `routes/web.php` :**

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\ClasseController;
use App\Http\Controllers\Admin\FormateurController;
use App\Http\Controllers\Front\StudentController as FrontStudentController;

// Page d'accueil redirige vers la liste publique des étudiants
Route::get('/', function () {
    return redirect()->route('front.students.index');
});

// ========================================
// Routes Front (publiques)
// ========================================
Route::prefix('students')->name('front.students.')->group(function () {
    Route::get('/', [FrontStudentController::class, 'index'])->name('index');
    Route::get('/{student}', [FrontStudentController::class, 'show'])->name('show');
});

// ========================================
// Routes Admin (protégées)
// ========================================
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Gestion des étudiants (Admin + Formateur)
    Route::resource('students', AdminStudentController::class);

    // Gestion des classes (Admin uniquement)
    Route::resource('classes', ClasseController::class);

    // Gestion des formateurs (Admin uniquement)
    Route::resource('formateurs', FormateurController::class);
});
```

---

## 7. Layouts

### 7.1. Layout Front

**Créer : `resources/views/layouts/front.blade.php` :**

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - @yield('title', 'Accueil')</title>
    <link href="https://fonts.bunny.net/css?family=nunito:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('front.students.index') }}">
                <i class="bi bi-mortarboard-fill"></i> {{ config('app.name') }}
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('front.students.index') }}">
                            <i class="bi bi-people"></i> Étudiants
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    <li class="nav-item">
                        <button class="btn btn-outline-light me-2" id="theme-toggle">
                            <i class="bi bi-moon-fill"></i>
                        </button>
                    </li>

                    @auth
                        @if(auth()->user()->isAdmin() || auth()->user()->isFormateur())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Admin
                                </a>
                            </li>
                        @endif

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.show') }}">Profil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Déconnexion</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Connexion</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Inscription</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="py-4 mt-5 bg-light">
        <div class="container text-center">
            <p class="mb-0 text-muted">© {{ date('Y') }} {{ config('app.name') }}</p>
        </div>
    </footer>
</body>
</html>
```

### 7.2. Layout Admin

**Créer : `resources/views/layouts/admin.blade.php` :**

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - {{ config('app.name') }} - @yield('title')</title>
    <link href="https://fonts.bunny.net/css?family=nunito:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block sidebar-admin">
                <div class="position-sticky">
                    <div class="sidebar-header">
                        <h5><i class="bi bi-shield-check"></i> Administration</h5>
                        <small>{{ auth()->user()->name }}</small><br>
                        <small class="badge bg-primary mt-1">{{ ucfirst(auth()->user()->role) }}</small>
                    </div>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                               href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>

                        @if(auth()->user()->isAdmin() || auth()->user()->isFormateur())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}" 
                                   href="{{ route('admin.students.index') }}">
                                    <i class="bi bi-people"></i> Étudiants
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.classes.*') ? 'active' : '' }}" 
                                   href="{{ route('admin.classes.index') }}">
                                    <i class="bi bi-diagram-3"></i> Classes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.formateurs.*') ? 'active' : '' }}" 
                                   href="{{ route('admin.formateurs.index') }}">
                                    <i class="bi bi-person-badge"></i> Formateurs
                                </a>
                            </li>
                        @endif

                        <li class="nav-item mt-3 border-top pt-3">
                            <a class="nav-link" href="{{ route('front.students.index') }}">
                                <i class="bi bi-arrow-left-circle"></i> Retour au site
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('profile.show') }}">
                                <i class="bi bi-person-circle"></i> Mon profil
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent">
                                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>

                    <div class="p-3 border-top mt-3">
                        <button class="btn btn-outline-light w-100" id="theme-toggle">
                            <i class="bi bi-moon-fill"></i> Thème
                        </button>
                    </div>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
```

### 7.3. Layout Auth

**Créer : `resources/views/layouts/auth.blade.php` :**

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name') }}</title>
    <link href="https://fonts.bunny.net/css?family=nunito:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="card shadow-lg">
                <div class="card-body p-4">
                    <div class="auth-logo">
                        <i class="bi bi-mortarboard-fill" style="font-size: 3rem;"></i>
                        <h2 class="mt-2">{{ config('app.name') }}</h2>
                    </div>

                    @yield('content')
                </div>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('front.students.index') }}" class="text-white">
                    <i class="bi bi-arrow-left"></i> Retour au site
                </a>
            </div>
        </div>
    </div>
</body>
</html>
```

---

## 8. Vues Front (publiques)

### 8.1. Liste des étudiants

**Créer : `resources/views/front/students/index.blade.php` :**

```blade
@extends('layouts.front')
@section('title', 'Liste des étudiants')

@section('content')
<h1 class="mb-4">
    <i class="bi bi-people-fill"></i> Liste des étudiants
</h1>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Classe</th>
                        <th>Date de naissance</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td>{{ $student->lastname }}</td>
                            <td>{{ $student->firstname }}</td>
                            <td>{{ $student->email }}</td>
                            <td>
                                @if($student->classe)
                                    <span class="badge bg-primary">{{ $student->classe->name }}</span>
                                @else
                                    <span class="badge bg-secondary">Aucune</span>
                                @endif
                            </td>
                            <td>{{ $student->birthdate->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('front.students.show', $student) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i> Voir
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                                <p class="mt-2 text-muted">Aucun étudiant inscrit</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $students->links() }}
        </div>
    </div>
</div>
@endsection
```

### 8.2. Détail d'un étudiant

**Créer : `resources/views/front/students/show.blade.php` :**

```blade
@extends('layouts.front')
@section('title', $student->full_name)

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="mb-3">
            <a href="{{ route('front.students.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour à la liste
            </a>
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">
                    <i class="bi bi-person-circle"></i> {{ $student->full_name }}
                </h2>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-4">Prénom :</dt>
                    <dd class="col-sm-8">{{ $student->firstname }}</dd>

                    <dt class="col-sm-4">Nom :</dt>
                    <dd class="col-sm-8">{{ $student->lastname }}</dd>

                    <dt class="col-sm-4">Email :</dt>
                    <dd class="col-sm-8">{{ $student->email }}</dd>

                    <dt class="col-sm-4">Date de naissance :</dt>
                    <dd class="col-sm-8">{{ $student->birthdate->format('d/m/Y') }}</dd>

                    <dt class="col-sm-4">Classe :</dt>
                    <dd class="col-sm-8">
                        @if($student->classe)
                            <span class="badge bg-primary">{{ $student->classe->name }}</span>
                        @else
                            <span class="badge bg-secondary">Aucune classe</span>
                        @endif
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
```

---

## 9. Vues Admin - Dashboard

**Créer : `resources/views/admin/dashboard.blade.php` :**

```blade
@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<h1 class="mb-4">
    <i class="bi bi-speedometer2"></i> Tableau de bord
</h1>

<div class="row g-4">
    @if(auth()->user()->isAdmin() || auth()->user()->isFormateur())
        <div class="col-md-4">
            <div class="card text-white bg-primary h-100">
                <div class="card-body">
                    <h5><i class="bi bi-people-fill"></i> Étudiants</h5>
                    <p class="mb-0">Gérer les étudiants de l'établissement</p>
                    <a href="{{ route('admin.students.index') }}" class="btn btn-light mt-3">
                        Accéder <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    @endif

    @if(auth()->user()->isAdmin())
        <div class="col-md-4">
            <div class="card text-white bg-success h-100">
                <div class="card-body">
                    <h5><i class="bi bi-diagram-3-fill"></i> Classes</h5>
                    <p class="mb-0">Gérer les classes et groupes</p>
                    <a href="{{ route('admin.classes.index') }}" class="btn btn-light mt-3">
                        Accéder <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-info h-100">
                <div class="card-body">
                    <h5><i class="bi bi-person-badge-fill"></i> Formateurs</h5>
                    <p class="mb-0">Gérer les comptes formateurs</p>
                    <a href="{{ route('admin.formateurs.index') }}" class="btn btn-light mt-3">
                        Accéder <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Bienvenue</h5>
            </div>
            <div class="card-body">
                <p>Bienvenue sur l'interface d'administration, <strong>{{ auth()->user()->name }}</strong>.</p>
                <p class="mb-0">
                    Rôle : <span class="badge bg-primary">{{ ucfirst(auth()->user()->role) }}</span>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
```

---

## 10. Vues Admin - Étudiants (CRUD complet)

### 10.1. Liste

**Créer le dossier :**

```bash
mkdir -p resources/views/admin/students
```

**Créer : `resources/views/admin/students/index.blade.php` :**

```blade
@extends('layouts.admin')
@section('title', 'Gestion des étudiants')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-people-fill"></i> Gestion des étudiants</h1>
    <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nouvel étudiant
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Classe</th>
                        <th>Date de naissance</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td>{{ $student->id }}</td>
                            <td>{{ $student->lastname }}</td>
                            <td>{{ $student->firstname }}</td>
                            <td>{{ $student->email }}</td>
                            <td>
                                @if($student->classe)
                                    <span class="badge bg-primary">{{ $student->classe->name }}</span>
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                            <td>{{ $student->birthdate->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.students.show', $student) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.students.destroy', $student) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Supprimer cet étudiant ?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                                <p class="mt-2">Aucun étudiant</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $students->links() }}
        </div>
    </div>
</div>
@endsection
```

### 10.2. Créer

**Créer : `resources/views/admin/students/create.blade.php` :**

```blade
@extends('layouts.admin')
@section('title', 'Créer un étudiant')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="mb-3">
            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <h2><i class="bi bi-person-plus"></i> Créer un étudiant</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.students.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="firstname" class="form-label">Prénom *</label>
                        <input type="text" class="form-control @error('firstname') is-invalid @enderror" 
                               id="firstname" name="firstname" value="{{ old('firstname') }}" required>
                        @error('firstname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="lastname" class="form-label">Nom *</label>
                        <input type="text" class="form-control @error('lastname') is-invalid @enderror" 
                               id="lastname" name="lastname" value="{{ old('lastname') }}" required>
                        @error('lastname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="birthdate" class="form-label">Date de naissance *</label>
                        <input type="date" class="form-control @error('birthdate') is-invalid @enderror" 
                               id="birthdate" name="birthdate" value="{{ old('birthdate') }}" required>
                        @error('birthdate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="classe_id" class="form-label">Classe</label>
                        <select class="form-select @error('classe_id') is-invalid @enderror" 
                                id="classe_id" name="classe_id">
                            <option value="">Aucune classe</option>
                            @foreach($classes as $classe)
                                <option value="{{ $classe->id }}" {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                                    {{ $classe->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('classe_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Créer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
```

### 10.3. Modifier

**Créer : `resources/views/admin/students/edit.blade.php` :**

```blade
@extends('layouts.admin')
@section('title', 'Modifier un étudiant')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="mb-3">
            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <h2><i class="bi bi-pencil-square"></i> Modifier l'étudiant</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.students.update', $student) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="firstname" class="form-label">Prénom *</label>
                        <input type="text" class="form-control @error('firstname') is-invalid @enderror" 
                               id="firstname" name="firstname" value="{{ old('firstname', $student->firstname) }}" required>
                        @error('firstname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="lastname" class="form-label">Nom *</label>
                        <input type="text" class="form-control @error('lastname') is-invalid @enderror" 
                               id="lastname" name="lastname" value="{{ old('lastname', $student->lastname) }}" required>
                        @error('lastname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $student->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="birthdate" class="form-label">Date de naissance *</label>
                        <input type="date" class="form-control @error('birthdate') is-invalid @enderror" 
                               id="birthdate" name="birthdate" 
                               value="{{ old('birthdate', $student->birthdate->format('Y-m-d')) }}" required>
                        @error('birthdate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="classe_id" class="form-label">Classe</label>
                        <select class="form-select @error('classe_id') is-invalid @enderror" 
                                id="classe_id" name="classe_id">
                            <option value="">Aucune classe</option>
                            @foreach($classes as $classe)
                                <option value="{{ $classe->id }}" 
                                    {{ old('classe_id', $student->classe_id) == $classe->id ? 'selected' : '' }}>
                                    {{ $classe->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('classe_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
```

### 10.4. Détail

**Créer : `resources/views/admin/students/show.blade.php` :**

```blade
@extends('layouts.admin')
@section('title', $student->full_name)

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="mb-3">
            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
            <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Modifier
            </a>
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0"><i class="bi bi-person-circle"></i> {{ $student->full_name }}</h2>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-4">ID :</dt>
                    <dd class="col-sm-8">{{ $student->id }}</dd>

                    <dt class="col-sm-4">Prénom :</dt>
                    <dd class="col-sm-8">{{ $student->firstname }}</dd>

                    <dt class="col-sm-4">Nom :</dt>
                    <dd class="col-sm-8">{{ $student->lastname }}</dd>

                    <dt class="col-sm-4">Email :</dt>
                    <dd class="col-sm-8">{{ $student->email }}</dd>

                    <dt class="col-sm-4">Date de naissance :</dt>
                    <dd class="col-sm-8">{{ $student->birthdate->format('d/m/Y') }}</dd>

                    <dt class="col-sm-4">Classe :</dt>
                    <dd class="col-sm-8">
                        @if($student->classe)
                            <span class="badge bg-primary">{{ $student->classe->name }}</span>
                        @else
                            <span class="badge bg-secondary">Aucune</span>
                        @endif
                    </dd>

                    <dt class="col-sm-4">Inscrit le :</dt>
                    <dd class="col-sm-8">{{ $student->created_at->format('d/m/Y à H:i') }}</dd>
                </dl>
            </div>
            <div class="card-footer">
                <form action="{{ route('admin.students.destroy', $student) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" 
                            onclick="return confirm('Supprimer cet étudiant ?')">
                        <i class="bi bi-trash"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
```

---

## 11. Vues Admin - Classes

**Créer le dossier :**

```bash
mkdir -p resources/views/admin/classes
```

### 11.1. Liste

**Créer : `resources/views/admin/classes/index.blade.php` :**

```blade
@extends('layouts.admin')
@section('title', 'Gestion des classes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-diagram-3-fill"></i> Gestion des classes</h1>
    <a href="{{ route('admin.classes.create') }}" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Nouvelle classe
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Formateur</th>
                        <th>Nb étudiants</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classes as $classe)
                        <tr>
                            <td>{{ $classe->id }}</td>
                            <td><strong>{{ $classe->name }}</strong></td>
                            <td>{{ Str::limit($classe->description ?? '-', 50) }}</td>
                            <td>
                                <span class="badge bg-info">
                                    <i class="bi bi-person-badge"></i> {{ $classe->formateur->name }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $classe->students->count() }} étudiants
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.classes.edit', $classe) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.classes.destroy', $classe) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Supprimer cette classe ?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                                <p class="mt-2">Aucune classe</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $classes->links() }}
        </div>
    </div>
</div>
@endsection
```

### 11.2. Créer

**Créer : `resources/views/admin/classes/create.blade.php` :**

```blade
@extends('layouts.admin')
@section('title', 'Créer une classe')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="mb-3">
            <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <h2><i class="bi bi-plus-circle"></i> Créer une classe</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.classes.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nom de la classe *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" 
                               placeholder="Ex: BTS SIO 2024-2025" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Description optionnelle">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="formateur_id" class="form-label">Formateur responsable *</label>
                        <select class="form-select @error('formateur_id') is-invalid @enderror" 
                                id="formateur_id" name="formateur_id" required>
                            <option value="">-- Sélectionner un formateur --</option>
                            @foreach($formateurs as $formateur)
                                <option value="{{ $formateur->id }}" 
                                    {{ old('formateur_id') == $formateur->id ? 'selected' : '' }}>
                                    {{ $formateur->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('formateur_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Créer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
```

### 11.3. Modifier

**Créer : `resources/views/admin/classes/edit.blade.php` :**

```blade
@extends('layouts.admin')
@section('title', 'Modifier une classe')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="mb-3">
            <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <h2><i class="bi bi-pencil-square"></i> Modifier la classe</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.classes.update', $classe) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nom de la classe *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $classe->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description', $classe->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="formateur_id" class="form-label">Formateur responsable *</label>
                        <select class="form-select @error('formateur_id') is-invalid @enderror" 
                                id="formateur_id" name="formateur_id" required>
                            <option value="">-- Sélectionner un formateur --</option>
                            @foreach($formateurs as $formateur)
                                <option value="{{ $formateur->id }}" 
                                    {{ old('formateur_id', $classe->formateur_id) == $formateur->id ? 'selected' : '' }}>
                                    {{ $formateur->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('formateur_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
```

---

## 12. Vues Admin - Formateurs

**Créer le dossier :**

```bash
mkdir -p resources/views/admin/formateurs
```

### 12.1. Liste

**Créer : `resources/views/admin/formateurs/index.blade.php` :**

```blade
@extends('layouts.admin')
@section('title', 'Gestion des formateurs')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-person-badge-fill"></i> Gestion des formateurs</h1>
    <a href="{{ route('admin.formateurs.create') }}" class="btn btn-info">
        <i class="bi bi-plus-circle"></i> Nouveau formateur
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Nb classes</th>
                        <th>Inscrit le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($formateurs as $formateur)
                        <tr>
                            <td>{{ $formateur->id }}</td>
                            <td>
                                <strong>{{ $formateur->name }}</strong><br>
                                <span class="badge bg-info">Formateur</span>
                            </td>
                            <td>{{ $formateur->email }}</td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $formateur->classes_count }} classe(s)
                                </span>
                            </td>
                            <td>{{ $formateur->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.formateurs.edit', $formateur) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.formateurs.destroy', $formateur) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Supprimer ce formateur ?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                                <p class="mt-2">Aucun formateur</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $formateurs->links() }}
        </div>
    </div>
</div>
@endsection
```

### 12.2. Créer

**Créer : `resources/views/admin/formateurs/create.blade.php` :**

```blade
@extends('layouts.admin')
@section('title', 'Créer un formateur')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="mb-3">
            <a href="{{ route('admin.formateurs.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <h2><i class="bi bi-person-plus"></i> Créer un formateur</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.formateurs.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nom complet *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe *</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        <small class="text-muted">Minimum 8 caractères</small>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmer le mot de passe *</label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.formateurs.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-info">
                            <i class="bi bi-check-circle"></i> Créer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
```

### 12.3. Modifier

**Créer : `resources/views/admin/formateurs/edit.blade.php` :**

```blade
@extends('layouts.admin')
@section('title', 'Modifier un formateur')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="mb-3">
            <a href="{{ route('admin.formateurs.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <h2><i class="bi bi-pencil-square"></i> Modifier le formateur</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.formateurs.update', $formateur) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nom complet *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $formateur->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $formateur->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3"><i class="bi bi-key"></i> Modifier le mot de passe (optionnel)</h5>

                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        Laissez les champs vides si vous ne voulez pas changer le mot de passe.
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Nouveau mot de passe</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmer</label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.formateurs.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-info">
                            <i class="bi bi-check-circle"></i> Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
```

---

## 13. Vues Auth

**Créer le dossier :**

```bash
mkdir -p resources/views/auth
```

### 13.1. Login

**Créer : `resources/views/auth/login.blade.php` :**

```blade
@extends('layouts.auth')
@section('title', 'Connexion')

@section('content')
<h4 class="text-center mb-4">Connexion</h4>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" 
               id="email" name="email" value="{{ old('email') }}" required autofocus>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Mot de passe</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" 
               id="password" name="password" required>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="remember" name="remember">
        <label class="form-check-label" for="remember">Se souvenir de moi</label>
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-box-arrow-in-right"></i> Se connecter
        </button>
    </div>

    <div class="text-center mt-3">
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">Mot de passe oublié ?</a>
        @endif
    </div>

    @if (Route::has('register'))
        <div class="text-center mt-2">
            <span class="text-muted">Pas de compte ?</span>
            <a href="{{ route('register') }}">S'inscrire</a>
        </div>
    @endif
</form>
@endsection
```

### 13.2. Register

**Créer : `resources/views/auth/register.blade.php` :**

```blade
@extends('layouts.auth')
@section('title', 'Inscription')

@section('content')
<h4 class="text-center mb-4">Inscription</h4>

<form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="mb-3">
        <label for="name" class="form-label">Nom complet</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" 
               id="name" name="name" value="{{ old('name') }}" required autofocus>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" 
               id="email" name="email" value="{{ old('email') }}" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Mot de passe</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" 
               id="password" name="password" required>
        <small class="text-muted">Minimum 8 caractères</small>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
        <input type="password" class="form-control" 
               id="password_confirmation" name="password_confirmation" required>
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-person-plus"></i> S'inscrire
        </button>
    </div>

    <div class="text-center mt-3">
        <span class="text-muted">Déjà inscrit ?</span>
        <a href="{{ route('login') }}">Se connecter</a>
    </div>
</form>
@endsection
```

---

## 14. Vue Profil

**Créer le dossier et le fichier :**

```bash
mkdir -p resources/views/profile
```

**Créer : `resources/views/profile/show.blade.php` :**

```blade
@extends(auth()->user()->isAdmin() || auth()->user()->isFormateur() ? 'layouts.admin' : 'layouts.front')
@section('title', 'Mon profil')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <h1 class="mb-4"><i class="bi bi-person-circle"></i> Mon profil</h1>

        <!-- Informations du profil -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Informations personnelles</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('user-profile-information.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nom</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Changer le mot de passe -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Modifier le mot de passe</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('user-password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Mot de passe actuel</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                               id="current_password" name="current_password" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Nouveau mot de passe</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-key"></i> Changer le mot de passe
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
```

---

## 15. Seeders

**Modifier : `database/seeders/DatabaseSeeder.php` :**

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User, Classe, Student};
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Créer l'administrateur
        User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Créer des formateurs
        $formateur1 = User::create([
            'name' => 'Jean Dupont',
            'email' => 'jean@test.com',
            'password' => Hash::make('password'),
            'role' => 'formateur',
        ]);

        $formateur2 = User::create([
            'name' => 'Marie Martin',
            'email' => 'marie@test.com',
            'password' => Hash::make('password'),
            'role' => 'formateur',
        ]);

        // Créer des classes
        $classe1 = Classe::create([
            'name' => 'BTS SIO SLAM 2024-2025',
            'description' => 'Promotion 2024-2025 - Spécialité SLAM',
            'formateur_id' => $formateur1->id,
        ]);

        $classe2 = Classe::create([
            'name' => 'BTS SIO SISR 2024-2025',
            'description' => 'Promotion 2024-2025 - Spécialité SISR',
            'formateur_id' => $formateur2->id,
        ]);

        $classe3 = Classe::create([
            'name' => 'Licence Pro DevOps 2024-2025',
            'description' => 'Licence professionnelle DevOps',
            'formateur_id' => $formateur1->id,
        ]);

        // Créer des étudiants
        $students = [
            ['firstname' => 'Pierre', 'lastname' => 'Durand', 'email' => 'pierre.durand@test.com', 'birthdate' => '2005-03-15', 'classe_id' => $classe1->id],
            ['firstname' => 'Sophie', 'lastname' => 'Bernard', 'email' => 'sophie.bernard@test.com', 'birthdate' => '2004-07-22', 'classe_id' => $classe1->id],
            ['firstname' => 'Lucas', 'lastname' => 'Petit', 'email' => 'lucas.petit@test.com', 'birthdate' => '2003-11-08', 'classe_id' => $classe2->id],
            ['firstname' => 'Emma', 'lastname' => 'Roux', 'email' => 'emma.roux@test.com', 'birthdate' => '2005-01-30', 'classe_id' => $classe2->id],
            ['firstname' => 'Hugo', 'lastname' => 'Moreau', 'email' => 'hugo.moreau@test.com', 'birthdate' => '2004-09-12', 'classe_id' => $classe3->id],
            ['firstname' => 'Léa', 'lastname' => 'Simon', 'email' => 'lea.simon@test.com', 'birthdate' => '2003-05-25', 'classe_id' => $classe3->id],
        ];

        foreach ($students as $student) {
            Student::create($student);
        }

        $this->command->info('✅ Base de données peuplée avec succès !');
        $this->command->info('📧 Admin : admin@test.com / password');
        $this->command->info('📧 Formateur 1 : jean@test.com / password');
        $this->command->info('📧 Formateur 2 : marie@test.com / password');
    }
}
```

**Exécuter les seeders :**

```bash
php artisan migrate:fresh --seed
```

---

## 16. Tests et vérifications

### 16.1. Vérifier les routes

```bash
php artisan route:list
```

### 16.2. Vérifier les assets compilés

```bash
npm run build
```

### 16.3. Vider tous les caches

```bash
php artisan optimize:clear
```

### 16.4. Lancer le serveur

```bash
# Avec Herd : accéder directement à http://gestion-etudiants.test

# Sans Herd
php artisan serve
# Puis ouvrir http://localhost:8000
```

### 16.5. Comptes de test

- **Administrateur** : `admin@test.com` / `password`
- **Formateur 1** : `jean@test.com` / `password`
- **Formateur 2** : `marie@test.com` / `password`

---

## 17. Résolution des problèmes courants

### Problème 1 : Erreur "Class not found"

```bash
composer dump-autoload
```

### Problème 2 : Assets non compilés

```bash
npm install
npm run build
```

### Problème 3 : Erreur de migration

```bash
# Réinitialiser complètement la BDD
php artisan migrate:fresh --seed
```

### Problème 4 : Erreur 500 après login

Vérifier que `JetstreamServiceProvider` est bien configuré avec les méthodes `isAdmin()` et `isFormateur()` dans le modèle `User`.

### Problème 5 : Vues Jetstream manquantes

```bash
php artisan vendor:publish --tag=jetstream-views
```

Puis adapter les vues auth personnalisées si nécessaire.

### Problème 6 : Pagination Bootstrap

Ajouter dans `App\Providers\AppServiceProvider` :

```php
use Illuminate\Pagination\Paginator;

public function boot(): void
{
    Paginator::useBootstrapFive();
}
```

---

## 18. Checklist finale

- [ ] Environnement mis à jour (PHP, Composer, Node, npm)
- [ ] Projet Laravel créé
- [ ] Jetstream installé avec Livewire
- [ ] Tailwind supprimé, Bootstrap installé
- [ ] Vite configuré
- [ ] SCSS compilé
- [ ] Migrations créées dans le bon ordre (classes AVANT students)
- [ ] Modèles créés avec relations
- [ ] Contrôleurs créés
- [ ] Routes configurées
- [ ] Layouts créés (front, admin, auth)
- [ ] Vues front créées
- [ ] Vues admin créées (students, classes, formateurs)
- [ ] Vues auth créées
- [ ] Seeders exécutés
- [ ] Tests de connexion réussis
- [ ] Toutes les fonctionnalités CRUD testées

---

## 19. Commandes de maintenance

```bash
# Régénérer les caches optimisés pour production
php artisan optimize

# Créer un lien symbolique pour le stockage
php artisan storage:link

# Lister toutes les routes
php artisan route:list

# Afficher la configuration
php artisan config:show

# Mettre en mode maintenance
php artisan down

# Sortir du mode maintenance
php artisan up
```
