<?php
/**
 * Module {{MODULE_NAME}} - Définition des routes
 * 
 * Ce fichier définit toutes les routes associées au module {{MODULE_NAME}} dans l'architecture HMVC.
 * Il est automatiquement chargé par la classe Application lors de l'initialisation
 * du module, et reçoit les variables suivantes injectées par l'Application :
 * 
 * - $router : L'instance du routeur pour définir les routes
 * - $module : Le nom du module actuel ('{{MODULE_NAME}}' dans ce cas)
 * 
 * Chaque route associe un pattern d'URL à une méthode spécifique du contrôleur,
 * permettant ainsi d'organiser clairement la structure de navigation du module.
 */

// -----------------------------------------------------------------------------
// ROUTES PRINCIPALES DU MODULE
// -----------------------------------------------------------------------------

/**
 * Route pour la page d'accueil du module
 * 
 * Cette route répond à l'URL "/{{MODULE_NAME_LOWERCASE}}" et invoque la méthode "index"
 * du contrôleur "{{CONTROLLER_NAME}}".
 */
$router->get('/{{MODULE_NAME_LOWERCASE}}', '{{CONTROLLER_NAME}}@index', $module);

/**
 * Route pour afficher un élément spécifique
 * 
 * Cette route répond à l'URL "/{{MODULE_NAME_LOWERCASE}}/show/{id}" où {id} est un paramètre dynamique
 * représentant l'identifiant de l'élément à afficher.
 */
$router->get('/{{MODULE_NAME_LOWERCASE}}/show/{id}', '{{CONTROLLER_NAME}}@show', $module);

/**
 * Route API pour récupérer des données au format JSON
 * 
 * Cette route illustre la création d'un point d'API simple retournant des données
 * au format JSON plutôt qu'une page HTML.
 */
$router->get('/{{MODULE_NAME_LOWERCASE}}/api', '{{CONTROLLER_NAME}}@api', $module);

// -----------------------------------------------------------------------------
// ROUTES ADDITIONNELLES
// -----------------------------------------------------------------------------

/**
 * Vous pouvez ajouter ici d'autres routes selon vos besoins :
 * 
 * // Route pour créer un nouvel élément (formulaire)
 * $router->get('/{{MODULE_NAME_LOWERCASE}}/create', '{{CONTROLLER_NAME}}@create', $module);
 * 
 * // Route pour enregistrer un nouvel élément (traitement du formulaire)
 * $router->post('/{{MODULE_NAME_LOWERCASE}}/store', '{{CONTROLLER_NAME}}@store', $module);
 * 
 * // Route pour éditer un élément existant (formulaire)
 * $router->get('/{{MODULE_NAME_LOWERCASE}}/edit/{id}', '{{CONTROLLER_NAME}}@edit', $module);
 * 
 * // Route pour mettre à jour un élément existant (traitement du formulaire)
 * $router->post('/{{MODULE_NAME_LOWERCASE}}/update/{id}', '{{CONTROLLER_NAME}}@update', $module);
 * 
 * // Route pour supprimer un élément
 * $router->get('/{{MODULE_NAME_LOWERCASE}}/delete/{id}', '{{CONTROLLER_NAME}}@delete', $module);
 */