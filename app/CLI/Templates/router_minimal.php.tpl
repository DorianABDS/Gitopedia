<?php
/**
 * Module {{MODULE_NAME}} - Définition minimale des routes
 * 
 * Ce fichier définit les routes minimales associées au module {{MODULE_NAME}}.
 * Il est automatiquement chargé par la classe Application lors de l'initialisation
 * du module, et reçoit les variables suivantes injectées par l'Application :
 * 
 * - $router : L'instance du routeur pour définir les routes
 * - $module : Le nom du module actuel ('{{MODULE_NAME}}' dans ce cas)
 */

// -----------------------------------------------------------------------------
// ROUTE PRINCIPALE DU MODULE
// -----------------------------------------------------------------------------

/**
 * Route pour la page principale du module
 * 
 * Cette route répond à l'URL "/{{MODULE_NAME_LOWERCASE}}" et invoque la méthode "index"
 * du contrôleur "{{CONTROLLER_NAME}}".
 */
$router->get('/{{MODULE_NAME_LOWERCASE}}', '{{CONTROLLER_NAME}}@index', $module);