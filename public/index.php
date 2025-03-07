<?php
/**
 * Gitopedia - Point d'entrée principal de l'application
 * 
 * Ce fichier est le "Front Controller" de l'application, c'est-à-dire le point
 * d'entrée unique pour toutes les requêtes HTTP. Toutes les URLs du site passent
 * par ce script grâce aux règles de réécriture définies dans .htaccess.
 * 
 * Ce fichier remplit plusieurs fonctions fondamentales :
 * - Définition des constantes essentielles (ROOT_PATH)
 * - Chargement des fichiers de configuration
 * - Configuration de l'autoloader PSR-4
 * - Initialisation et démarrage de l'application
 * 
 * Dans l'architecture HMVC, ce fichier représente le niveau supérieur de la
 * hiérarchie et orchestre le lancement de l'ensemble du système.
 */

// -----------------------------------------------------------------------------
// DÉFINITION DU CHEMIN RACINE
// -----------------------------------------------------------------------------
/**
 * Définir la constante ROOT_PATH qui représente le chemin absolu vers
 * le dossier racine de l'application.
 * 
 * Cette constante est essentielle car elle sert de point de référence pour
 * tous les chemins absolus utilisés dans l'application, permettant une
 * portabilité complète indépendamment de l'emplacement d'installation.
 * 
 * dirname(__DIR__) remonte d'un niveau par rapport au dossier 'public'
 * pour accéder à la racine du projet.
 */
define('ROOT_PATH', dirname(__DIR__));

// -----------------------------------------------------------------------------
// CONFIGURATION DE L'AUTOLOADER PSR-4
// -----------------------------------------------------------------------------
/**
 * Enregistrement de l'autoloader PSR-4
 * 
 * L'autoloader PSR-4 est un standard PHP qui définit comment les classes
 * doivent être chargées automatiquement à partir de leur namespace.
 * 
 * Avantages :
 * - Charge les classes uniquement lorsqu'elles sont nécessaires
 * - Élimine les problèmes d'ordre de chargement des dépendances
 * - Simplifie la gestion des inclusions de fichiers
 * - Suit les standards modernes de l'écosystème PHP
 * 
 * Structure de correspondance :
 * - Namespace App\Core\... => app/Core/...
 * - Namespace App\Modules\... => app/Modules/...
 * - Namespace App\Domain\... => app/Domain/...
 * - Namespace App\Services\... => app/Services/...
 */
spl_autoload_register(function ($class) {
    // Préfixe de namespace de base pour l'application
    $prefix = 'App\\';
    $base_dir = ROOT_PATH . '/app/';
    
    // Vérifier si la classe utilise le préfixe de notre application
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return; // Si non, quitter et laisser d'autres autoloaders s'en charger
    }
    
    // Récupérer le chemin relatif à partir du namespace
    $relative_class = substr($class, $len);
    
    // Remplacer les séparateurs de namespace (\) par des séparateurs de répertoire (/)
    // et ajouter .php à la fin pour obtenir le chemin du fichier
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    // Si le fichier existe, le charger
    if (file_exists($file)) {
        require $file;
    }
});

// -----------------------------------------------------------------------------
// CHARGEMENT DES CONFIGURATIONS
// -----------------------------------------------------------------------------
/**
 * Inclure les fichiers de configuration de l'application
 * 
 * Ces fichiers définissent les constantes et paramètres globaux
 * nécessaires au fonctionnement de l'application :
 * - config.php : Configuration générale (débogage, URL, timezone...)
 * - database.php : Paramètres de connexion à la base de données
 */
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/config/database.php';

// -----------------------------------------------------------------------------
// INITIALISATION DE L'APPLICATION
// -----------------------------------------------------------------------------
/**
 * Créer l'instance principale de l'application
 * 
 * Cette instance unique (Singleton) coordonne tous les composants
 * et gère le cycle de vie complet de la requête HTTP.
 */
$app = new App\Core\Application();

/**
 * Initialiser les services de l'application
 * 
 * Cette étape enregistre tous les services définis dans services.php
 * auprès du container d'injection de dépendances.
 */
$initServices = require_once ROOT_PATH . '/app/services.php';
$initServices();

// -----------------------------------------------------------------------------
// CONFIGURATION DE BASE
// -----------------------------------------------------------------------------
/**
 * Configurer les chemins essentiels de l'application
 * 
 * - setModulesPath : Définit l'emplacement des modules HMVC
 *   Les modules sont les composants fonctionnels de l'application (Blog, User, etc.)
 * 
 * - setSharedViewsPath : Définit l'emplacement des templates partagés
 *   Ces templates sont utilisés par tous les modules (layouts, partials, etc.)
 */
$app->setModulesPath(ROOT_PATH . '/app/Modules');
$app->setSharedViewsPath(ROOT_PATH . '/assets/templates');

// -----------------------------------------------------------------------------
// DÉMARRAGE DE L'APPLICATION
// -----------------------------------------------------------------------------
/**
 * Lancer l'exécution de l'application
 * 
 * Cette méthode déclenche le processus complet de traitement de la requête :
 * 1. Analyse de la requête entrante
 * 2. Correspondance avec une route définie
 * 3. Exécution des middlewares applicables
 * 4. Instanciation du contrôleur approprié
 * 5. Exécution de l'action du contrôleur
 * 6. Génération et envoi de la réponse
 * 
 * C'est le point final de ce fichier d'entrée, après quoi
 * le contrôle passe entièrement au framework HMVC.
 */
$app->run();