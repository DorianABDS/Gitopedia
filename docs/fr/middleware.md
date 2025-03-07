# Guide du Dossier Middleware

## Introduction

Le dossier `Middleware` contient des composants qui interceptent et traitent les requêtes HTTP avant qu'elles n'atteignent les contrôleurs. Les middlewares agissent comme des "filtres" ou des "couches intermédiaires" qui peuvent examiner, modifier, ou bloquer une requête.

## Qu'est-ce qu'un Middleware?

Un middleware est un composant logiciel qui s'insère dans le cycle de traitement d'une requête HTTP, entre la réception de la requête et l'exécution du contrôleur. 

Les middlewares permettent de:
- Examiner la requête entrante
- Effectuer des vérifications (authentification, droits d'accès, etc.)
- Modifier la requête ou la réponse
- Rejeter la requête si nécessaire
- Exécuter du code avant et/ou après le traitement principal

## Structure du Dossier Middleware

```
Middleware/
├── MiddlewareInterface.php    # Interface commune pour tous les middlewares
├── AuthMiddleware.php         # Middleware d'authentification
├── CsrfMiddleware.php         # Protection contre les attaques CSRF
└── [AutresMiddlewares].php    # Autres middlewares spécifiques
```

## Le Pattern Chain of Responsibility

Les middlewares implémentent le pattern de conception "Chaîne de responsabilité" (Chain of Responsibility). Dans ce pattern:
- Chaque middleware peut traiter une requête ou la passer au suivant
- Les middlewares sont exécutés dans un ordre défini
- Le traitement peut être interrompu à tout moment

## L'Interface MiddlewareInterface

Tous les middlewares implémentent une interface commune qui définit un contrat uniforme:

```php
namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;

interface MiddlewareInterface
{
    /**
     * Traite la requête HTTP entrante
     * 
     * @param Request $request La requête à traiter
     * @param Response $response La réponse en cours de construction
     * @return bool True pour continuer le traitement, false pour l'arrêter
     */
    public function process(Request $request, Response $response): bool;
}
```

Le paramètre de retour `bool` est crucial:
- `true` signifie que le traitement de la requête doit continuer
- `false` indique que le traitement doit s'arrêter (par exemple, si l'authentification échoue)

## Exemple de Middleware: AuthMiddleware

Le middleware d'authentification (`AuthMiddleware.php`) est un exemple parfait de middleware:

```php
namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;

class AuthMiddleware implements MiddlewareInterface
{
    public function process(Request $request, Response $response): bool
    {
        // Démarrer la session si nécessaire
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Vérifier si l'utilisateur est authentifié
        if (!isset($_SESSION['user_id'])) {
            // Utilisateur non authentifié - rediriger vers la page de connexion
            $currentUrl = $request->getUri();
            $response->redirect('/login?redirect=' . urlencode($currentUrl));
            
            // Arrêter le traitement de la requête
            return false;
        }
        
        // Utilisateur authentifié - continuer le traitement
        return true;
    }
}
```

Ce middleware:
1. Vérifie si l'utilisateur est authentifié (via la session)
2. Si oui, il autorise la continuation du traitement
3. Sinon, il redirige vers la page de connexion et bloque la suite du traitement

## Types Courants de Middlewares

### Sécurité
- **AuthMiddleware**: Vérifie l'authentification des utilisateurs
- **CsrfMiddleware**: Protège contre les attaques CSRF
- **RateLimitMiddleware**: Limite le nombre de requêtes par période

### Prétraitement
- **BodyParserMiddleware**: Parse les données JSON ou XML du corps de la requête
- **ValidationMiddleware**: Valide les données entrantes 
- **SanitizationMiddleware**: Nettoie et désinfecte les données

### Journalisation et Monitoring
- **LoggingMiddleware**: Enregistre des informations sur les requêtes
- **PerformanceMiddleware**: Mesure le temps de traitement des requêtes

### Réponse
- **CacheMiddleware**: Gère la mise en cache des réponses
- **CompressionMiddleware**: Compresse les réponses pour réduire la taille
- **CorsMiddleware**: Gère les en-têtes Cross-Origin Resource Sharing

## Utilisation des Middlewares

Les middlewares peuvent être appliqués à différents niveaux:

### Middlewares Globaux

Appliqués à toutes les requêtes de l'application:

```php
// Dans Application.php ou bootstrap
$app = new Application();
$app->addMiddleware(new SessionMiddleware())
    ->addMiddleware(new AuthMiddleware());
```

### Middlewares de Route

Appliqués uniquement à des routes spécifiques:

```php
// Dans un fichier router.php
$router->get('/admin/dashboard', 'AdminController@dashboard', 'Admin', ['AuthMiddleware', 'AdminRoleMiddleware']);
```

## Création d'un Nouveau Middleware

Pour créer un nouveau middleware:

1. Créez une classe qui implémente `MiddlewareInterface`
2. Implémentez la méthode `process()`
3. Retournez `true` pour continuer le traitement ou `false` pour l'arrêter

Exemple de middleware de journalisation:

```php
namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;

class LoggingMiddleware implements MiddlewareInterface
{
    public function process(Request $request, Response $response): bool
    {
        // Récupérer des informations sur la requête
        $method = $request->getMethod();
        $uri = $request->getUri();
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        
        // Enregistrer ces informations dans un fichier de log
        $logMessage = date('Y-m-d H:i:s') . " - $ip - $method $uri\n";
        file_put_contents(ROOT_PATH . '/logs/access.log', $logMessage, FILE_APPEND);
        
        // Toujours continuer le traitement
        return true;
    }
}
```

## Bonnes Pratiques

1. **Gardez les middlewares courts et ciblés**: Chaque middleware doit avoir une responsabilité unique

2. **Ordre des middlewares**: L'ordre d'exécution est important - les middlewares de sécurité doivent généralement être exécutés en premier

3. **Évitez la logique métier**: Les middlewares ne doivent pas contenir de logique métier; ils se concentrent sur l'infrastructure et les aspects techniques

4. **Utilisez des middlewares conditionnels**: Si un middleware ne doit s'appliquer que dans certaines conditions, vérifiez ces conditions au début de la méthode `process()`

5. **Documentation claire**: Documentez soigneusement ce que fait chaque middleware, car ils peuvent avoir des effets subtils sur le comportement de l'application

## Avantages des Middlewares

- **Séparation des préoccupations**: Isolez les aspects transversaux du traitement des requêtes
- **Réutilisabilité**: Appliquez les mêmes vérifications à plusieurs routes
- **Modularité**: Ajoutez, supprimez ou réorganisez les middlewares sans modifier le code existant
- **Testabilité**: Testez chaque middleware indépendamment du reste de l'application

---

Les middlewares sont un outil puissant pour gérer les aspects transversaux de votre application web, comme la sécurité, la journalisation ou le traitement des requêtes. En comprenant leur fonctionnement et en suivant les bonnes pratiques, vous pouvez créer une architecture propre et maintenable.