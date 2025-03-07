# Services vs Controllers dans l'Architecture HMVC

## Table des matières

1. [Introduction](#introduction)
2. [Le principe HMVC et l'organisation modulaire](#le-principe-hmvc-et-lorganisation-modulaire)
3. [Contrôleurs vs Services : La séparation des responsabilités](#contrôleurs-vs-services--la-séparation-des-responsabilités)
4. [Le pattern "Thin Controller, Fat Service"](#le-pattern-thin-controller-fat-service)
5. [Implémentation des Services dans un module HMVC](#implémentation-des-services-dans-un-module-hmvc)
6. [Tutoriel pas à pas : Création d'un module avec Thin Controller et Services](#tutoriel-pas-à-pas--création-dun-module-avec-thin-controller-et-services)
7. [Bonnes pratiques et anti-patterns](#bonnes-pratiques-et-anti-patterns)
8. [FAQ : Questions fréquentes](#faq--questions-fréquentes)

## Introduction

Dans une architecture HMVC (Hierarchical Model-View-Controller), la séparation des responsabilités est cruciale pour maintenir un code évolutif, testable et maintenable. Ce guide explore la relation entre les contrôleurs et les services, en se concentrant sur l'approche "Thin Controller, Fat Service" (Contrôleur léger, Service riche) et comment l'implémenter efficacement au sein de modules HMVC.

Ce document vise à clarifier :
- Les responsabilités spécifiques des contrôleurs et des services
- Comment organiser les services au sein de modules HMVC
- Comment mettre en œuvre des contrôleurs légers qui délèguent la logique métier aux services
- Les bonnes pratiques pour une architecture modulaire propre

## Le principe HMVC et l'organisation modulaire

L'architecture HMVC (Hierarchical Model-View-Controller) étend le pattern MVC classique en introduisant une organisation hiérarchique qui favorise la modularité, la réutilisabilité et la maintenance du code.

### Principes fondamentaux de HMVC

- **Hiérarchie** : Les composants MVC sont organisés en modules hiérarchiques qui peuvent communiquer entre eux
- **Modularité** : Chaque module contient ses propres composants MVC et peut fonctionner de manière autonome
- **Réutilisabilité** : Les modules peuvent être réutilisés dans différentes parties de l'application
- **Isolation** : Les modules sont isolés les uns des autres, ce qui facilite le développement parallèle

### Structure d'un module HMVC traditionnel

```
Module/
├── Controllers/     # Gestion des requêtes/réponses
├── Models/          # Accès et manipulation des données
└── Views/           # Présentation et interface utilisateur
```

### Structure d'un module HMVC amélioré avec Services

```
Module/
├── Controllers/     # Interface avec HTTP (mince)
├── Services/        # Logique métier (riche)
├── Models/          # Accès aux données et logique du domaine
└── Views/           # Templates de présentation
```

L'ajout de la couche Service dans chaque module permet une meilleure séparation des responsabilités et renforce la cohésion du module.

## Contrôleurs vs Services : La séparation des responsabilités

### Responsabilités du Contrôleur

Dans une architecture HMVC avec services, le contrôleur devient délibérément "mince" (thin) et se concentre sur :

1. **Gestion des requêtes HTTP**
   - Récupération des données de la requête (paramètres, corps, en-têtes)
   - Vérification basique de la présence des données requises
   - Gestion des méthodes HTTP (GET, POST, PUT, DELETE)

2. **Coordination du flux d'exécution**
   - Appel au service approprié
   - Transmission des données entre la requête et le service

3. **Gestion de la présentation**
   - Sélection de la vue appropriée
   - Transmission des données du service à la vue
   - Gestion des redirections et des messages flash

4. **Construction de la réponse HTTP**
   - Définition des codes d'état HTTP
   - Définition des en-têtes de réponse
   - Formatage de la réponse (HTML, JSON, XML, etc.)

### Responsabilités du Service

Les services, eux, se concentrent exclusivement sur la logique métier :

1. **Implémentation des règles métier**
   - Validation approfondie selon les règles du domaine
   - Application des règles de gestion complexes
   - Maintien de l'intégrité du domaine métier

2. **Orchestration des opérations**
   - Coordination entre différentes entités et repositories
   - Gestion des transactions
   - Gestion des cas d'erreurs métier

3. **Manipulation des entités du domaine**
   - Création, modification, suppression d'entités
   - Application des changements d'état conformes aux règles métier
   - Gestion des relations entre entités

4. **Indépendance de l'infrastructure**
   - Abstraction de la persistance des données
   - Indépendance vis-à-vis du framework web
   - Réutilisabilité dans différents contextes

### Tableau comparatif

| Aspect | Contrôleur | Service |
|--------|------------|---------|
| **Responsabilité principale** | Interface entre HTTP et application | Logique métier |
| **Connaît** | Routes, paramètres, vues, réponses HTTP | Règles métier, entités, workflows |
| **Ignore** | Logique métier complexe | Détails HTTP, vues, présentation |
| **Dépendances typiques** | Request, Response, Services | Repositories, autres Services, Entities |
| **Testabilité** | Tests d'intégration | Tests unitaires faciles |

## Le pattern "Thin Controller, Fat Service"

Le pattern "Thin Controller, Fat Service" (Contrôleur léger, Service riche) est une approche architecturale qui vise à déplacer toute la logique métier des contrôleurs vers des services dédiés.

### Avantages

1. **Séparation claire des responsabilités**
   - Interface utilisateur vs logique métier
   - Facilite la compréhension et la maintenance du code

2. **Amélioration de la testabilité**
   - Services facilement testables avec des tests unitaires
   - Contrôleurs simples nécessitant moins de tests complexes

3. **Meilleure organisation du code au sein du module**
   - Services qui encapsulent la logique métier spécifique au module
   - Permet de diviser des fonctionnalités complexes en services distincts
   - Réduit la taille et la complexité des contrôleurs

4. **Code plus maintenable**
   - Changements dans l'UI sans affecter la logique métier
   - Changements dans la logique métier sans affecter l'UI

Il est important de noter que dans notre architecture HMVC stricte, les services d'un module restent strictement au sein de ce module et ne sont pas partagés avec d'autres modules. Cette isolation renforce la robustesse de l'application en évitant toute dépendance inter-modules.

### Exemples concrets

#### Avant : Controller Fat (à éviter)

```php
class UserController extends Controller
{
    public function register()
    {
        // Récupération des données
        $email = $this->request->post('email');
        $password = $this->request->post('password');
        $name = $this->request->post('name');
        
        // Validation (logique métier dans le contrôleur = mauvaise pratique)
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->viewWithLayout('register', 'layout', [
                'error' => 'Email invalide',
                'name' => $name
            ]);
        }
        
        if (strlen($password) < 8) {
            return $this->viewWithLayout('register', 'layout', [
                'error' => 'Le mot de passe doit contenir au moins 8 caractères',
                'email' => $email,
                'name' => $name
            ]);
        }
        
        // Vérification de l'unicité de l'email (accès direct au modèle = mauvaise pratique)
        $userModel = new UserModel($this->db);
        if ($userModel->firstWhere(['email' => $email])) {
            return $this->viewWithLayout('register', 'layout', [
                'error' => 'Cet email est déjà utilisé',
                'name' => $name
            ]);
        }
        
        // Hachage du mot de passe (logique métier)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Création de l'utilisateur (logique de persistance)
        $userData = [
            'email' => $email,
            'password' => $hashedPassword,
            'name' => $name,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $userId = $userModel->create($userData);
        
        if (!$userId) {
            return $this->viewWithLayout('register', 'layout', [
                'error' => 'Erreur lors de la création du compte',
                'email' => $email,
                'name' => $name
            ]);
        }
        
        // Envoi d'email de bienvenue (logique métier)
        $to = $email;
        $subject = 'Bienvenue sur notre site';
        $message = "Bonjour $name,\n\nVotre compte a été créé avec succès.";
        mail($to, $subject, $message);
        
        // Redirection
        $this->response->redirect('/login?registered=1');
    }
}
```

#### Après : Thin Controller avec Service (bonne pratique)

```php
// Controller mince qui délègue au service
class UserController extends Controller
{
    public function register()
    {
        // Récupération des données
        $userData = [
            'email' => $this->request->post('email'),
            'password' => $this->request->post('password'),
            'name' => $this->request->post('name')
        ];
        
        // Délégation au service
        $userService = $this->getService('User\\RegistrationService');
        $result = $userService->registerUser($userData);
        
        // Traitement du résultat
        if ($result['success']) {
            $this->response->redirect('/login?registered=1');
        } else {
            return $this->viewWithLayout('register', 'layout', [
                'errors' => $result['errors'],
                'email' => $userData['email'],
                'name' => $userData['name']
            ]);
        }
    }
}
```

```php
// Service riche qui contient toute la logique métier
namespace App\Modules\User\Services;

class RegistrationService
{
    private UserRepositoryInterface $userRepository;
    private PasswordHasherInterface $passwordHasher;
    private EmailServiceInterface $emailService;
    
    public function __construct(
        UserRepositoryInterface $userRepository,
        PasswordHasherInterface $passwordHasher,
        EmailServiceInterface $emailService
    ) {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->emailService = $emailService;
    }
    
    public function registerUser(array $userData): array
    {
        // Validation
        $errors = $this->validateUserData($userData);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        // Vérification de l'unicité de l'email
        if ($this->userRepository->findByEmail($userData['email'])) {
            return [
                'success' => false, 
                'errors' => ['Cet email est déjà utilisé']
            ];
        }
        
        // Hachage du mot de passe
        $userData['password'] = $this->passwordHasher->hash($userData['password']);
        $userData['created_at'] = date('Y-m-d H:i:s');
        
        // Création de l'utilisateur
        $userId = $this->userRepository->create($userData);
        
        if (!$userId) {
            return [
                'success' => false, 
                'errors' => ['Erreur lors de la création du compte']
            ];
        }
        
        // Envoi d'email de bienvenue
        $this->emailService->sendWelcomeEmail(
            $userData['email'],
            $userData['name']
        );
        
        return ['success' => true, 'userId' => $userId];
    }
    
    private function validateUserData(array $data): array
    {
        $errors = [];
        
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email invalide';
        }
        
        if (strlen($data['password']) < 8) {
            $errors[] = 'Le mot de passe doit contenir au moins 8 caractères';
        }
        
        // Autres validations...
        
        return $errors;
    }
}
```

## Implémentation des Services dans un module HMVC

### Organisation des services dans un module

Dans notre architecture HMVC, l'indépendance des modules est fondamentale. **Un module ne doit jamais dépendre du code d'un autre module** afin d'éviter les pannes en cascade. Cette règle s'applique également aux services.

Chaque module doit contenir ses propres services, qui ne sont accessibles que par les contrôleurs de ce même module:

```
app/
├── Modules/
│   ├── User/
│   │   ├── Controllers/   # Accède uniquement aux services du module User
│   │   ├── Services/      # Services spécifiques au module User
│   │   │   ├── ProfileService.php
│   │   │   └── RegistrationService.php
│   │   ├── Models/
│   │   └── Views/
│   └── Blog/
│       ├── Controllers/   # Accède uniquement aux services du module Blog
│       ├── Services/      # Services spécifiques au module Blog
│       │   ├── PostService.php
│       │   └── CommentService.php
│       ├── Models/
│       └── Views/
└── Services/             # Services partagés/globaux (indépendants des modules)
    ├── Email/
    │   └── EmailService.php
    ├── Payment/
    │   └── PaymentService.php
    └── Cache/
        └── CacheService.php
```

Les services dans le dossier `app/Services/` sont des services utilitaires généraux qui ne dépendent d'aucun module spécifique et peuvent être utilisés par tous les modules. Cependant, ils ne doivent jamais créer de dépendance entre modules.

### Types de services dans un module HMVC

1. **Services principaux** : Implémentent les fonctionnalités centrales du module
   ```php
   // User/Services/ProfileService.php - Gestion des profils utilisateurs
   // Blog/Services/PostService.php - Gestion des articles de blog
   ```

2. **Services auxiliaires** : Apportent des fonctionnalités de support au module
   ```php
   // User/Services/PasswordResetService.php - Réinitialisation de mot de passe
   // Blog/Services/SearchService.php - Recherche dans les articles
   ```

3. **Services de validation** : Focalisés sur la validation des données
   ```php
   // User/Services/UserValidationService.php - Validation des données utilisateur
   // Blog/Services/ContentValidationService.php - Validation du contenu
   ```

### Injection des services dans les contrôleurs

Les services peuvent être injectés dans les contrôleurs de plusieurs façons :

1. **Via le Container d'Injection de Dépendances (recommandé)**

```php
// Dans le contrôleur
public function someAction()
{
    $service = $this->getService('User\\ProfileService');
    // ou
    $service = $this->getService(ProfileService::class);
    
    // Utilisation du service
    $profile = $service->getUserProfile($userId);
}
```

2. **Via un registre de services global**

```php
// Dans le contrôleur
public function someAction()
{
    $service = ServiceRegistry::get('userProfile');
    
    // Utilisation du service
    $profile = $service->getUserProfile($userId);
}
```

3. **Injection directe dans le constructeur (pour les frameworks supportant cela)**

```php
class UserController extends Controller
{
    private ProfileService $profileService;
    
    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }
    
    public function profile($userId)
    {
        $profile = $this->profileService->getUserProfile($userId);
        // Suite du code...
    }
}
```

## Tutoriel pas à pas : Création d'un module avec Thin Controller et Services

Dans cette section, nous allons créer un module HMVC complet "Product" avec des contrôleurs légers et des services riches.

### Étape 1 : Créer la structure du module

Commencez par créer la structure de dossiers pour votre module Product :

```
app/Modules/Product/
├── Controllers/
├── Services/
├── Models/
├── Views/
└── router.php
```

### Étape 2 : Définir les routes

Définissez les routes dans votre fichier `router.php` :

```php
<?php
// app/Modules/Product/router.php

// Routes CRUD pour les produits
$router->get('/products', 'ProductController@index', $module);
$router->get('/products/create', 'ProductController@create', $module);
$router->post('/products', 'ProductController@store', $module);
$router->get('/products/{id}', 'ProductController@show', $module);
$router->get('/products/{id}/edit', 'ProductController@edit', $module);
$router->post('/products/{id}', 'ProductController@update', $module);
$router->get('/products/{id}/delete', 'ProductController@delete', $module);
```

### Étape 3 : Créer le modèle

Créez un modèle simple pour les produits :

```php
<?php
// app/Modules/Product/Models/ProductModel.php

namespace App\Modules\Product\Models;

use App\Core\Model;

class ProductModel extends Model
{
    protected string $table = 'products';
    protected string $primaryKey = 'id';
    
    // Méthodes spécifiques au modèle Product
    public function findActive()
    {
        return $this->where(['status' => 'active']);
    }
}
```

### Étape 4 : Créer les services

Maintenant, créons les services qui contiendront la logique métier :

```php
<?php
// app/Modules/Product/Services/ProductService.php

namespace App\Modules\Product\Services;

use App\Modules\Product\Models\ProductModel;
use App\Core\Database;

class ProductService
{
    private ProductModel $productModel;
    private Database $db;
    
    public function __construct(ProductModel $productModel, Database $db)
    {
        $this->productModel = $productModel;
        $this->db = $db;
    }
    
    /**
     * Récupère tous les produits actifs
     */
    public function getAllProducts(): array
    {
        return $this->productModel->findActive();
    }
    
    /**
     * Récupère un produit par son ID
     */
    public function getProductById(int $id): ?array
    {
        return $this->productModel->find($id);
    }
    
    /**
     * Crée un nouveau produit
     */
    public function createProduct(array $productData): array
    {
        // Validation
        $errors = $this->validateProductData($productData);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        // Préparation des données
        $productData['created_at'] = date('Y-m-d H:i:s');
        $productData['status'] = 'active';
        
        // Transaction
        try {
            $this->db->beginTransaction();
            
            // Création du produit
            $productId = $this->productModel->create($productData);
            
            if (!$productId) {
                $this->db->rollBack();
                return [
                    'success' => false,
                    'errors' => ['Erreur lors de la création du produit']
                ];
            }
            
            // Si catégories, gérer les relations
            if (!empty($productData['categories'])) {
                $this->associateCategories($productId, $productData['categories']);
            }
            
            $this->db->commit();
            
            return [
                'success' => true,
                'productId' => $productId
            ];
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            
            // Journaliser l'erreur
            error_log('Erreur création produit: ' . $e->getMessage());
            
            return [
                'success' => false,
                'errors' => ['Une erreur technique est survenue']
            ];
        }
    }
    
    /**
     * Met à jour un produit existant
     */
    public function updateProduct(int $id, array $productData): array
    {
        // Vérifier l'existence
        $product = $this->productModel->find($id);
        if (!$product) {
            return [
                'success' => false,
                'errors' => ['Produit non trouvé']
            ];
        }
        
        // Validation
        $errors = $this->validateProductData($productData);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        // Mise à jour timestamp
        $productData['updated_at'] = date('Y-m-d H:i:s');
        
        // Transaction pour la mise à jour
        try {
            $this->db->beginTransaction();
            
            $success = $this->productModel->update($id, $productData);
            
            if (!$success) {
                $this->db->rollBack();
                return [
                    'success' => false,
                    'errors' => ['Erreur lors de la mise à jour du produit']
                ];
            }
            
            // Mise à jour des catégories si nécessaire
            if (isset($productData['categories'])) {
                $this->updateCategories($id, $productData['categories']);
            }
            
            $this->db->commit();
            
            return [
                'success' => true,
                'productId' => $id
            ];
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log('Erreur mise à jour produit: ' . $e->getMessage());
            
            return [
                'success' => false,
                'errors' => ['Une erreur technique est survenue']
            ];
        }
    }
    
    /**
     * Supprime un produit (soft delete)
     */
    public function deleteProduct(int $id): array
    {
        // Vérifier l'existence
        $product = $this->productModel->find($id);
        if (!$product) {
            return [
                'success' => false,
                'errors' => ['Produit non trouvé']
            ];
        }
        
        // Utiliser soft delete (mise à jour du statut)
        try {
            $success = $this->productModel->update($id, [
                'status' => 'deleted',
                'deleted_at' => date('Y-m-d H:i:s')
            ]);
            
            if (!$success) {
                return [
                    'success' => false,
                    'errors' => ['Erreur lors de la suppression du produit']
                ];
            }
            
            return [
                'success' => true,
                'message' => 'Produit supprimé avec succès'
            ];
            
        } catch (\Exception $e) {
            error_log('Erreur suppression produit: ' . $e->getMessage());
            
            return [
                'success' => false,
                'errors' => ['Une erreur technique est survenue']
            ];
        }
    }
    
    /**
     * Valide les données d'un produit
     */
    private function validateProductData(array $data): array
    {
        $errors = [];
        
        // Validation du nom
        if (empty($data['name'])) {
            $errors[] = 'Le nom du produit est obligatoire';
        } elseif (strlen($data['name']) < 3) {
            $errors[] = 'Le nom du produit doit contenir au moins 3 caractères';
        }
        
        // Validation du prix
        if (!isset($data['price'])) {
            $errors[] = 'Le prix est obligatoire';
        } elseif (!is_numeric($data['price']) || $data['price'] < 0) {
            $errors[] = 'Le prix doit être un nombre positif';
        }
        
        // Validation du stock
        if (isset($data['stock']) && (!is_numeric($data['stock']) || $data['stock'] < 0)) {
            $errors[] = 'Le stock doit être un nombre positif';
        }
        
        return $errors;
    }
    
    /**
     * Associe des catégories à un produit
     */
    private function associateCategories(int $productId, array $categoryIds): void
    {
        foreach ($categoryIds as $categoryId) {
            $this->db->execute(
                "INSERT INTO product_categories (product_id, category_id) VALUES (:pid, :cid)",
                [':pid' => $productId, ':cid' => $categoryId]
            );
        }
    }
    
    /**
     * Met à jour les catégories d'un produit
     */
    private function updateCategories(int $productId, array $categoryIds): void
    {
        // Supprimer les associations existantes
        $this->db->execute(
            "DELETE FROM product_categories WHERE product_id = :pid",
            [':pid' => $productId]
        );
        
        // Créer les nouvelles associations
        $this->associateCategories($productId, $categoryIds);
    }
}
```

### Étape 5 : Créer les contrôleurs légers

Maintenant, créons des contrôleurs légers qui délèguent la logique aux services :

```php
<?php
// app/Modules/Product/Controllers/ProductController.php

namespace App\Modules\Product\Controllers;

use App\Core\Controller;
use App\Modules\Product\Services\ProductService;

class ProductController extends Controller
{
    /**
     * Affiche la liste des produits
     */
    public function index(): string
    {
        // Récupérer le service
        $productService = $this->getService(ProductService::class);
        
        // Déléguer la récupération des produits au service
        $products = $productService->getAllProducts();
        
        // Rendre la vue avec les données
        return $this->viewWithLayout('index', 'layout', [
            'title' => 'Liste des produits',
            'products' => $products
        ]);
    }
    
    /**
     * Affiche le formulaire de création
     */
    public function create(): string
    {
        return $this->viewWithLayout('create', 'layout', [
            'title' => 'Créer un produit'
        ]);
    }
    
    /**
     * Traite la soumission du formulaire de création
     */
    public function store(): void
    {
        // Récupérer les données du formulaire
        $productData = [
            'name' => $this->request->post('name'),
            'description' => $this->request->post('description'),
            'price' => $this->request->post('price'),
            'stock' => $this->request->post('stock'),
            'categories' => $this->request->post('categories', [])
        ];
        
        // Déléguer la création au service
        $productService = $this->getService(ProductService::class);
        $result = $productService->createProduct($productData);
        
        // Traiter le résultat
        if ($result['success']) {
            // Redirection en cas de succès
            $this->response->redirect('/products/' . $result['productId'] . '?created=1');
        } else {
            // Réafficher le formulaire avec les erreurs
            $content = $this->view('create', [
                'errors' => $result['errors'],
                'product' => $productData
            ]);
            
            $layout = $this->createLayout('Créer un produit');
            $layout->setContent($content);
            $layout->render();
        }
    }
    
    /**
     * Affiche les détails d'un produit
     */
    public function show(int $id): string
    {
        // Récupérer le produit via le service
        $productService = $this->getService(ProductService::class);
        $product = $productService->getProductById($id);
        
        // Vérifier si le produit existe
        if (!$product) {
            $this->response->setStatusCode(404);
            return $this->viewWithLayout('error', 'layout', [
                'title' => 'Produit non trouvé',
                'message' => 'Le produit demandé n\'existe pas.'
            ]);
        }
        
        // Afficher les détails du produit
        return $this->viewWithLayout('show', 'layout', [
            'title' => $product['name'],
            'product' => $product,
            'justCreated' => $this->request->get('created') == 1
        ]);
    }
    
    /**
     * Affiche le formulaire d'édition
     */
    public function edit(int $id): string
    {
        // Récupérer le produit via le service
        $productService = $this->getService(ProductService::class);
        $product = $productService->getProductById($id);
        
        // Vérifier si le produit existe
        if (!$product) {
            $this->response->setStatusCode(404);
            return $this->viewWithLayout('error', 'layout', [
                'title' => 'Produit non trouvé',
                'message' => 'Le produit demandé n\'existe pas.'
            ]);
        }
        
        // Afficher le formulaire d'édition
        return $this->viewWithLayout('edit', 'layout', [
            'title' => 'Modifier ' . $product['name'],
            'product' => $product
        ]);
    }
    
    /**
     * Traite la soumission du formulaire d'édition
     */
    public function update(int $id): void
    {
        // Récupérer les données du formulaire
        $productData = [
            'name' => $this->request->post('name'),
            'description' => $this->request->post('description'),
            'price' => $this->request->post('price'),
            'stock' => $this->request->post('stock'),
            'categories' => $this->request->post('categories', [])
        ];
        
        // Déléguer la mise à jour au service
        $productService = $this->getService(ProductService::class);
        $result = $productService->updateProduct($id, $productData);
        
        // Traiter le résultat
        if ($result['success']) {
            // Redirection en cas de succès
            $this->response->redirect('/products/' . $id . '?updated=1');
        } else {
            // Réafficher le formulaire avec les erreurs
            $content = $this->view('edit', [
                'errors' => $result['errors'],
                'product' => array_merge(['id' => $id], $productData)
            ]);
            
            $layout = $this->createLayout('Modifier le produit');
            $layout->setContent($content);
            $layout->render();
        }
    }
    
    /**
     * Traite la demande de suppression
     */
    public function delete(int $id): void
    {
        // Déléguer la suppression au service
        $productService = $this->getService(ProductService::class);
        $result = $productService->deleteProduct($id);
        
        // Redirection avec message approprié
        if ($result['success']) {
            $this->response->redirect('/products?deleted=1');
        } else {
            $this->response->redirect('/products/' . $id . '?error=delete');
        }
    }
}
```

### Étape 6 : Créer les vues

Maintenant, créons quelques vues de base pour notre module. Ici, nous ne montrerons que l'essentiel :

```php
<!-- app/Modules/Product/Views/index.php -->
<div class="container mt-4">
    <h1>Liste des produits</h1>
    
    <?php if ($this->request->get('deleted')): ?>
        <div class="alert alert-success">Le produit a été supprimé avec succès.</div>
    <?php endif; ?>
    
    <a href="/products/create" class="btn btn-primary mb-3">Nouveau produit</a>
    
    <div class="row">
        <?php if (empty($products)): ?>
            <div class="col-12">
                <p>Aucun produit disponible.</p>
            </div>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                            <p class="card-text">
                                Prix: <?= number_format($product['price'], 2) ?> €<br>
                                Stock: <?= $product['stock'] ?>
                            </p>
                            <a href="/products/<?= $product['id'] ?>" class="btn btn-info">Détails</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
```

```php
<!-- app/Modules/Product/Views/show.php -->
<div class="container mt-4">
    <?php if (isset($justCreated) && $justCreated): ?>
        <div class="alert alert-success">Le produit a été créé avec succès.</div>
    <?php endif; ?>
    
    <?php if ($this->request->get('updated')): ?>
        <div class="alert alert-success">Le produit a été mis à jour avec succès.</div>
    <?php endif; ?>
    
    <?php if ($this->request->get('error')): ?>
        <div class="alert alert-danger">Une erreur est survenue.</div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-header">
            <h1><?= htmlspecialchars($product['name']) ?></h1>
        </div>
        <div class="card-body">
            <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($product['description'])) ?></p>
            <p><strong>Prix:</strong> <?= number_format($product['price'], 2) ?> €</p>
            <p><strong>Stock:</strong> <?= $product['stock'] ?></p>
            <p><strong>Statut:</strong> <?= $product['status'] === 'active' ? 'Actif' : 'Inactif' ?></p>
        </div>
        <div class="card-footer">
            <a href="/products" class="btn btn-secondary">Retour à la liste</a>
            <a href="/products/<?= $product['id'] ?>/edit" class="btn btn-primary">Modifier</a>
            <a href="/products/<?= $product['id'] ?>/delete" class="btn btn-danger" 
               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit?')">Supprimer</a>
        </div>
    </div>
</div>
```

### Étape 7 : Enregistrer les services du module

Pour maintenir l'indépendance des modules dans notre architecture HMVC, chaque module devrait gérer ses propres services. Voici une proposition d'implémentation qui respecte ce principe:

Créez un fichier `services.php` dans le module Product:

```php
<?php
// app/Modules/Product/services.php

namespace App\Modules\Product;

use App\Core\ServiceContainer;
use App\Modules\Product\Services\ProductService;
use App\Modules\Product\Models\ProductModel;

/**
 * Enregistre les services du module Product
 * Cette fonction sera appelée automatiquement lors du chargement du module
 */
return function() {
    $app = \App\Core\Application::getInstance();
    $db = $app->getDatabase();
    
    // Enregistrer les services du module
    ServiceContainer::singleton(ProductService::class, function() use ($db) {
        $productModel = new ProductModel($db);
        return new ProductService($productModel, $db);
    });
    
    // Vous pouvez également enregistrer un alias spécifique au module
    ServiceContainer::register('Product.ProductService', function() {
        return ServiceContainer::resolve(ProductService::class);
    });
};
```

Cette approche garantit que:
1. Chaque module gère ses propres services
2. Aucun module ne dépend d'une configuration centrale
3. L'application charge automatiquement les services de chaque module
4. L'indépendance des modules est préservée

## Bonnes pratiques et anti-patterns

### Bonnes pratiques

1. **Responsabilité unique** : Chaque service doit avoir une responsabilité bien définie et cohérente.
   ```php
   // Bon : Service avec une responsabilité claire
   class OrderProcessingService { /* ... */ }
   class InvoicingService { /* ... */ }
   
   // Mauvais : Service fourre-tout
   class OrderService { /* gestion des commandes, facturation, expédition, etc. */ }
   ```

2. **Contrôleurs légers** : Les contrôleurs ne doivent contenir que du code lié à la requête HTTP.
   ```php
   // Bon : Contrôleur léger
   public function updateProfile()
   {
       $userData = $this->request->post();
       $profileService = $this->getService(ProfileService::class);
       $result = $profileService->updateProfile($userData);
       // Afficher résultat...
   }
   ```

3. **Injection de dépendances** : Injectez les dépendances plutôt que de les instancier directement.
   ```php
   // Bon : Injection via constructeur
   public function __construct(UserRepository $repo, EmailService $email) {
       $this->userRepository = $repo;
       $this->emailService = $email;
   }
   
   // Mauvais : Instanciation directe
   public function someMethod() {
       $repo = new UserRepository();
       $email = new EmailService();
   }
   ```

4. **Retours explicites** : Les services doivent retourner des structures claires indiquant le résultat.
   ```php
   // Bon : Structure de retour explicite
   return [
       'success' => true,
       'data' => $user,
       'message' => 'Profil mis à jour avec succès'
   ];
   ```

5. **Transactions** : Utilisez des transactions pour les opérations impliquant plusieurs modifications.
   ```php
   $this->db->beginTransaction();
   try {
       // Opérations multiples...
       $this->db->commit();
   } catch (\Exception $e) {
       $this->db->rollBack();
       // Gérer l'erreur...
   }
   ```

### Anti-patterns à éviter

1. **Service anémique** : Services qui ne font que déléguer aux repositories sans ajouter de valeur.
   ```php
   // Anti-pattern : Service anémique
   public function getUser($id) {
       return $this->userRepository->find($id); // Juste une délégation
   }
   ```

2. **Contrôleur obèse** : Contrôleurs qui contiennent de la logique métier.
   ```php
   // Anti-pattern : Logique métier dans le contrôleur
   public function register() {
       // Validation métier, hachage, vérifications...
       if (strlen($password) < 8) { /* ... */ }
       $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
       // Suite...
   }
   ```

3. **Dépendances cachées** : Services qui créent leurs dépendances en interne.
   ```php
   // Anti-pattern : Dépendance cachée
   class UserService {
       public function __construct() {
           $this->mailer = new Mailer(); // Dépendance cachée
       }
   }
   ```

4. **Accès direct à la base** : Contourner les repositories/modèles dans les services.
   ```php
   // Anti-pattern : Accès direct à la base
   public function getUserData($id) {
       $result = $this->db->query("SELECT * FROM users WHERE id = :id", [':id' => $id]);
       // Suite...
   }
   ```

5. **Services statiques** : Utiliser des méthodes statiques au lieu de l'injection de dépendances.
   ```php
   // Anti-pattern : Service statique
   class EmailService {
       public static function send($to, $subject, $body) {
           // Difficile à mocker, à tester...
       }
   }
   ```

## FAQ : Questions fréquentes

### 1. Quand créer un service vs étendre un modèle ?

**Réponse** : Les modèles devraient contenir la logique directement liée aux données d'une entité spécifique. Créez un service lorsque :
- L'opération implique plusieurs modèles au sein du même module
- L'opération contient une logique métier complexe
- Vous avez besoin d'orchestrer plusieurs étapes ou transactions
- La logique doit être réutilisée par plusieurs contrôleurs du même module

### 2. Comment organiser mes services quand ils deviennent nombreux ?

**Réponse** : Organisez-les par domaine fonctionnel et responsabilité :
- Services principaux : `UserService`, `ProductService`
- Services spécialisés : `UserRegistrationService`, `ProductImportService`
- Services par couche : `ValidationService`, `NotificationService`

### 3. Comment tester efficacement les services ?

**Réponse** : Les services sont conçus pour être facilement testables :
- Utilisez l'injection de dépendances pour faciliter le mocking
- Testez chaque méthode unitairement
- Utilisez des doubles de test (mocks) pour les dépendances externes
- Testez les différents chemins d'exécution (succès, échecs, exceptions)

### 4. Mon service devient trop gros, que faire ?

**Réponse** : C'est le signe qu'il faut le décomposer :
1. Identifiez des groupes de méthodes cohérentes
2. Extrayez ces groupes dans des services séparés
3. Injectez ces nouveaux services dans le service d'origine ou réorganisez les appels

### 5. Comment gérer les permissions/autorisations dans cette architecture ?

**Réponse** : Il y a plusieurs approches :
1. **Via middlewares** : Pour les autorisations basées sur les rôles généraux
2. **Via services dédiés** : `AuthorizationService` pour la logique d'autorisation complexe
3. **Dans les services métier** : Vérifications spécifiques au contexte métier

---

Ce guide a été conçu pour vous aider à implémenter efficacement l'approche "Thin Controller, Fat Service" dans votre architecture HMVC. En suivant ces pratiques, vous créerez une codebase plus maintenable, testable et évolutive. N'hésitez pas à adapter ces concepts à vos besoins spécifiques tout en respectant les principes fondamentaux de séparation des responsabilités.