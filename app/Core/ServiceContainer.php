<?php
/**
 * Classe ServiceContainer - Implémentation des patterns IoC Container et Service Locator
 * 
 * Cette classe implémente deux patterns essentiels de l'architecture moderne:
 * 
 * 1. Inversion of Control (IoC) Container:
 *    Fournit un mécanisme centralisé pour créer et gérer les instances de classes,
 *    en inversant le contrôle de la création d'objets. Au lieu que les classes
 *    créent leurs dépendances, elles les reçoivent du container.
 * 
 * 2. Service Locator:
 *    Agit comme un registre central où les services sont enregistrés et peuvent
 *    être localisés/résolus par leur identifiant. Permet de découpler le code client
 *    de l'implémentation concrète des services.
 * 
 * La classe utilise aussi le pattern Singleton pour ses méthodes statiques,
 * garantissant un point d'accès global unique au container.
 * 
 * Le container de services répond à la question "COMMENT OBTENIR?" :
 * - COMMENT obtenir une instance d'un service sans connaître sa création?
 * - COMMENT gérer les dépendances entre différents services?
 * - COMMENT réutiliser les mêmes instances à travers l'application?
 * 
 * Avantages de ce pattern:
 * - Facilite l'implémentation de l'injection de dépendances
 * - Réduit le couplage entre les composants de l'application
 * - Centralise la gestion du cycle de vie des objets
 * - Simplifie les tests unitaires en permettant l'injection de mocks
 * - Favorise l'adhérence aux principes SOLID, notamment le Dependency Inversion Principle
 * 
 * Cette implémentation reste volontairement simple tout en fournissant
 * les fonctionnalités essentielles d'un container d'injection de dépendances.
 */

namespace App\Core;

class ServiceContainer
{
    /**
     * Services enregistrés sous forme de factories
     * 
     * Ces services sont recréés à chaque résolution, produisant
     * une nouvelle instance à chaque appel de resolve().
     * 
     * @var array
     */
    private static array $services = [];
    
    /**
     * Services enregistrés comme singletons
     * 
     * Ces services sont créés une seule fois puis réutilisés
     * pour toutes les résolutions suivantes.
     * 
     * @var array
     */
    private static array $singletons = [];
    
    /**
     * Enregistre un service comme factory
     * 
     * Les services enregistrés avec cette méthode seront recréés
     * à chaque résolution, produisant des instances distinctes.
     * Utile pour les services qui doivent être uniques à chaque
     * utilisation ou qui dépendent du contexte.
     * 
     * @param string $abstract Identifiant du service (souvent une interface)
     * @param callable $factory Fonction qui crée l'instance du service
     */
    public static function register(string $abstract, callable $factory): void
    {
        self::$services[$abstract] = $factory;
    }
    
    /**
     * Enregistre un service comme singleton
     * 
     * Les services enregistrés avec cette méthode sont créés
     * seulement lors de leur première résolution, puis l'instance
     * est réutilisée pour toutes les résolutions suivantes.
     * Idéal pour les services partagés ou coûteux à créer.
     * 
     * @param string $abstract Identifiant du service (souvent une interface)
     * @param callable $factory Fonction qui crée l'instance du service
     */
    public static function singleton(string $abstract, callable $factory): void
    {
        self::$singletons[$abstract] = [
            'factory' => $factory,
            'instance' => null
        ];
    }
    
    /**
     * Résout un service enregistré
     * 
     * Résout un service à partir de son identifiant en appelant sa factory
     * ou en retournant l'instance singleton existante.
     * 
     * @param string $abstract Identifiant du service à résoudre
     * @return mixed Instance du service résolu
     * @throws \Exception Si le service n'est pas enregistré
     */
    public static function resolve(string $abstract)
    {
        // Vérifier si c'est un singleton
        if (isset(self::$singletons[$abstract])) {
            // Créer l'instance si elle n'existe pas encore
            if (self::$singletons[$abstract]['instance'] === null) {
                self::$singletons[$abstract]['instance'] = call_user_func(
                    self::$singletons[$abstract]['factory']
                );
            }
            
            // Retourner l'instance existante
            return self::$singletons[$abstract]['instance'];
        }
        
        // Vérifier si c'est un service standard
        if (isset(self::$services[$abstract])) {
            // Créer et retourner une nouvelle instance
            return call_user_func(self::$services[$abstract]);
        }
        
        // Service non trouvé
        throw new \Exception("Service non enregistré: {$abstract}");
    }
    
    /**
     * Vérifie si un service est enregistré
     * 
     * @param string $abstract Identifiant du service à vérifier
     * @return bool True si le service est enregistré
     */
    public static function has(string $abstract): bool
    {
        return isset(self::$services[$abstract]) || isset(self::$singletons[$abstract]);
    }
    
    /**
     * Définit directement une instance singleton
     * 
     * Permet d'enregistrer une instance déjà créée comme singleton.
     * Utile pour les tests ou pour définir des valeurs prédéfinies.
     * 
     * @param string $abstract Identifiant du service
     * @param mixed $instance Instance du service
     */
    public static function instance(string $abstract, $instance): void
    {
        self::$singletons[$abstract] = [
            'factory' => function() use ($instance) { return $instance; },
            'instance' => $instance
        ];
    }
}