<?php
/**
 * {{CONTROLLER_NAME}} - Contrôleur principal du module {{MODULE_NAME}}
 * 
 * Ce contrôleur gère les fonctionnalités principales du module {{MODULE_NAME}}.
 * Il implémente les actions de base et les différentes vues associées.
 * 
 * Dans l'architecture HMVC, ce contrôleur :
 * - Reçoit les requêtes HTTP via le routeur
 * - Interagit avec les modèles pour manipuler les données
 * - Prépare les données pour l'affichage
 * - Sélectionne et rend les vues appropriées
 */

namespace App\Modules\{{MODULE_NAME}}\Controllers;

use App\Core\Controller;

class {{CONTROLLER_NAME}} extends Controller
{
    /**
     * Affiche la page d'accueil du module
     * 
     * Cette méthode est appelée lorsque l'utilisateur accède à la route
     * principale du module. Elle affiche une vue d'ensemble des fonctionnalités
     * et données disponibles dans le module.
     * 
     * @return string Le HTML de la page d'accueil du module
     */
    public function index(): string
    {
        // Utiliser la méthode viewWithLayout pour générer la page complète
        return $this->viewWithLayout('index', 'layout', [
            'title' => '{{MODULE_NAME}} - Accueil'
        ]);
    }
    
    /**
     * Affiche le détail d'un élément
     * 
     * Cette méthode récupère et affiche les informations détaillées
     * d'un élément spécifique identifié par son ID.
     * 
     * @param int $id Identifiant de l'élément à afficher
     * @return string Le HTML de la page de détail
     */
    public function show(int $id): string
    {
        // Simuler la récupération de données (à remplacer par un vrai modèle)
        $item = [
            'id' => $id,
            'title' => 'Élément #' . $id,
            'description' => 'Description de l\'élément #' . $id
        ];
        
        // Créer le layout avec titre personnalisé
        $layout = $this->createLayout("$item[title] - {{MODULE_NAME}}");
        
        // Générer le contenu avec les données
        $content = $this->view('show', [
            'item' => $item
        ]);
        
        // Configurer le layout avec le contenu généré
        $layout->setContent($content);
        
        return $layout->render();
    }
    
    /**
     * Exemple de méthode supplémentaire
     * 
     * Cette méthode peut être adaptée selon les besoins spécifiques du module.
     * Elle illustre l'utilisation d'un retour JSON pour une API.
     */
    public function api(): void
    {
        // Exemple de données à retourner en JSON
        $data = [
            'module' => '{{MODULE_NAME}}',
            'status' => 'active',
            'items_count' => 42
        ];
        
        // Retourner les données au format JSON
        $this->response->json($data);
    }
}