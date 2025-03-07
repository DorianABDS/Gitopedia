<?php
/**
 * {{CONTROLLER_NAME}} - Contrôleur complet CRUD du module {{MODULE_NAME}}
 * 
 * Ce contrôleur implémente toutes les actions CRUD (Create, Read, Update, Delete)
 * pour le module {{MODULE_NAME}}. Il offre une gestion complète des ressources
 * en suivant les principes RESTful.
 * 
 * Dans l'architecture HMVC, ce contrôleur :
 * - Reçoit les requêtes HTTP via le routeur
 * - Interagit avec les modèles pour manipuler les données
 * - Prépare les données pour l'affichage
 * - Sélectionne et rend les vues appropriées pour chaque action
 */

namespace App\Modules\{{MODULE_NAME}}\Controllers;

use App\Core\Controller;
use App\Modules\{{MODULE_NAME}}\Models\{{MODULE_NAME}}Model;

class {{CONTROLLER_NAME}} extends Controller
{
    /**
     * Instance du modèle
     * 
     * @var {{MODULE_NAME}}Model
     */
    private {{MODULE_NAME}}Model $model;
    
    /**
     * Constructeur - Initialise le contrôleur avec ses dépendances
     * 
     * @param \App\Core\Application $app
     */
    public function __construct(\App\Core\Application $app)
    {
        parent::__construct($app);
        
        // Initialiser le modèle
        $this->model = new {{MODULE_NAME}}Model($this->db);
    }
    
    /**
     * Affiche la liste des éléments (READ - Liste)
     * 
     * Cette méthode récupère tous les éléments disponibles et les affiche
     * sous forme de liste avec options de filtrage et pagination.
     * 
     * @return string Le HTML de la page de liste
     */
    public function index(): string
    {
        // Récupérer tous les éléments via le modèle
        $items = $this->model->all();
        
        // Rendre la vue avec les données
        return $this->viewWithLayout('index', 'layout', [
            'title' => '{{MODULE_NAME}} - Liste des éléments',
            'items' => $items
        ]);
    }
    
    /**
     * Affiche le détail d'un élément spécifique (READ - Détail)
     * 
     * Cette méthode récupère un élément par son ID et affiche
     * toutes ses informations de manière détaillée.
     * 
     * @param int $id Identifiant de l'élément à afficher
     * @return string Le HTML de la page de détail
     */
    public function show(int $id): string
    {
        // Récupérer l'élément par son ID
        $item = $this->model->find($id);
        
        // Vérifier si l'élément existe
        if (!$item) {
            // Élément non trouvé - Afficher une page 404
            $this->response->setStatusCode(404);
            return $this->viewWithLayout('error', 'layout', [
                'title' => 'Élément non trouvé',
                'message' => "L'élément demandé n'existe pas ou a été supprimé."
            ]);
        }
        
        // Rendre la vue avec les données de l'élément
        return $this->viewWithLayout('show', 'layout', [
            'title' => $item['title'] . ' - {{MODULE_NAME}}',
            'item' => $item
        ]);
    }
    
    /**
     * Affiche le formulaire de création d'un élément (CREATE - Formulaire)
     * 
     * Cette méthode présente un formulaire vide permettant à l'utilisateur
     * de créer un nouvel élément.
     * 
     * @return string Le HTML du formulaire de création
     */
    public function create(): string
    {
        // Rendre la vue du formulaire de création
        return $this->viewWithLayout('create', 'layout', [
            'title' => 'Créer un nouvel élément - {{MODULE_NAME}}'
        ]);
    }
    
    /**
     * Traite la soumission du formulaire de création (CREATE - Traitement)
     * 
     * Cette méthode récupère les données soumises, les valide, crée
     * un nouvel élément et redirige l'utilisateur.
     */
    public function store(): void
    {
        // Récupérer les données du formulaire
        $data = [
            'title' => $this->request->post('title'),
            'description' => $this->request->post('description'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // Valider les données
        if (!$this->model->validate($data)) {
            // Rediriger vers le formulaire avec un message d'erreur
            // (Dans une implémentation réelle, vous voudriez préserver les données saisies)
            $this->response->redirect('/{{MODULE_NAME_LOWERCASE}}/create');
            return;
        }
        
        // Créer l'élément dans la base de données
        $newId = $this->model->create($data);
        
        // Rediriger vers la page de détail du nouvel élément
        $this->response->redirect('/{{MODULE_NAME_LOWERCASE}}/show/' . $newId);
    }
    
    /**
     * Affiche le formulaire d'édition d'un élément (UPDATE - Formulaire)
     * 
     * Cette méthode récupère un élément existant et affiche un formulaire
     * pré-rempli avec ses données actuelles.
     * 
     * @param int $id Identifiant de l'élément à modifier
     * @return string Le HTML du formulaire d'édition
     */
    public function edit(int $id): string
    {
        // Récupérer l'élément par son ID
        $item = $this->model->find($id);
        
        // Vérifier si l'élément existe
        if (!$item) {
            // Élément non trouvé - Afficher une page 404
            $this->response->setStatusCode(404);
            return $this->viewWithLayout('error', 'layout', [
                'title' => 'Élément non trouvé',
                'message' => "L'élément que vous souhaitez modifier n'existe pas ou a été supprimé."
            ]);
        }
        
        // Rendre la vue du formulaire d'édition avec les données de l'élément
        return $this->viewWithLayout('edit', 'layout', [
            'title' => 'Modifier ' . $item['title'] . ' - {{MODULE_NAME}}',
            'item' => $item
        ]);
    }
    
    /**
     * Traite la soumission du formulaire d'édition (UPDATE - Traitement)
     * 
     * Cette méthode récupère les données soumises, les valide, met à jour
     * l'élément existant et redirige l'utilisateur.
     * 
     * @param int $id Identifiant de l'élément à mettre à jour
     */
    public function update(int $id): void
    {
        // Récupérer l'élément existant
        $item = $this->model->find($id);
        
        // Vérifier si l'élément existe
        if (!$item) {
            // Élément non trouvé - Rediriger vers la liste avec un message d'erreur
            $this->response->redirect('/{{MODULE_NAME_LOWERCASE}}');
            return;
        }
        
        // Récupérer les données du formulaire
        $data = [
            'title' => $this->request->post('title'),
            'description' => $this->request->post('description'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Valider les données
        if (!$this->model->validate($data)) {
            // Rediriger vers le formulaire d'édition avec un message d'erreur
            $this->response->redirect('/{{MODULE_NAME_LOWERCASE}}/edit/' . $id);
            return;
        }
        
        // Mettre à jour l'élément dans la base de données
        $success = $this->model->update($id, $data);
        
        // Rediriger vers la page de détail de l'élément mis à jour
        $this->response->redirect('/{{MODULE_NAME_LOWERCASE}}/show/' . $id);
    }
    
    /**
     * Supprime un élément existant (DELETE)
     * 
     * Cette méthode supprime un élément de la base de données
     * et redirige l'utilisateur vers la liste des éléments.
     * 
     * @param int $id Identifiant de l'élément à supprimer
     */
    public function delete(int $id): void
    {
        // Récupérer l'élément existant
        $item = $this->model->find($id);
        
        // Vérifier si l'élément existe
        if (!$item) {
            // Élément non trouvé - Rediriger vers la liste avec un message d'erreur
            $this->response->redirect('/{{MODULE_NAME_LOWERCASE}}');
            return;
        }
        
        // Supprimer l'élément de la base de données
        $success = $this->model->delete($id);
        
        // Rediriger vers la liste des éléments
        $this->response->redirect('/{{MODULE_NAME_LOWERCASE}}');
    }
    
    /**
     * Point d'entrée API pour les données (API JSON)
     * 
     * Cette méthode retourne les données au format JSON pour
     * consommation par des clients API.
     */
    public function api(): void
    {
        // Récupérer tous les éléments via le modèle
        $items = $this->model->all();
        
        // Préparer les données pour la réponse JSON
        $data = [
            'module' => '{{MODULE_NAME}}',
            'status' => 'success',
            'count' => count($items),
            'items' => $items
        ];
        
        // Retourner les données au format JSON
        $this->response->json($data);
    }
}