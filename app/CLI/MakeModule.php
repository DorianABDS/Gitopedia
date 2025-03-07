<?php
/**
 * Classe MakeModule - Commande CLI pour générer un nouveau module HMVC
 * 
 * Cette commande permet de créer rapidement un nouveau module complet
 * avec sa structure de base (contrôleurs, modèles, vues, router) en
 * suivant les conventions du framework HMVC.
 * 
 * La génération automatique de modules permet d'accélérer le développement
 * en s'assurant que tous les fichiers nécessaires sont créés avec la bonne
 * structure et les conventions de nommage appropriées, réduisant ainsi
 * le risque d'erreurs et garantissant la cohérence de l'application.
 */

namespace App\CLI;

class MakeModule extends Command
{
    /**
     * Nom de la commande
     * 
     * @var string
     */
    protected string $name = 'make:module';
    
    /**
     * Description de la commande
     * 
     * @var string
     */
    protected string $description = 'Crée un nouveau module HMVC avec sa structure de base';
    
    /**
     * Arguments attendus
     * 
     * @var array
     */
    protected array $arguments = [
        'nom' => 'Nom du module à créer (ex: Blog, User, Admin)'
    ];
    
    /**
     * Options disponibles
     * 
     * @var array
     */
    protected array $options = [
        '--force' => 'Écraser le module s\'il existe déjà',
        '--model' => 'Inclure un modèle de base',
        '--controller' => 'Nom personnalisé pour le contrôleur principal (défaut: {Nom}Controller)',
        '--resource' => 'Générer un module avec des actions CRUD complètes',
        '--minimal' => 'Générer un module minimaliste avec seulement l\'essentiel'
    ];
    
    /**
     * Chemin vers le dossier des templates
     * 
     * @var string
     */
    private string $templatesPath;
    
    /**
     * Constructeur - Initialise le chemin des templates
     */
    public function __construct()
    {
        $this->templatesPath = ROOT_PATH . '/app/CLI/Templates';
    }
    
    /**
     * Exécute la commande de création de module
     * 
     * Cette méthode implémente la logique principale de la commande :
     * 1. Valide les arguments fournis
     * 2. Vérifie si le module existe déjà
     * 3. Crée le répertoire du module et sa structure
     * 4. Génère les fichiers de base du module à partir des templates
     * 
     * @param array $args Arguments passés à la commande
     * @return int Code de retour (0 pour succès, autre pour erreur)
     */
    public function execute(array $args): int
    {
        // Vérifier si le nom du module est fourni
        if (empty($args[0])) {
            $this->output("Erreur: Nom du module manquant.", 'error');
            $this->showHelp();
            return 1;
        }
        
        // Récupérer le nom du module et le normaliser
        $moduleName = ucfirst($args[0]);
        
        // Vérifier si le module existe déjà
        $modulePath = ROOT_PATH . '/app/Modules/' . $moduleName;
        
        if (is_dir($modulePath) && !$this->hasOption($args, '--force')) {
            $this->output("Erreur: Le module '$moduleName' existe déjà.", 'error');
            $this->output("Utilisez l'option --force pour écraser le module existant.");
            return 1;
        }
        
        // Récupérer les options
        $includeModel = $this->hasOption($args, '--model');
        $isResource = $this->hasOption($args, '--resource');
        $isMinimal = $this->hasOption($args, '--minimal');
        $controllerName = $this->getOptionValue($args, '--controller', $moduleName . 'Controller');
        
        // Vérifier la compatibilité des options
        if ($isResource && $isMinimal) {
            $this->output("Erreur: Les options --resource et --minimal sont mutuellement exclusives.", 'error');
            return 1;
        }
        
        // Vérifier si le dossier des templates existe
        if (!is_dir($this->templatesPath)) {
            // Si le répertoire n'existe pas, le créer
            if (!mkdir($this->templatesPath, 0755, true)) {
                $this->output("Erreur: Impossible de créer le répertoire des templates.", 'error');
                return 1;
            }
            
            // Créer le sous-répertoire pour les vues
            $viewsPath = $this->templatesPath . '/views';
            if (!is_dir($viewsPath) && !mkdir($viewsPath, 0755, true)) {
                $this->output("Erreur: Impossible de créer le répertoire des templates de vues.", 'error');
                return 1;
            }
        }
        
        try {
            // Créer le répertoire du module et sa structure
            $this->createModuleStructure($moduleName, $modulePath);
            
            // Préparer les variables de remplacement pour les templates
            $vars = [
                '{{MODULE_NAME}}' => $moduleName,
                '{{CONTROLLER_NAME}}' => $controllerName,
                '{{MODULE_NAME_LOWERCASE}}' => strtolower($moduleName),
                '{{YEAR}}' => date('Y'),
                '{{DATE}}' => date('Y-m-d')
            ];
            
            // Générer les fichiers du module à partir des templates
            $this->generateFileFromTemplate(
                $modulePath . '/Controllers/' . $controllerName . '.php',
                $isMinimal ? 'controller_minimal.php.tpl' : 
                    ($isResource ? 'controller_resource.php.tpl' : 'controller.php.tpl'),
                $vars
            );
            
            $this->generateFileFromTemplate(
                $modulePath . '/router.php',
                $isMinimal ? 'router_minimal.php.tpl' :
                    ($isResource ? 'router_resource.php.tpl' : 'router.php.tpl'),
                $vars
            );
            
            // Générer les vues
            $this->generateViewFiles($moduleName, $modulePath, $isResource, $isMinimal, $vars);
            
            // Générer le modèle si demandé
            if ($includeModel || $isResource) {
                $modelName = $moduleName . 'Model';
                $vars['{{MODEL_NAME}}'] = $modelName;
                $vars['{{TABLE_NAME}}'] = strtolower($moduleName) . 's';
                
                $this->generateFileFromTemplate(
                    $modulePath . '/Models/' . $modelName . '.php',
                    $isResource ? 'model_resource.php.tpl' : 'model.php.tpl',
                    $vars
                );
            }
            
            $this->output("Module '$moduleName' créé avec succès !", 'success');
            return 0;
        } catch (\Exception $e) {
            $this->output("Erreur lors de la création du module: " . $e->getMessage(), 'error');
            return 1;
        }
    }
    
    /**
     * Crée la structure de répertoires du module
     * 
     * @param string $moduleName Nom du module
     * @param string $modulePath Chemin vers le répertoire du module
     */
    private function createModuleStructure(string $moduleName, string $modulePath): void
    {
        // Créer le répertoire principal du module
        if (!is_dir($modulePath)) {
            if (!mkdir($modulePath, 0755, true)) {
                throw new \Exception("Impossible de créer le répertoire du module");
            }
        }
        
        // Créer les sous-répertoires
        $subDirectories = [
            '/Controllers',
            '/Models',
            '/Views',
        ];
        
        foreach ($subDirectories as $dir) {
            $path = $modulePath . $dir;
            if (!is_dir($path) && !mkdir($path, 0755, true)) {
                throw new \Exception("Impossible de créer le répertoire $dir");
            }
        }
        
        $this->output("Structure de répertoires créée pour le module '$moduleName'", 'info');
    }
    
    /**
     * Génère les fichiers de vue du module
     * 
     * @param string $moduleName Nom du module
     * @param string $modulePath Chemin vers le répertoire du module
     * @param bool $isResource Si vrai, génère des vues CRUD complètes
     * @param bool $isMinimal Si vrai, génère une vue minimale
     * @param array $vars Variables de remplacement
     */
    private function generateViewFiles(string $moduleName, string $modulePath, bool $isResource, bool $isMinimal, array $vars): void
    {
        $viewsPath = $modulePath . '/Views';
        
        if ($isMinimal) {
            // Génération minimale - seulement index
            $this->generateFileFromTemplate(
                $viewsPath . '/index.php',
                'views/index_minimal.php.tpl',
                $vars
            );
        } else {
            // Génération standard ou ressource
            // Générer la vue index
            $this->generateFileFromTemplate(
                $viewsPath . '/index.php',
                'views/index.php.tpl',
                $vars
            );
            
            // Générer la vue show
            $this->generateFileFromTemplate(
                $viewsPath . '/show.php',
                'views/show.php.tpl',
                $vars
            );
            
            // Si c'est une ressource, générer les vues supplémentaires
            if ($isResource) {
                $this->generateFileFromTemplate(
                    $viewsPath . '/create.php',
                    'views/create.php.tpl',
                    $vars
                );
                
                $this->generateFileFromTemplate(
                    $viewsPath . '/edit.php',
                    'views/edit.php.tpl',
                    $vars
                );
            }
        }
        
        $this->output("Vues générées pour le module '$moduleName'", 'info');
    }
    
    /**
     * Génère un fichier à partir d'un template
     * 
     * @param string $destination Chemin du fichier à créer
     * @param string $template Nom du fichier template
     * @param array $vars Variables de remplacement
     */
    private function generateFileFromTemplate(string $destination, string $template, array $vars): void
    {
        $templatePath = $this->templatesPath . '/' . $template;
        
        // Vérifier si le template existe
        if (!file_exists($templatePath)) {
            throw new \Exception("Template non trouvé: $template");
        }
        
        // Lire le contenu du template
        $content = file_get_contents($templatePath);
        
        // Remplacer les variables
        foreach ($vars as $key => $value) {
            $content = str_replace($key, $value, $content);
        }
        
        // Écrire le fichier de destination
        if (file_put_contents($destination, $content) === false) {
            throw new \Exception("Impossible d'écrire le fichier: $destination");
        }
        
        $this->output("Fichier créé: " . basename($destination), 'info');
    }
}