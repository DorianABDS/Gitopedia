<?php
/**
 * Classe Controller - Classe de base pour tous les contrôleurs
 * 
 * Cette classe abstraite sert de fondation à tous les contrôleurs de l'application.
 * Elle implémente le C (Controller) du pattern MVC/HMVC et fournit un ensemble
 * de fonctionnalités communes et essentielles pour tous les contrôleurs :
 * - Accès aux composants principaux de l'application (requête, réponse, base de données)
 * - Méthodes pour le rendu des vues avec ou sans layout
 * - Utilitaires pour générer des réponses JSON et effectuer des redirections
 * - Localisation automatique des vues en fonction des noms de modules
 * 
 * Dans l'architecture HMVC, les contrôleurs sont responsables de :
 * - Intercepter et traiter les requêtes HTTP entrantes
 * - Exécuter la logique métier appropriée, souvent via des services ou modèles
 * - Préparer les données nécessaires pour l'affichage
 * - Choisir et rendre la vue adaptée à la demande
 * - Retourner une réponse appropriée au client (HTML, JSON, redirection, etc.)
 */

namespace App\Core;

use App\Core\ServiceContainer;

class Controller
{
    /**
     * Instance de l'application
     * 
     * Référence à l'instance principale de l'application, permettant
     * d'accéder aux services globaux comme le routeur, la configuration,
     * ou tout autre composant enregistré dans l'application.
     * 
     * Cette propriété est injectée dans le constructeur et permet
     * aux contrôleurs d'interagir avec l'ensemble du système.
     * 
     * @var Application
     */
    protected Application $app;
    
    /**
     * Instance de la requête
     * 
     * Encapsule toutes les données de la requête HTTP entrante :
     * - Méthode HTTP (GET, POST, etc.)
     * - Paramètres d'URL ($_GET)
     * - Données de formulaire ($_POST)
     * - Cookies ($_COOKIE)
     * - En-têtes HTTP
     * - Fichiers téléversés ($_FILES)
     * - Autres paramètres de la requête
     * 
     * Cette encapsulation offre une abstraction sécurisée et testable
     * pour accéder aux données entrantes sans utiliser directement
     * les variables superglobales de PHP.
     * 
     * @var Request
     */
    protected Request $request;
    
    /**
     * Instance de la réponse
     * 
     * Permet de construire la réponse HTTP à renvoyer au client avec :
     * - Le contenu (corps de la réponse)
     * - Le code de statut HTTP (200, 404, 500, etc.)
     * - Les en-têtes HTTP (Content-Type, Location, etc.)
     * - Les cookies à définir
     * 
     * Cette abstraction facilite la création de différents types de
     * réponses (HTML, JSON, redirections, etc.) de manière cohérente.
     * 
     * @var Response
     */
    protected Response $response;
    
    /**
     * Instance de la base de données
     * 
     * Fournit un accès à la base de données pour interagir
     * avec les données persistantes de l'application.
     * 
     * Cette propriété peut être null si l'application n'utilise
     * pas de base de données ou si la connexion n'est pas configurée.
     * 
     * @var Database|null
     */
    protected ?Database $db;
    
    /**
     * Constructeur du contrôleur
     * 
     * Initialise les propriétés fondamentales du contrôleur en récupérant
     * les instances nécessaires depuis l'application principale.
     * 
     * Ce constructeur est appelé automatiquement lors de l'instanciation
     * d'un contrôleur par le système de routage.
     * 
     * Exemple d'utilisation interne (par le routeur) :
     * 
     * $controllerInstance = new UserController($app);
     * $response = $controllerInstance->profile($userId);
     * 
     * @param Application $app L'instance de l'application
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->request = $app->getRequest();
        $this->response = $app->getResponse();
        $this->db = $app->getDatabase();
    }
    
    /**
     * Rend une vue et retourne son contenu
     * 
     * Cette méthode centrale charge un fichier de vue PHP, lui passe les
     * données fournies, et capture le contenu généré.
     * 
     * Trois formats de chemins de vue sont pris en charge :
     * 1. Chemin absolu commençant par '/' (ex: '/path/to/view')
     *    Utile pour des vues situées hors de la structure standard
     * 
     * 2. Format Module:Vue (ex: 'User:profile')
     *    Pour référencer explicitement une vue d'un module spécifique
     * 
     * 3. Nom de vue simple (ex: 'index')
     *    Dans ce cas, le module est automatiquement déduit du nom du contrôleur
     * 
     * Cette flexibilité permet de réutiliser des vues entre modules
     * tout en gardant une organisation claire du code.
     * 
     * Exemple d'utilisation dans un contrôleur :
     * 
     * // Rendre une vue du module actuel
     * $content = $this->view('dashboard', ['user' => $user, 'stats' => $stats]);
     * 
     * // Rendre une vue d'un autre module
     * $content = $this->view('User:profile', ['user' => $user]);
     * 
     * @param string $view Le chemin ou identifiant de la vue à rendre
     * @param array $data Les données à passer à la vue (variables)
     * @return string Le contenu HTML généré par la vue
     * @throws \Exception Si la vue n'est pas trouvée
     */
    protected function view(string $view, array $data = []): string
    {
        // Extraire les données pour qu'elles soient disponibles dans la vue
        // Cette fonction transforme chaque clé du tableau $data en variable PHP
        // Par exemple, $data['user'] devient accessible comme $user dans la vue
        extract($data);
        
        // Déterminer le chemin complet de la vue selon le format spécifié
        if (strpos($view, '/') === 0) {
            // Si le chemin commence par /, utiliser tel quel (chemin absolu)
            $viewPath = ROOT_PATH . $view . '.php';
        } elseif (strpos($view, ':') !== false) {
            // Format Module:view (par exemple User:profile)
            // Découper la chaîne pour séparer le module et le nom de la vue
            list($module, $viewName) = explode(':', $view);
            $viewPath = ROOT_PATH . "/app/Modules/$module/Views/$viewName.php";
        } else {
            // Trouver automatiquement le module à partir de la classe du contrôleur
            // Cette approche convention-over-configuration simplifie l'organisation du code
            $className = get_class($this);
            $moduleName = $this->getModuleFromClassName($className);
            $viewPath = ROOT_PATH . "/app/Modules/$moduleName/Views/$view.php";
        }
        
        // Vérifier si le fichier de vue existe
        if (!file_exists($viewPath)) {
            throw new \Exception("Vue '$viewPath' introuvable");
        }
        
        // Capturer la sortie générée par la vue
        // Cette technique permet d'inclure un fichier PHP et de récupérer
        // son contenu généré plutôt que de l'afficher immédiatement
        ob_start();
        include $viewPath;
        $content = ob_get_clean();
        
        return $content;
    }
    
    /**
     * Affiche une vue avec un layout et retourne le contenu complet
     * 
     * Cette méthode enrichit le système de vues en intégrant le concept de layout.
     * Elle fonctionne en deux étapes :
     * 1. Génère d'abord le contenu de la vue principale
     * 2. Intègre ce contenu dans un layout (template principal) qui définit
     *    la structure globale de la page (header, footer, navigation, etc.)
     * 
     * Les layouts facilitent la cohérence visuelle de l'application
     * en évitant la duplication de code de structure HTML.
     * 
     * Exemple d'utilisation dans un contrôleur :
     * 
     * // Rendre une vue intégrée dans le layout principal
     * return $this->viewWithLayout('profile', 'main', [
     *     'user' => $currentUser,
     *     'title' => 'Profil utilisateur'
     * ]);
     * 
     * @param string $view Le chemin de la vue à rendre (contenu principal)
     * @param string $layout Le nom ou chemin du layout à utiliser
     * @param array $data Les données à passer à la vue et au layout
     * @return string Le contenu HTML complet (layout + vue)
     * @throws \Exception Si le layout n'est pas trouvé
     */
    protected function viewWithLayout(string $view, string $layout, array $data = []): string
    {
        // Générer le contenu de la vue principale
        $content = $this->view($view, $data);
        
        // Ajouter le contenu de la vue aux données pour qu'il soit accessible dans le layout
        // Le layout pourra l'inclure via la variable $content
        $data['content'] = $content;
        
        // Déterminer le chemin du layout
        if (strpos($layout, '/') === 0) {
            // Si le chemin commence par /, utiliser tel quel (chemin absolu)
            $layoutPath = ROOT_PATH . $layout . '.php';
        } else {
            // Sinon, chercher dans les templates partagés de l'application
            // Cela centralise les layouts pour une meilleure organisation
            $layoutPath = $this->app->getSharedViewsPath() . '/' . $layout . '.php';
        }
        
        // Vérifier si le fichier de layout existe
        if (!file_exists($layoutPath)) {
            throw new \Exception("Layout '$layoutPath' introuvable");
        }
        
        // Extraire les données pour qu'elles soient disponibles dans le layout
        extract($data);
        
        // Capturer la sortie générée par le layout
        ob_start();
        include $layoutPath;
        return ob_get_clean();
    }

    /**
     * Crée une instance du gestionnaire de layout
     * 
     * Cette méthode utilitaire facilite l'utilisation de la classe Layout,
     * qui offre une approche orientée objet plus flexible pour la gestion
     * des layouts comparée à viewWithLayout().
     * 
     * L'approche orientée objet permet notamment :
     * - De configurer progressivement le layout (titre, variables, etc.)
     * - D'enchaîner les appels de méthode (fluent interface)
     * - De réutiliser un layout configuré pour plusieurs vues
     * 
     * Exemple d'utilisation dans un contrôleur :
     * 
     * // Créer et configurer un layout
     * $layout = $this->createLayout('Tableau de bord');
     * $layout->setVariable('user', $currentUser);
     * $layout->setVariable('notifications', $notifs);
     * 
     * // Générer et intégrer la vue dans le layout
     * $content = $this->view('dashboard', ['stats' => $stats]);
     * $layout->setContent($content);
     * 
     * // Rendre le tout
     * return $layout->render();
     * 
     * @param string $title Titre de la page (optionnel)
     * @return Layout Instance du gestionnaire de layout
     */
    protected function createLayout(string $title = ''): Layout
    {
        // Créer une nouvelle instance du gestionnaire de layout
        $layout = new Layout($this->app);
        
        // Définir le titre si fourni
        if (!empty($title)) {
            $layout->setTitle($title);
        }
        
        return $layout;
    }
    
    /**
     * Répond avec des données au format JSON
     * 
     * Cette méthode simplifie la création de réponses JSON, couramment
     * utilisées pour les API REST et les requêtes AJAX. Elle :
     * - Convertit automatiquement les données en JSON
     * - Définit l'en-tête Content-Type approprié
     * - Applique le code de statut HTTP spécifié
     * - Envoie immédiatement la réponse au client
     * 
     * Exemple d'utilisation dans un contrôleur API :
     * 
     * // Réponse JSON réussie
     * $this->json(['success' => true, 'data' => $user]);
     * 
     * // Réponse JSON d'erreur
     * $this->json(['error' => 'User not found'], 404);
     * 
     * @param mixed $data Les données à convertir en JSON
     * @param int $statusCode Le code de statut HTTP (200 par défaut)
     */
    protected function json($data, int $statusCode = 200): void
    {
        // Délègue à la méthode json() de l'objet Response
        $this->response->json($data, $statusCode);
    }
    
    /**
     * Effectue une redirection vers une autre URL
     * 
     * Cette méthode simplifie les redirections HTTP en configurant
     * automatiquement l'en-tête Location et le code de statut approprié,
     * puis en envoyant immédiatement la réponse au client.
     * 
     * Les codes de statut courants pour les redirections sont :
     * - 301 : Redirection permanente
     * - 302 : Redirection temporaire (par défaut)
     * - 303 : See Other (souvent utilisé après un POST)
     * - 307 : Redirection temporaire stricte (conserve la méthode HTTP)
     * 
     * Exemple d'utilisation dans un contrôleur :
     * 
     * // Redirection simple
     * $this->redirect('/login');
     * 
     * // Redirection permanente
     * $this->redirect('/nouvelle-page', 301);
     * 
     * // Après un traitement de formulaire
     * public function register() {
     *     // Traitement du formulaire d'inscription...
     *     $this->redirect('/merci', 303);
     * }
     * 
     * @param string $url L'URL de destination
     * @param int $statusCode Le code de statut HTTP (302 par défaut)
     */
    protected function redirect(string $url, int $statusCode = 302): void
    {
        // Délègue à la méthode redirect() de l'objet Response
        $this->response->redirect($url, $statusCode);
    }
    
    /**
     * Extrait le nom du module à partir du nom de classe du contrôleur
     * 
     * Cette méthode utilitaire interne permet de déterminer automatiquement
     * le module auquel appartient un contrôleur en analysant son namespace.
     * Elle est utilisée pour localiser automatiquement les vues associées
     * au contrôleur selon la convention de nommage HMVC.
     * 
     * Le format attendu pour le namespace est :
     * App\Modules\{ModuleName}\Controllers\{ControllerName}
     * 
     * Cette automatisation repose sur la convention sur la configuration,
     * simplifiant ainsi le développement et la maintenance.
     * 
     * @param string $className Le nom complet de la classe (avec namespace)
     * @return string Le nom du module
     * @throws \Exception Si le module ne peut pas être déterminé
     */
    private function getModuleFromClassName(string $className): string
    {
        // Découper le namespace en parties
        $parts = explode('\\', $className);
        
        // Format attendu: App\Modules\ModuleName\Controllers\ControllerName
        if (count($parts) >= 4 && $parts[0] === 'App' && $parts[1] === 'Modules') {
            return $parts[2]; // Le nom du module est la 3ème partie du namespace
        }
        
        // Si le format ne correspond pas à la convention, lever une exception
        throw new \Exception("Impossible de déterminer le module à partir de la classe '$className'");
    }
    
    /**
     * Récupère un service depuis le container
     * 
     * Cette méthode utilitaire permet d'accéder facilement aux services
     * enregistrés depuis n'importe quel contrôleur. Elle utilise le
     * ServiceContainer pour résoudre les dépendances automatiquement.
     * 
     * Exemples d'utilisation:
     * 
     * // Accès au service utilisateur
     * $userService = $this->getService('App\\Services\\User\\UserService');
     * $user = $userService->getUserById($id);
     * 
     * // Ou avec un alias plus court (si défini dans services.php)
     * $userService = $this->getService('user');
     * 
     * @param string $serviceId Identifiant du service à récupérer
     * @return mixed Instance du service demandé
     */
    protected function getService(string $serviceId)
    {
        return ServiceContainer::resolve($serviceId);
    }
}