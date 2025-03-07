<?php
/**
 * Classe CommandRunner - Gestionnaire d'exécution des commandes CLI
 * 
 * Cette classe centrale du système CLI est responsable de :
 * - Découvrir et enregistrer toutes les commandes disponibles
 * - Analyser les arguments de la ligne de commande
 * - Rechercher et instancier la commande demandée
 * - Exécuter la commande avec les arguments appropriés
 * - Afficher l'aide générale ou spécifique à une commande
 * 
 * Le CommandRunner agit comme un point d'entrée unifié pour toutes les
 * interactions en ligne de commande avec le framework, offrant une interface
 * cohérente et extensible pour l'automatisation des tâches de développement.
 */

namespace App\CLI;

class CommandRunner
{
    /**
     * Registre des commandes disponibles
     * 
     * Tableau associatif qui map les noms de commandes aux noms de classes
     * qui les implémentent.
     * 
     * Format: ['nom:commande' => 'App\CLI\NomCommande']
     * 
     * @var array
     */
    private array $commands = [];
    
    /**
     * Constructeur - Enregistre toutes les commandes disponibles
     * 
     * Parcourt le répertoire des commandes, détecte automatiquement toutes
     * les classes de commande et les enregistre dans le registre des commandes.
     */
    public function __construct()
    {
        $this->registerCommands();
    }
    
    /**
     * Enregistre toutes les commandes disponibles
     * 
     * Cette méthode découvre automatiquement toutes les classes de commande
     * dans le répertoire CLI et les enregistre pour utilisation.
     * 
     * L'approche automatique d'enregistrement permet d'ajouter de nouvelles
     * commandes simplement en créant une nouvelle classe dans le répertoire
     * approprié, sans avoir à mettre à jour manuellement une liste.
     */
    private function registerCommands(): void
    {
        // Chemin vers le répertoire des commandes
        $commandsDir = __DIR__;
        
        // Parcourir les fichiers PHP dans le répertoire
        foreach (glob($commandsDir . '/*.php') as $file) {
            $className = basename($file, '.php');
            
            // Ne pas considérer les classes abstraites ou le CommandRunner lui-même
            if ($className === 'Command' || $className === 'CommandRunner') {
                continue;
            }
            
            // Construire le nom complet de la classe avec namespace
            $fullClassName = 'App\\CLI\\' . $className;
            
            // Vérifier si la classe existe et est une sous-classe de Command
            if (class_exists($fullClassName) && is_subclass_of($fullClassName, Command::class)) {
                // Instancier temporairement pour récupérer le nom de la commande
                $command = new $fullClassName();
                $this->commands[$command->getName()] = $fullClassName;
            }
        }
    }
    
    /**
     * Exécute la commande demandée
     * 
     * Cette méthode centrale analyse les arguments de ligne de commande,
     * identifie la commande demandée, et l'exécute avec les arguments appropriés.
     * 
     * Si aucune commande n'est spécifiée ou si la commande est 'help',
     * elle affiche l'aide générale ou l'aide spécifique à une commande.
     * 
     * @param array $args Arguments de ligne de commande
     * @return int Code de retour (0 pour succès, autre pour erreur)
     */
    public function run(array $args): int
    {
        // Supprimer le nom du script (premier argument)
        array_shift($args);
        
        // Si aucun argument, afficher l'aide générale
        if (empty($args)) {
            $this->showHelp();
            return 0;
        }
        
        // Récupérer le nom de la commande (premier argument)
        $commandName = array_shift($args);
        
        // Commande d'aide spéciale
        if ($commandName === 'help') {
            if (empty($args)) {
                // Aide générale
                $this->showHelp();
            } else {
                // Aide pour une commande spécifique
                $this->showCommandHelp($args[0]);
            }
            return 0;
        }
        
        // Vérifier si la commande existe
        if (!isset($this->commands[$commandName])) {
            $this->output("Erreur: Commande '$commandName' introuvable.", 'error');
            $this->output("Exécutez 'php gitopedia help' pour voir la liste des commandes disponibles.");
            return 1;
        }
        
        // Si l'option --help est présente, afficher l'aide de la commande
        if (in_array('--help', $args) || in_array('-h', $args)) {
            $this->showCommandHelp($commandName);
            return 0;
        }
        
        try {
            // Instancier et exécuter la commande
            $commandClass = $this->commands[$commandName];
            $command = new $commandClass();
            return $command->execute($args);
        } catch (\Exception $e) {
            $this->output("Erreur lors de l'exécution de la commande: " . $e->getMessage(), 'error');
            return 1;
        }
    }
    
    /**
     * Affiche l'aide générale avec la liste des commandes disponibles
     * 
     * Cette méthode génère et affiche une documentation d'aide complète
     * qui liste toutes les commandes disponibles avec leurs descriptions.
     */
    private function showHelp(): void
    {
        $this->output("\nGitopedia CLI - Outil en ligne de commande\n", 'info');
        $this->output("Usage: php gitopedia <commande> [options] [arguments]\n", 'info');
        
        $this->output("Commandes disponibles:", 'info');
        
        // Trier les commandes par nom pour une meilleure lisibilité
        ksort($this->commands);
        
        // Définir la largeur de la colonne pour les noms de commande
        $maxLength = max(array_map('strlen', array_keys($this->commands)));
        
        // Afficher chaque commande avec sa description
        foreach ($this->commands as $name => $class) {
            $command = new $class();
            $padding = str_repeat(' ', $maxLength - strlen($name) + 2);
            $this->output("  $name$padding{$command->getDescription()}");
        }
        
        $this->output("\nPour plus d'informations sur une commande: php gitopedia help <commande>\n");
    }
    
    /**
     * Affiche l'aide spécifique à une commande
     * 
     * @param string $commandName Nom de la commande
     * @return void
     */
    private function showCommandHelp(string $commandName): void
    {
        // Vérifier si la commande existe
        if (!isset($this->commands[$commandName])) {
            $this->output("Erreur: Commande '$commandName' introuvable.", 'error');
            $this->output("Exécutez 'php gitopedia help' pour voir la liste des commandes disponibles.");
            return;
        }
        
        // Instancier la commande et afficher son aide
        $commandClass = $this->commands[$commandName];
        $command = new $commandClass();
        $command->showHelp();
    }
    
    /**
     * Affiche un message dans la console
     * 
     * @param string $message Message à afficher
     * @param string $type Type de message (info, success, warning, error)
     */
    private function output(string $message, string $type = 'info'): void
    {
        $colors = [
            'info' => "\033[0;36m",     // Cyan
            'success' => "\033[0;32m",  // Vert
            'warning' => "\033[0;33m",  // Jaune
            'error' => "\033[0;31m"     // Rouge
        ];
        
        $reset = "\033[0m";
        
        // Appliquer la couleur correspondant au type, si disponible
        $color = $colors[$type] ?? $colors['info'];
        
        echo $color . $message . $reset . PHP_EOL;
    }
}