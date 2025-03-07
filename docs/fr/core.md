# Guide du Dossier Core

## Introduction

Le dossier `Core` est le cœur de notre framework HMVC. Il contient les classes fondamentales qui constituent l'infrastructure de base de l'application. Ce dossier implémente les mécanismes essentiels sur lesquels tous les autres composants s'appuient.

## Structure du Dossier Core

```
Core/
├── Application.php         # Point central du framework
├── Controller.php          # Classe de base pour tous les contrôleurs
├── Database.php            # Gestion des connexions à la base de données
├── Layout.php              # Gestion des mises en page (templates)
├── Model.php               # Classe de base pour les modèles de données
├── Request.php             # Encapsulation des requêtes HTTP entrantes
├── Response.php            # Création et envoi des réponses HTTP
├── Router.php              # Gestion des routes et de leur résolution
├── ServiceContainer.php    # Container d'injection de dépendances
└── Auth/                   # Composants d'authentification
    └── PasswordHasher.php  # Utilitaire de hachage de mots de passe
```

## Composants Principaux

### Application (Application.php)

La classe `Application` est le **point d'entrée principal** du framework. Elle:
- Implémente le pattern **Front Controller**
- Initialise tous les composants du framework
- Charge les modules HMVC
- Coordonne le traitement des requêtes HTTP
- Exécute les middlewares et les contrôleurs

Exemple d'utilisation:
```php
// Dans public/index.php
$app = new App\Core\Application();
$app->setModulesPath(ROOT_PATH . '/app/Modules')
    ->setSharedViewsPath(ROOT_PATH . '/assets/templates')
    ->run(); // Démarre le traitement de la requête
```

### Request et Response (Request.php, Response.php)

Ces classes forment le **cycle requête-réponse HTTP**:

**Request** encapsule la requête entrante:
- Méthode HTTP (GET, POST, etc.)
- Paramètres URL, formulaires, JSON
- En-têtes HTTP, cookies
- Fichiers téléchargés

```php
// Dans un contrôleur
$email = $this->request->post('email');
$isAjax = $this->request->isAjax();
```

**Response** construit la réponse sortante:
- Corps de la réponse (HTML, JSON, etc.)
- Code de statut HTTP
- En-têtes HTTP
- Cookies
- Redirections

```php
// Dans un contrôleur
$this->response->setStatusCode(201)
               ->setHeader('Content-Type', 'application/json')
               ->setBody($jsonContent);
```

### Router (Router.php)

Le `Router` gère le **routage des requêtes HTTP** vers les contrôleurs appropriés:
- Définition des routes avec leur pattern d'URL
- Association des routes aux contrôleurs et actions
- Extraction des paramètres dynamiques des URLs
- Support des méthodes HTTP (GET, POST, PUT, DELETE)
- Groupes de routes et middlewares spécifiques aux routes

```php
// Dans un fichier router.php de module
$router->get('/profile/{id}', 'UserController@show', 'User');
$router->post('/login', 'AuthController@login', 'Auth');
```

### Controller (Controller.php)

La classe `Controller` est la **base de tous les contrôleurs** de l'application:
- Accès aux objets request, response et database
- Méthodes pour rendre des vues et utiliser des layouts
- Création de réponses JSON (APIs)
- Redirections HTTP
- Accès aux services via le container

```php
class UserController extends Controller
{
    public function profile($id)
    {
        $user = $userModel->find($id);
        return $this->viewWithLayout('profile', 'main', [
            'title' => 'Profil de ' . $user['name'],
            'user' => $user
        ]);
    }
}
```

### Model (Model.php)

`Model` est la **classe de base pour tous les modèles** de données:
- Opérations CRUD (Create, Read, Update, Delete) génériques
- Méthodes pour filtrer et récupérer des données
- Système de validation de données
- Abstraction de la persistance des données

```php
class UserModel extends Model
{
    protected string $table = 'users';
    
    public function findByEmail(string $email)
    {
        return $this->firstWhere(['email' => $email]);
    }
}
```

### Database (Database.php)

La classe `Database` gère les **connexions et opérations de base de données**:
- Connexion sécurisée à la base de données via PDO
- Exécution de requêtes avec paramètres préparés
- Transactions pour maintenir l'intégrité des données
- Gestion des erreurs de base de données

```php
$users = $db->query("SELECT * FROM users WHERE active = :active", 
                    [':active' => 1]);

$db->beginTransaction();
try {
    $db->execute("UPDATE accounts SET balance = balance - :amount WHERE id = :id",
                [':amount' => $amount, ':id' => $accountId]);
    $db->commit();
} catch (Exception $e) {
    $db->rollBack();
}
```

### ServiceContainer (ServiceContainer.php)

Le `ServiceContainer` implémente le **pattern d'injection de dépendances**:
- Enregistrement de services et leurs dépendances
- Résolution automatique des dépendances
- Gestion des singletons (instances partagées)
- Accès centralisé aux services de l'application

```php
// Enregistrer un service
ServiceContainer::singleton(UserService::class, function() {
    $userRepository = ServiceContainer::resolve(UserRepositoryInterface::class);
    return new UserService($userRepository);
});

// Utiliser un service
$userService = ServiceContainer::resolve(UserService::class);
```

### Layout (Layout.php)

La classe `Layout` gère la **structure des pages HTML**:
- Intégration du contenu spécifique dans un template réutilisable
- Transmission de variables aux templates
- Gestion du titre de la page et des métadonnées
- Organisation cohérente de l'interface utilisateur

```php
$layout = new Layout($app);
$layout->setTitle('Page d\'accueil')
       ->setContent($content)
       ->setVariable('user', $currentUser);
return $layout->render(); // Génère le HTML complet
```

## Flux d'Exécution d'une Requête

1. **Entrée**: La requête HTTP arrive au point d'entrée `public/index.php`
2. **Initialisation**: L'`Application` est instanciée et configurée
3. **Routage**: Le `Router` analyse l'URL et détermine le contrôleur et l'action à exécuter
4. **Middlewares**: Les middlewares globaux puis spécifiques à la route sont exécutés
5. **Contrôleur**: L'action appropriée du contrôleur est appelée
6. **Traitement**: Le contrôleur interagit avec les modèles et services pour traiter la demande
7. **Vue**: Le contrôleur rend une vue ou génère une réponse appropriée
8. **Réponse**: L'objet `Response` envoie le résultat au client

## Bonnes Pratiques

1. **Ne modifiez pas directement les classes du dossier Core**:
   - Héritez des classes de base pour les personnaliser
   - Utilisez les hooks et points d'extension fournis

2. **Respectez les responsabilités de chaque composant**:
   - Les contrôleurs gèrent le flux d'exécution et les vues
   - Les modèles gèrent les données et la logique métier
   - Les services coordonnent des opérations complexes

3. **Tirez parti de l'injection de dépendances**:
   - Utilisez le ServiceContainer pour les dépendances
   - Évitez d'instancier directement des objets avec `new`
   - Testez vos composants en isolation

4. **Suivez le pattern HMVC**:
   - Organisez votre code en modules cohérents
   - Respectez la séparation des préoccupations
   - Réutilisez des composants entre modules

---

Le dossier `Core` établit les fondations solides sur lesquelles le reste de l'application est construit. Une bonne compréhension de ses composants est essentielle pour développer efficacement avec ce framework.