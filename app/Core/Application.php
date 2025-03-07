<?php
/**
 * Classe Application - Le cœur du framework HMVC
 * 
 * Cette classe représente le point central de l'application et implémente 
 * le pattern Front Controller. Elle est responsable de :
 * - Charger et gérer la structure modulaire de l'application (HMVC)
 * - Intercepter et traiter toutes les requêtes HTTP entrantes
 * - Appliquer la chaîne de middlewares pour le filtrage des requêtes
 * - Déterminer et exécuter les contrôleurs/actions appropriés selon la route
 * - Construire et envoyer les réponses HTTP au client
 * - Centraliser la gestion des erreurs de l'application
 * 
 * Cette classe est l'élément fondamental qui lie tous les composants du framework.
 * Elle suit le pattern Singleton pour garantir qu'une seule instance existe
 * pendant toute la durée de vie de la requête.
 */

namespace App\Core;

use App\Middleware\MiddlewareInterface;
use App\Core\ServiceContainer;

class Application
{
    /**
     * Instance unique de l'application (Pattern Singleton)
     * 
     * Le pattern Singleton garantit qu'une seule instance de cette classe
     * existe à tout moment dans l'application, offrant plusieurs avantages :
     * - Accès global à l'instance depuis n'importe quel point du code
     * - Centralisation de la configuration de l'application
     * - Économie de ressources (une seule connexion à la base de données, etc.)
     * - Gestion cohérente des dépendances
     * 
     * @var Application|null
     */
    private static ?Application $instance = null;
    
    /**
     * Chemin vers le dossier des modules
     * 
     * Ce chemin est utilisé pour détecter automatiquement et charger
     * tous les modules disponibles dans l'application.
     * 
     * Dans l'architecture HMVC (Hierarchical Model-View-Controller),
     * chaque module est une mini-application MVC indépendante qui peut
     * être développée, testée et maintenue séparément.
     * 
     * @var string
     */
    private string $modulesPath;
    
    /**
     * Chemin vers le dossier des vues partagées
     * 
     * Ce dossier contient les templates et layouts réutilisables
     * par différents modules de l'application, notamment :
     * - Le layout principal (header, footer, navigation)
     * - Les templates partiels réutilisables (formulaires, cartes, modales)
     * - Les éléments d'interface communs (pagination, messages d'alerte)
     * 
     * Ces vues partagées favorisent la cohérence de l'interface utilisateur
     * et évitent la duplication de code à travers les modules.
     * 
     * @var string
     */
    private string $sharedViewsPath;
    
    /**
     * Instance du routeur
     * 
     * Le routeur est responsable de faire correspondre les URLs entrantes
     * avec les contrôleurs et actions appropriés. Il gère :
     * - La définition des routes (URL patterns, méthodes HTTP)
     * - L'extraction des paramètres des URLs
     * - La résolution des routes vers les contrôleurs et actions
     * - Les middlewares spécifiques à certaines routes
     * 
     * @var Router
     */
    private Router $router;
    
    /**
     * Instance de la requête
     * 
     * Encapsule toutes les informations sur la requête HTTP actuelle :
     * - Méthode HTTP (GET, POST, PUT, DELETE, etc.)
     * - URL et URI
     * - Paramètres de requête ($_GET, $_POST)
     * - En-têtes HTTP
     * - Cookies
     * - Fichiers téléversés
     * - Corps de la requête (pour API REST par exemple)
     * 
     * Cette abstraction facilite le test unitaire et évite d'accéder
     * directement aux variables superglobales de PHP.
     * 
     * @var Request
     */
    private Request $request;
    
    /**
     * Instance de la réponse
     * 
     * Permet de construire une réponse HTTP à renvoyer au client avec :
     * - Le corps de la réponse (HTML, JSON, XML, etc.)
     * - Le code de statut HTTP (200, 404, 500, etc.)
     * - Les en-têtes HTTP (Content-Type, Cache-Control, etc.)
     * - Les cookies à définir ou supprimer
     * 
     * Cette encapsulation offre une interface claire pour manipuler
     * tous les aspects de la réponse avant son envoi final.
     * 
     * @var Response
     */
    private Response $response;
    
    /**
     * Instance de la connexion à la base de données
     * 
     * Gère la connexion à la base de données et fournit des méthodes
     * pour exécuter des requêtes SQL et récupérer des résultats.
     * 
     * La valeur peut être null si l'application n'utilise pas de base
     * de données ou si la connexion n'est pas encore configurée.
     * 
     * @var Database|null
     */
    private ?Database $db = null;
    
    /**
     * Liste des modules chargés
     * 
     * Stocke les informations sur tous les modules détectés et chargés,
     * avec leur nom et leur chemin sur le disque.
     * 
     * Format typique :
     * [
     *    'User' => [
     *        'name' => 'User',
     *        'path' => '/path/to/app/Modules/User'
     *    ],
     *    'Blog' => [
     *        'name' => 'Blog',
     *        'path' => '/path/to/app/Modules/Blog'
     *    ]
     * ]
     * 
     * @var array
     */
    private array $modules = [];
    
    /**
     * Liste des middlewares globaux
     * 
     * Les middlewares permettent de filtrer et traiter les requêtes avant
     * qu'elles n'atteignent les contrôleurs. Ils sont exécutés dans l'ordre
     * de leur ajout et peuvent :
     * - Authentifier l'utilisateur
     * - Vérifier les permissions d'accès
     * - Valider les données entrantes
     * - Gérer les sessions
     * - Ajouter des en-têtes de sécurité
     * - Logger les requêtes
     * - Effectuer des redirections conditionnelles
     * 
     * @var array<MiddlewareInterface>
     */
    private array $middlewares = [];
    
    /**
     * Constructeur de la classe Application
     * 
     * Initialise les composants de base de l'application :
     * - Stocke l'instance dans la propriété statique $instance (Singleton)
     * - Crée une instance du routeur
     * - Crée une instance de l'objet requête pour traiter la requête actuelle
     * - Crée une instance de l'objet réponse pour construire la réponse
     * - Initialise la connexion à la base de données si les constantes sont définies
     * 
     * Note: Ce constructeur est appelé automatiquement lors du premier appel
     * à Application::getInstance().
     */
    public function __construct()
    {
        self::$instance = $this;
        $this->router = new Router();
        $this->request = new Request();
        $this->response = new Response();
        
        // Initialiser la connexion à la base de données si les constantes sont définies
        // Ces constantes sont généralement définies dans le fichier config/database.php
        // qui est chargé avant l'instanciation de l'application
        if (defined('DB_HOST')) {
            $this->db = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS);
        }
    }
    
    /**
     * Récupère l'instance unique de l'application (Pattern Singleton)
     * 
     * Cette méthode statique est le point d'entrée principal pour accéder
     * à l'instance de l'application depuis n'importe quel point du code.
     * Si l'instance n'existe pas encore, elle est créée automatiquement.
     * 
     * Le pattern Singleton évite de devoir passer explicitement l'instance
     * de l'application à travers toutes les couches du code, simplifiant
     * ainsi l'architecture.
     * 
     * Exemple d'utilisation :
     * 
     * // Accéder à l'application depuis n'importe quelle classe
     * $app = Application::getInstance();
     * 
     * // Utiliser ses méthodes et propriétés
     * $router = $app->getRouter();
     * $db = $app->getDatabase();
     * 
     * @return self L'instance unique de l'application
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Définit le chemin vers le dossier des modules
     * 
     * Ce chemin est utilisé pour rechercher et charger automatiquement
     * tous les modules disponibles dans l'application selon le principe HMVC.
     * 
     * Cette méthode utilise le pattern de conception "fluent interface"
     * (interface fluide) qui permet d'enchaîner les appels de méthode
     * en retournant l'instance de l'objet courant ($this).
     * 
     * Exemple d'utilisation :
     * 
     * // Configuration de base de l'application
     * $app = Application::getInstance();
     * $app->setModulesPath(ROOT_PATH . '/app/Modules')
     *     ->setSharedViewsPath(ROOT_PATH . '/assets/templates')
     *     ->addMiddleware(new SessionMiddleware())
     *     ->run();
     * 
     * @param string $path Chemin absolu vers le dossier des modules
     * @return self Retourne l'instance de l'application pour permettre le chaînage
     */
    public function setModulesPath(string $path): self
    {
        $this->modulesPath = $path;
        return $this;
    }
    
    /**
     * Définit le chemin vers le dossier des vues partagées
     * 
     * Ces vues partagées contiennent généralement les layouts et templates
     * communs à plusieurs modules (entête, pied de page, menu de navigation, etc.).
     * 
     * L'utilisation de vues partagées permet de :
     * - Maintenir une interface utilisateur cohérente
     * - Centraliser les modifications de design
     * - Éviter la duplication de code de présentation
     * - Faciliter la maintenance
     * 
     * Exemple d'utilisation :
     * 
     * $app->setSharedViewsPath(ROOT_PATH . '/assets/templates');
     * 
     * @param string $path Chemin absolu vers le dossier des vues partagées
     * @return self Retourne l'instance de l'application pour permettre le chaînage
     */
    public function setSharedViewsPath(string $path): self
    {
        $this->sharedViewsPath = $path;
        return $this;
    }
    
    /**
     * Ajoute un middleware global à l'application
     * 
     * Les middlewares globaux sont exécutés pour toutes les requêtes,
     * avant que les contrôleurs ne soient appelés. Ils permettent de
     * filtrer les requêtes, vérifier les autorisations, etc.
     * 
     * L'ordre d'ajout des middlewares est important car ils sont exécutés
     * dans cet ordre. Par exemple, un middleware de session devrait être
     * ajouté avant un middleware d'authentification qui en dépend.
     * 
     * Exemple d'utilisation :
     * 
     * // Ajouter plusieurs middlewares
     * $app->addMiddleware(new SessionMiddleware())
     *     ->addMiddleware(new AuthMiddleware())
     *     ->addMiddleware(new CsrfProtectionMiddleware());
     * 
     * @param MiddlewareInterface $middleware Instance d'un middleware
     * @return self Retourne l'instance de l'application pour permettre le chaînage
     */
    public function addMiddleware(MiddlewareInterface $middleware): self
    {
        $this->middlewares[] = $middleware;
        return $this;
    }
    
    /**
     * Détecte et charge automatiquement tous les modules
     * 
     * Cette méthode interne est appelée au début de l'exécution de l'application
     * et réalise les opérations suivantes :
     * 1. Vérifie que le chemin des modules existe
     * 2. Parcourt tous les sous-dossiers du dossier des modules
     * 3. Enregistre chaque dossier comme un module distinct
     * 
     * L'architecture HMVC (Hierarchical Model-View-Controller) permet de :
     * - Structurer le code en modules autonomes
     * - Améliorer la maintenabilité et la testabilité
     * - Réutiliser des composants à travers l'application
     * - Favoriser le développement parallèle par plusieurs équipes
     * 
     * @throws \Exception Si le chemin des modules n'est pas défini ou n'existe pas
     */
    private function loadModules(): void
    {
        // Vérifier que le chemin des modules est défini et existe
        if (!isset($this->modulesPath) || !is_dir($this->modulesPath)) {
            die("Le chemin des modules n'est pas défini ou n'existe pas");
        }
        
        // Récupérer tous les dossiers dans le dossier des modules
        // GLOB_ONLYDIR filtre pour ne retourner que les dossiers
        $directories = glob($this->modulesPath . '/*', GLOB_ONLYDIR);
        
        // Parcourir chaque dossier et l'enregistrer comme un module
        foreach ($directories as $moduleDir) {
            $moduleName = basename($moduleDir);
            $this->registerModule($moduleName, $moduleDir);
        }
    }
    
    /**
     * Charge les entités de domaine et les services
     * 
     * Cette méthode est appelée pendant l'initialisation de l'application
     * pour s'assurer que les entités de domaine et les services sont disponibles.
     * 
     * Avec l'autoloader PSR-4, cette méthode devient plus simple car elle n'a pas
     * besoin de charger explicitement chaque fichier - l'autoloader s'en charge
     * automatiquement lorsqu'une classe est référencée pour la première fois.
     * 
     * Cette méthode a principalement un rôle de vérification de l'existence
     * des dossiers de domaine et services, pour éviter des erreurs pendant
     * le fonctionnement de l'application.
     * 
     * @return self Retourne l'instance de l'application pour permettre le chaînage
     */
    public function loadDomain(): self
    {
        // Vérifier si les dossiers existent
        $domainPath = ROOT_PATH . '/app/Domain';
        $servicesPath = ROOT_PATH . '/app/Services';
        
        // Vérifier l'existence du dossier de domaine
        if (!is_dir($domainPath)) {
            // Créer le dossier si nécessaire ou journaliser un avertissement
            if (defined('DEBUG_MODE') && DEBUG_MODE) {
                error_log("Le dossier Domain n'existe pas: $domainPath");
            }
        }
        
        // Vérifier l'existence du dossier de services
        if (!is_dir($servicesPath)) {
            // Créer le dossier si nécessaire ou journaliser un avertissement
            if (defined('DEBUG_MODE') && DEBUG_MODE) {
                error_log("Le dossier Services n'existe pas: $servicesPath");
            }
        }
        
        return $this;
    }

    /**
     * Charge le dossier Services et vérifie son existence
     * 
     * Cette méthode est appelée pendant l'initialisation de l'application
     * pour s'assurer que les services sont disponibles.
     * 
     * Avec l'autoloader PSR-4, cette méthode a principalement un rôle 
     * de vérification de l'existence du dossier Services.
     * 
     * @return self Retourne l'instance de l'application pour permettre le chaînage
     */
    public function loadServices(): self
    {
        // Vérifier si le dossier Services existe
        $servicesPath = ROOT_PATH . '/app/Services';
        
        // Vérifier l'existence du dossier de services
        if (!is_dir($servicesPath)) {
            // Journaliser un avertissement en mode debug
            if (defined('DEBUG_MODE') && DEBUG_MODE) {
                error_log("Le dossier Services n'existe pas: $servicesPath");
            }
        }
        
        return $this;
    }
    
    /**
     * Enregistre un module dans l'application
     * 
     * Cette méthode effectue plusieurs opérations importantes :
     * 1. Stocke les informations du module (nom et chemin) dans la liste des modules
     * 2. Charge les contrôleurs du module pour qu'ils soient disponibles
     * 3. Charge les routes du module pour les intégrer au routeur
     * 
     * Un module HMVC est un composant autonome qui contient généralement :
     * - Des contrôleurs (Controllers/) : gèrent les requêtes et la logique
     * - Des modèles (Models/) : interagissent avec les données
     * - Des vues (Views/) : gèrent l'affichage et l'interface utilisateur
     * - Un fichier de routes (router.php) : définit les URLs du module
     * 
     * Exemple d'utilisation (généralement appelé automatiquement) :
     * 
     * // Enregistrer un module manuellement
     * $app->registerModule('Blog', ROOT_PATH . '/app/Modules/Blog');
     * 
     * @param string $name Nom du module (utilisé pour les namespaces et l'organisation)
     * @param string $path Chemin absolu vers le dossier du module
     * @return self Retourne l'instance de l'application pour permettre le chaînage
     */
    public function registerModule(string $name, string $path): self
    {
        // Ajouter le module à la liste des modules chargés
        $this->modules[$name] = [
            'name' => $name,
            'path' => $path
        ];
        
        // Charger les routes du module
        $this->loadModuleRoutes($name, $path);

        // Charger les services du module
        $this->loadModuleServices($name, $path);
        
        return $this;
    }
    
    /**
     * Charge les contrôleurs d'un module
     * 
     * Cette méthode parcourt le dossier Controllers/ d'un module
     * et inclut (require_once) tous les fichiers PHP qu'il contient.
     * 
     * Les contrôleurs sont des classes qui :
     * - Reçoivent les requêtes HTTP des utilisateurs
     * - Contiennent la logique métier ou appellent des services appropriés
     * - Interagissent avec les modèles pour récupérer ou modifier des données
     * - Préparent les données pour les passer aux vues
     * - Retournent les vues rendues ou des réponses appropriées (JSON, redirections, etc.)
     * 
     * @param string $moduleName Nom du module
     * @param string $modulePath Chemin absolu vers le dossier du module
     */
    private function loadModuleControllers(string $moduleName, string $modulePath): void
    {
        // Construire le chemin vers le dossier des contrôleurs du module
        $controllersPath = $modulePath . '/Controllers';
        
        // Vérifier si le dossier des contrôleurs existe
        if (is_dir($controllersPath)) {
            // Récupérer tous les fichiers PHP du dossier des contrôleurs
            $controllerFiles = glob($controllersPath . '/*.php');
            
            // Inclure chaque fichier de contrôleur trouvé
            foreach ($controllerFiles as $file) {
                require_once $file;
            }
        }
    }
    
    /**
     * Charge les routes d'un module
     * 
     * Cette méthode vérifie l'existence du fichier router.php dans le module
     * et l'inclut s'il existe. Ce fichier définit les routes du module,
     * c'est-à-dire les associations entre les URLs et les contrôleurs/actions.
     * 
     * Le fichier router.php reçoit deux variables injectées :
     * - $router : L'instance du routeur pour définir les routes
     * - $module : Le nom du module actuel
     * 
     * Exemple typique de contenu d'un fichier router.php de module :
     *
     * // Définir les routes du module User
     * $router->get('/profile', 'UserController@profile', $module);
     * $router->post('/login', 'AuthController@login', $module);
     * $router->get('/register', 'AuthController@showRegisterForm', $module);
     * $router->post('/register', 'AuthController@register', $module);
     * $router->get('/logout', 'AuthController@logout', $module);
     * 
     * @param string $moduleName Nom du module
     * @param string $modulePath Chemin absolu vers le dossier du module
     */
    private function loadModuleRoutes(string $moduleName, string $modulePath): void
    {
        // Construire le chemin vers le fichier router.php du module
        $routerFile = $modulePath . '/router.php';
        
        // Vérifier si le fichier router.php existe
        if (file_exists($routerFile)) {
            // Passer le routeur et le nom du module au fichier de routes
            // Ces variables seront disponibles dans le fichier inclus
            $router = $this->router;
            $module = $moduleName;
            
            // Charger les routes en incluant le fichier
            require $routerFile;
        }
    }

    private function loadModuleServices(string $moduleName, string $modulePath): void
    {
        $servicesFile = $modulePath . '/services.php';
        
        if (file_exists($servicesFile)) {
            $registerServices = require $servicesFile;
            
            if (is_callable($registerServices)) {
                $registerServices();
            }
        }
    }
    
    /**
     * Exécute l'application - Point d'entrée principal du framework
     * 
     * Cette méthode est le cœur du framework et gère tout le cycle de vie d'une requête :
     * 1. Charge tous les modules disponibles
     * 2. Analyse la requête entrante pour déterminer l'URI et la méthode HTTP
     * 3. Trouve la route correspondante via le routeur
     * 4. Applique les middlewares globaux et spécifiques à la route
     * 5. Exécute le contrôleur et l'action appropriés
     * 6. Envoie la réponse au client
     * 7. Gère les erreurs éventuelles
     * 
     * C'est cette méthode qui doit être appelée dans le fichier public/index.php
     * pour démarrer l'application.
     * 
     * Exemple d'utilisation :
     * 
     * $app = Application::getInstance();
     * $app->setModulesPath(ROOT_PATH . '/app/Modules')
     *     ->setSharedViewsPath(ROOT_PATH . '/assets/templates')
     *     ->addMiddleware(new SessionMiddleware())
     *     ->run(); // Démarrage du traitement de la requête
     */
    public function run(): void
    {
        // Charger tous les modules disponibles
        $this->loadModules();

        // Charger le domaine et les services
        $this->loadDomain();
        $this->loadServices();
        
        // Récupérer l'URI et la méthode HTTP de la requête entrante
        $uri = $this->request->getUri();
        $method = $this->request->getMethod();
        
        // Trouver la route correspondante à l'URI et à la méthode
        $route = $this->router->match($uri, $method);
        
        if ($route) {
            // Une route correspondante a été trouvée
            // Extraire les informations de la route
            [$module, $controller, $action, $params, $routeMiddlewares] = $route;
            
            try {
                // Appliquer les middlewares globaux
                foreach ($this->middlewares as $middleware) {
                    // Chaque middleware traite la requête et indique s'il faut continuer
                    $continue = $middleware->process($this->request, $this->response);
                    
                    if (!$continue) {
                        // Un middleware a rejeté la requête (ex: authentification échouée)
                        // La réponse a déjà été modifiée par le middleware (ex: redirection)
                        return; // Arrêter le traitement
                    }
                }
                
                // Appliquer les middlewares spécifiques à la route
                foreach ($routeMiddlewares as $middlewareClass) {
                    // Vérifier l'existence du fichier du middleware
                    if (file_exists(ROOT_PATH . '/app/Middleware/' . $middlewareClass . '.php')) {
                        // Charger et instancier le middleware
                        require_once ROOT_PATH . '/app/Middleware/' . $middlewareClass . '.php';
                        $middlewareClass = 'App\\Middleware\\' . $middlewareClass;
                        $middleware = new $middlewareClass();
                        
                        // Traiter la requête avec le middleware
                        $continue = $middleware->process($this->request, $this->response);
                        
                        if (!$continue) {
                            // Un middleware spécifique à la route a rejeté la requête
                            return; // Arrêter le traitement
                        }
                    }
                }
                
                // Tous les middlewares ont accepté la requête, exécuter le contrôleur
                if ($controller === null && $action instanceof \Closure) {
                    // Si c'est une route avec une closure (fonction anonyme)
                    // Par exemple: $router->get('/home', function($app) { ... });
                    $response = $action($this);
                } else {
                    // Si c'est une route avec un contrôleur standard
                    // Construire le nom complet de la classe du contrôleur avec son namespace
                    $controllerClass = "App\\Modules\\$module\\Controllers\\$controller";
                    
                    // Vérifier l'existence du contrôleur
                    if (!class_exists($controllerClass)) {
                        throw new \Exception("Contrôleur '$controllerClass' introuvable");
                    }
                    
                    // Instancier le contrôleur
                    $controllerInstance = new $controllerClass($this);
                    
                    // Vérifier l'existence de la méthode d'action
                    if (!method_exists($controllerInstance, $action)) {
                        throw new \Exception("Action '$action' introuvable dans le contrôleur");
                    }
                    
                    // Exécuter l'action du contrôleur avec les paramètres de la route
                    // call_user_func_array permet d'appeler une méthode avec un tableau de paramètres
                    $response = call_user_func_array([$controllerInstance, $action], $params);
                }
                
                // Traiter la réponse du contrôleur
                if (is_string($response)) {
                    // Si la réponse est une chaîne, la définir comme corps de la réponse
                    // Généralement du contenu HTML retourné par une vue
                    $this->response->setBody($response);
                } elseif ($response instanceof Response) {
                    // Si la réponse est un objet Response, l'utiliser directement
                    // Utile pour les retours spéciaux (redirections, JSON, etc.)
                    $this->response = $response;
                }
                
                // Envoyer la réponse finale au client
                $this->response->send();
                
            } catch (\Exception $e) {
                // Gérer les erreurs qui se produisent pendant l'exécution
                $this->handleError($e);
            }
        } else {
            // Aucune route correspondante n'a été trouvée (erreur 404)
            $this->response->setStatusCode(404);
            $this->response->setBody('Page non trouvée');
            $this->response->send();
        }
    }
    
    /**
     * Gère les erreurs survenues pendant l'exécution de l'application
     * 
     * Cette méthode centralise la gestion des exceptions et erreurs :
     * - En mode développement (DEBUG_MODE = true) : affiche les détails complets de l'erreur
     *   pour faciliter le débogage (message, fichier, ligne, stack trace)
     * - En mode production : affiche un message générique convivial pour l'utilisateur
     *   et journalise l'erreur complète pour les administrateurs
     * 
     * Cette approche permet de :
     * - Avoir un comportement cohérent pour toutes les erreurs
     * - Éviter de divulguer des informations sensibles en production
     * - Faciliter le débogage en développement
     * - Garder une trace des erreurs pour analyse ultérieure
     * 
     * @param \Exception $e L'exception à gérer
     */
    private function handleError(\Exception $e): void
    {
        // En mode développement, afficher les détails complets de l'erreur
        if (defined('DEBUG_MODE') && DEBUG_MODE) {
            $errorMessage = '<h1>Erreur</h1>';
            $errorMessage .= '<p><strong>Message:</strong> ' . $e->getMessage() . '</p>';
            $errorMessage .= '<p><strong>Fichier:</strong> ' . $e->getFile() . '</p>';
            $errorMessage .= '<p><strong>Ligne:</strong> ' . $e->getLine() . '</p>';
            $errorMessage .= '<h2>Stack Trace:</h2>';
            $errorMessage .= '<pre>' . $e->getTraceAsString() . '</pre>';
        } else {
            // En production, afficher un message d'erreur générique
            $errorMessage = '<h1>Une erreur est survenue</h1>';
            $errorMessage .= '<p>Veuillez réessayer plus tard.</p>';
            
            // Logger l'erreur complète pour les administrateurs
            // Les informations détaillées sont enregistrées dans le journal d'erreurs
            error_log($e->getMessage() . ' dans ' . $e->getFile() . ' à la ligne ' . $e->getLine());
        }
        
        // Définir le code de statut 500 (Erreur interne du serveur)
        $this->response->setStatusCode(500);
        // Définir le corps de la réponse avec le message d'erreur
        $this->response->setBody($errorMessage);
        // Envoyer la réponse au client
        $this->response->send();
    }
    
    /**
     * Récupère l'instance du routeur
     * 
     * Le routeur est responsable de faire correspondre les URLs entrantes
     * avec les contrôleurs et actions appropriés. Cette méthode permet
     * d'accéder à l'instance du routeur pour définir des routes ou
     * effectuer d'autres opérations.
     * 
     * Exemple d'utilisation :
     * 
     * // Ajouter une route manuellement
     * $router = $app->getRouter();
     * $router->get('/page-spéciale', function($app) {
     *     return 'Contenu de la page spéciale';
     * });
     * 
     * @return Router L'instance du routeur
     */
    public function getRouter(): Router
    {
        return $this->router;
    }
    
    /**
     * Récupère l'instance de la requête
     * 
     * L'objet Request contient toutes les informations sur la requête HTTP
     * actuelle (GET, POST, cookies, en-têtes, etc.) et fournit des méthodes
     * pour y accéder de manière sécurisée et abstraite.
     * 
     * Exemple d'utilisation :
     * 
     * // Dans un contrôleur ou un middleware
     * $request = $app->getRequest();
     * $username = $request->getPost('username');
     * $token = $request->getHeader('Authorization');
     * $isAjax = $request->isAjax();
     * 
     * @return Request L'instance de la requête
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
    
    /**
     * Récupère l'instance de la réponse
     * 
     * L'objet Response permet de construire une réponse HTTP à renvoyer
     * au client (contenu, code de statut, en-têtes, cookies, etc.).
     * 
     * Exemple d'utilisation :
     * 
     * // Dans un contrôleur ou un middleware
     * $response = $app->getResponse();
     * $response->setHeader('Content-Type', 'application/json');
     * $response->setStatusCode(201);
     * $response->setCookie('session_id', $sessionId, time() + 3600);
     * 
     * @return Response L'instance de la réponse
     */
    public function getResponse(): Response
    {
        return $this->response;
    }
    
    /**
     * Récupère l'instance de la base de données
     * 
     * L'objet Database gère la connexion à la base de données et fournit
     * des méthodes pour exécuter des requêtes SQL et récupérer des résultats
     * de manière sécurisée (avec des requêtes préparées).
     * 
     * Cette méthode peut retourner null si la connexion à la base de données
     * n'a pas été configurée (constantes DB_* non définies).
     * 
     * Exemple d'utilisation :
     * 
     * // Dans un modèle ou un contrôleur
     * $db = $app->getDatabase();
     * $users = $db->query("SELECT * FROM users WHERE active = :active", [':active' => 1]);
     * $success = $db->execute("UPDATE users SET last_login = NOW() WHERE id = :id", [':id' => $userId]);
     * 
     * @return Database|null L'instance de la base de données, ou null si non configurée
     */
    public function getDatabase(): ?Database
    {
        return $this->db;
    }
    
    /**
     * Récupère le chemin vers le dossier des vues partagées
     * 
     * Ces vues partagées contiennent généralement les layouts et templates
     * communs à plusieurs modules (entête, pied de page, etc.).
     * 
     * Si le chemin n'a pas été défini explicitement avec setSharedViewsPath(),
     * cette méthode retourne un chemin par défaut basé sur la constante ROOT_PATH.
     * 
     * Exemple d'utilisation :
     * 
     * // Dans un contrôleur ou une classe de vue
     * $sharedPath = $app->getSharedViewsPath();
     * $layoutFile = $sharedPath . '/main_layout.php';
     * 
     * @return string Chemin absolu vers le dossier des vues partagées
     */
    public function getSharedViewsPath(): string
    {
        // Utilise l'opérateur de coalescence null (??) pour retourner une valeur par défaut
        // si $this->sharedViewsPath n'est pas défini
        return $this->sharedViewsPath ?? ROOT_PATH . '/assets/templates';
    }

    /**
     * Récupère un service depuis le container
     * 
     * Cette méthode permet d'accéder facilement aux services enregistrés
     * depuis n'importe quel point de l'application qui a accès à l'instance App.
     * 
     * Exemple d'utilisation:
     * $app = Application::getInstance();
     * $userService = $app->getService('App\\Services\\User\\UserService');
     * $user = $userService->getUserById(1);
     * 
     * @param string $serviceId Identifiant du service à récupérer
     * @return mixed Instance du service demandé
     */
    public function getService(string $serviceId)
    {
        return ServiceContainer::resolve($serviceId);
    }
}