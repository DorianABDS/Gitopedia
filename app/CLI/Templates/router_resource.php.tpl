<?php
/**
 * Module {{MODULE_NAME}} - Définition des routes pour CRUD complet
 * 
 * Ce fichier définit toutes les routes associées au module {{MODULE_NAME}} dans l'architecture HMVC,
 * en implémentant l'ensemble complet des opérations CRUD (Create, Read, Update, Delete).
 * 
 * Il est automatiquement chargé par la classe Application lors de l'initialisation
 * du module, et reçoit les variables suivantes injectées par l'Application :
 * 
 * - $router : L'instance du routeur pour définir les routes
 * - $module : Le nom du module actuel ('{{MODULE_NAME}}' dans ce cas)
 * 
 * L'ensemble des routes définies suit une convention RESTful pour l'accès aux ressources.
 */

// -----------------------------------------------------------------------------
// ROUTES CRUD POUR LES RESSOURCES DU MODULE
// -----------------------------------------------------------------------------

/**
 * Route pour la liste des éléments (READ - Liste)
 * 
 * Cette route répond à l'URL "/{{MODULE_NAME_LOWERCASE}}" et invoque la méthode "index"
 * du contrôleur "{{CONTROLLER_NAME}}".
 */
$router->get('/{{MODULE_NAME_LOWERCASE}}', '{{CONTROLLER_NAME}}@index', $module);

/**
 * Route pour le formulaire de création d'un élément (CREATE - Formulaire)
 * 
 * Cette route répond à l'URL "/{{MODULE_NAME_LOWERCASE}}/create" et invoque la méthode "create"
 * du contrôleur "{{CONTROLLER_NAME}}".
 */
$router->get('/{{MODULE_NAME_LOWERCASE}}/create', '{{CONTROLLER_NAME}}@create', $module);

/**
 * Route pour le traitement du formulaire de création (CREATE - Traitement)
 * 
 * Cette route répond à l'URL "/{{MODULE_NAME_LOWERCASE}}/store" via la méthode POST et
 * invoque la méthode "store" du contrôleur "{{CONTROLLER_NAME}}".
 */
$router->post('/{{MODULE_NAME_LOWERCASE}}/store', '{{CONTROLLER_NAME}}@store', $module);

/**
 * Route pour afficher le détail d'un élément (READ - Détail)
 * 
 * Cette route répond à l'URL "/{{MODULE_NAME_LOWERCASE}}/show/{id}" où {id} est un paramètre dynamique
 * représentant l'identifiant de l'élément à afficher.
 */
$router->get('/{{MODULE_NAME_LOWERCASE}}/show/{id}', '{{CONTROLLER_NAME}}@show', $module);

/**
 * Route pour le formulaire d'édition d'un élément (UPDATE - Formulaire)
 * 
 * Cette route répond à l'URL "/{{MODULE_NAME_LOWERCASE}}/edit/{id}" où {id} est un paramètre dynamique
 * représentant l'identifiant de l'élément à modifier.
 */
$router->get('/{{MODULE_NAME_LOWERCASE}}/edit/{id}', '{{CONTROLLER_NAME}}@edit', $module);

/**
 * Route pour le traitement du formulaire d'édition (UPDATE - Traitement)
 * 
 * Cette route répond à l'URL "/{{MODULE_NAME_LOWERCASE}}/update/{id}" via la méthode POST et
 * invoque la méthode "update" du contrôleur "{{CONTROLLER_NAME}}".
 */
$router->post('/{{MODULE_NAME_LOWERCASE}}/update/{id}', '{{CONTROLLER_NAME}}@update', $module);

/**
 * Route pour la suppression d'un élément (DELETE)
 * 
 * Cette route répond à l'URL "/{{MODULE_NAME_LOWERCASE}}/delete/{id}" et invoque la méthode "delete"
 * du contrôleur "{{CONTROLLER_NAME}}".
 * 
 * Note: Dans une API RESTful pure, cela serait généralement une requête DELETE sur la ressource,
 * mais cette approche simplifie l'utilisation via des liens standard.
 */
$router->get('/{{MODULE_NAME_LOWERCASE}}/delete/{id}', '{{CONTROLLER_NAME}}@delete', $module);

// -----------------------------------------------------------------------------
// ROUTES API
// -----------------------------------------------------------------------------

/**
 * Route API pour récupérer les données au format JSON
 * 
 * Cette route répond à l'URL "/{{MODULE_NAME_LOWERCASE}}/api" et invoque la méthode "api"
 * du contrôleur "{{CONTROLLER_NAME}}".
 */
$router->get('/{{MODULE_NAME_LOWERCASE}}/api', '{{CONTROLLER_NAME}}@api', $module);