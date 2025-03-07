<?php
/**
 * Fichier d'initialisation des services
 * 
 * Ce fichier centralise l'enregistrement de tous les services de l'application
 * dans le container d'injection de dépendances. Il est chargé au démarrage
 * de l'application, avant le traitement des requêtes.
 * 
 * Il définit:
 * - Les liaisons entre interfaces et implémentations concrètes
 * - Les services singleton (partagés dans toute l'application)
 * - Les dépendances entre services
 * 
 * L'avantage de centraliser ces définitions est de:
 * - Avoir une vue d'ensemble des services disponibles
 * - Faciliter la modification des implémentations concrètes
 * - Rendre explicites les dépendances entre services
 */

use App\Core\ServiceContainer;
use App\Core\Application;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Services\User\UserService;

/**
 * Enregistrement des services dans le container
 */
return function() {
    // -----------------------------------------------------------------------
    // Service Repository
    // -----------------------------------------------------------------------
    
    // Liaison interface-implémentation pour le repository utilisateur
    ServiceContainer::singleton(UserRepositoryInterface::class, function() {
        // Récupérer une instance de Database depuis l'Application
        $app = Application::getInstance();
        $db = $app->getDatabase();
        
        // Créer et retourner le repository
        return new UserRepository($db);
    });
    
    // -----------------------------------------------------------------------
    // Services métier
    // -----------------------------------------------------------------------
    
    // Service utilisateur (avec ses dépendances)
    ServiceContainer::singleton(UserService::class, function() {
        // Récupérer une instance de Database depuis l'Application
        $app = Application::getInstance();
        $db = $app->getDatabase();
        
        // Résoudre les dépendances du service
        $userRepository = ServiceContainer::resolve(UserRepositoryInterface::class);
        
        // Créer et retourner le service
        return new UserService($userRepository, $db);
    });
    
    // -----------------------------------------------------------------------
    // Alias de services
    // -----------------------------------------------------------------------
    
    // Enregistrer des alias pour un accès plus simple
    ServiceContainer::register('user', function() {
        return ServiceContainer::resolve(UserService::class);
    });
};