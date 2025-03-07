<?php
/**
 * {{CONTROLLER_NAME}} - Contrôleur minimal du module {{MODULE_NAME}}
 * 
 * Ce contrôleur minimaliste implémente uniquement les fonctionnalités
 * de base nécessaires au module {{MODULE_NAME}}.
 * 
 * Dans l'architecture HMVC, ce contrôleur :
 * - Reçoit les requêtes HTTP via le routeur
 * - Prépare les données pour l'affichage
 * - Rend la vue appropriée
 */

namespace App\Modules\{{MODULE_NAME}}\Controllers;

use App\Core\Controller;

class {{CONTROLLER_NAME}} extends Controller
{
    /**
     * Affiche la page principale du module
     * 
     * Cette méthode est appelée lorsque l'utilisateur accède à la route
     * principale du module.
     * 
     * @return string Le HTML de la page d'accueil du module
     */
    public function index(): string
    {
        // Utiliser la méthode viewWithLayout pour générer la page complète
        return $this->viewWithLayout('index', 'layout', [
            'title' => '{{MODULE_NAME}}'
        ]);
    }
}