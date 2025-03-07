<?php
/**
 * Classe Command - Classe abstraite de base pour toutes les commandes CLI
 * 
 * Cette classe définit l'interface commune que toutes les commandes CLI
 * doivent implémenter. Elle établit une structure cohérente permettant au
 * CommandRunner d'exécuter n'importe quelle commande de manière uniforme.
 * 
 * Dans l'architecture du framework, les commandes CLI permettent d'automatiser
 * diverses tâches de développement et de maintenance, comme :
 * - Génération de code (modules, contrôleurs, modèles)
 * - Opérations de base de données (migrations, seeds)
 * - Tâches de maintenance (cache, logs, optimisations)
 * - Déploiement et gestion de l'environnement
 * 
 * Chaque commande spécifique étend cette classe et implémente sa propre
 * logique métier dans la méthode execute().
 */

namespace App\CLI;

abstract class Command
{
    /**
     * Nom de la commande
     * 
     * Ce nom sera utilisé pour invoquer la commande depuis la ligne de commande.
     * Par exemple, si $name = 'make:module', la commande sera exécutée via :
     * php gitopedia make:module NomDuModule
     * 
     * @var string
     */
    protected string $name;
    
    /**
     * Description de la commande
     * 
     * Cette description sera affichée dans l'aide de la ligne de commande
     * pour informer l'utilisateur sur le but et l'utilisation de la commande.
     * 
     * @var string
     */
    protected string $description;
    
    /**
     * Arguments de la commande
     * 
     * Liste des arguments attendus par la commande, sous forme d'un tableau :
     * [
     *     'nom' => 'Description de l'argument'
     * ]
     * 
     * @var array
     */
    protected array $arguments = [];
    
    /**
     * Options de la commande
     * 
     * Liste des options acceptées par la commande, sous forme d'un tableau :
     * [
     *     '--nom' => 'Description de l'option'
     * ]
     * 
     * @var array
     */
    protected array $options = [];
    
    /**
     * Récupère le nom de la commande
     * 
     * @return string Nom de la commande
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * Récupère la description de la commande
     * 
     * @return string Description de la commande
     */
    public function getDescription(): string
    {
        return $this->description;
    }
    
    /**
     * Récupère les arguments attendus par la commande
     * 
     * @return array Liste des arguments et leurs descriptions
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }
    
    /**
     * Récupère les options acceptées par la commande
     * 
     * @return array Liste des options et leurs descriptions
     */
    public function getOptions(): array
    {
        return $this->options;
    }
    
    /**
     * Vérifie si une option est présente dans les arguments passés
     * 
     * Cette méthode utilitaire permet de savoir facilement si une option
     * spécifique a été fournie lors de l'appel de la commande.
     * 
     * @param array $args Arguments de ligne de commande
     * @param string $option Nom de l'option à vérifier (avec ou sans --)
     * @return bool True si l'option est présente, false sinon
     */
    protected function hasOption(array $args, string $option): bool
    {
        // S'assurer que l'option commence par --
        if (strpos($option, '--') !== 0) {
            $option = '--' . $option;
        }
        
        return in_array($option, $args);
    }
    
    /**
     * Récupère la valeur d'une option depuis les arguments
     * 
     * Recherche une option avec une valeur au format "--option=valeur"
     * et retourne la valeur si trouvée.
     * 
     * @param array $args Arguments de ligne de commande
     * @param string $option Nom de l'option (avec ou sans --)
     * @param mixed $default Valeur par défaut si l'option n'est pas trouvée
     * @return mixed Valeur de l'option ou valeur par défaut
     */
    protected function getOptionValue(array $args, string $option, $default = null)
    {
        // S'assurer que l'option commence par --
        if (strpos($option, '--') !== 0) {
            $option = '--' . $option;
        }
        
        // Chercher une option au format --option=valeur
        foreach ($args as $arg) {
            if (strpos($arg, $option . '=') === 0) {
                return substr($arg, strlen($option) + 1);
            }
        }
        
        return $default;
    }
    
    /**
     * Affiche un message dans la console
     * 
     * @param string $message Message à afficher
     * @param string $type Type de message (info, success, warning, error)
     */
    protected function output(string $message, string $type = 'info'): void
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
    
    /**
     * Affiche l'aide de la commande
     * 
     * Cette méthode génère et affiche automatiquement une documentation
     * d'aide basée sur les propriétés de la commande (description, arguments, options).
     */
    public function showHelp(): void
    {
        $this->output("\nCommande: {$this->name}", 'info');
        $this->output("\n{$this->description}\n", 'info');
        
        if (!empty($this->arguments)) {
            $this->output("Arguments:", 'info');
            foreach ($this->arguments as $name => $description) {
                $this->output("  $name\t$description");
            }
            echo PHP_EOL;
        }
        
        if (!empty($this->options)) {
            $this->output("Options:", 'info');
            foreach ($this->options as $name => $description) {
                $this->output("  $name\t$description");
            }
            echo PHP_EOL;
        }
    }
    
    /**
     * Méthode d'exécution principale de la commande
     * 
     * Cette méthode abstraite doit être implémentée par chaque commande concrète.
     * Elle contient la logique principale de la commande et est appelée par le
     * CommandRunner lorsque la commande est invoquée.
     * 
     * @param array $args Arguments passés à la commande
     * @return int Code de retour (0 pour succès, autre pour erreur)
     */
    abstract public function execute(array $args): int;
}