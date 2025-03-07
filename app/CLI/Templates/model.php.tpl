<?php
/**
 * {{MODEL_NAME}} - Modèle de données pour le module {{MODULE_NAME}}
 * 
 * Ce modèle représente la structure de données et la logique métier
 * associée aux entités du module {{MODULE_NAME}}. Il encapsule toutes les
 * opérations CRUD (Create, Read, Update, Delete) et les règles de validation.
 * 
 * Dans l'architecture HMVC, ce modèle :
 * - Interagit directement avec la base de données
 * - Applique les règles de validation des données
 * - Implémente la logique métier spécifique aux entités
 * - Fournit une interface claire pour les contrôleurs
 */

namespace App\Modules\{{MODULE_NAME}}\Models;

use App\Core\Model;
use App\Core\Database;

class {{MODEL_NAME}} extends Model
{
    /**
     * Nom de la table associée dans la base de données
     * 
     * Par convention, nous utilisons le nom du module en minuscules et au pluriel.
     * 
     * @var string
     */
    protected string $table = '{{TABLE_NAME}}';
    
    /**
     * Champs autorisés pour l'assignation de masse
     * 
     * Cette propriété définit les champs qui peuvent être remplis en masse
     * via des méthodes comme create() ou fill().
     * 
     * @var array
     */
    protected array $fillable = [
        'title',
        'description',
        'created_at',
        'updated_at'
    ];
    
    /**
     * Règles de validation des données
     * 
     * Ces règles sont utilisées pour valider les données avant
     * leur insertion ou mise à jour dans la base de données.
     * 
     * @var array
     */
    protected array $validationRules = [
        'title' => 'required|max:255',
        'description' => 'required'
    ];
    
    /**
     * Constructeur - Initialise le modèle avec la connexion à la base de données
     * 
     * @param Database $db Instance de la connexion à la base de données
     */
    public function __construct(Database $db)
    {
        parent::__construct($db);
    }
    
    /**
     * Exemple de méthode personnalisée pour rechercher des éléments
     * 
     * Cette méthode illustre comment étendre la fonctionnalité de base
     * du modèle avec des méthodes spécifiques à ce module.
     * 
     * @param string $keyword Mot-clé à rechercher
     * @return array Liste des éléments correspondants
     */
    public function search(string $keyword): array
    {
        $keyword = '%' . $keyword . '%';
        
        $query = "SELECT * FROM {$this->table} WHERE title LIKE :keyword OR description LIKE :keyword ORDER BY created_at DESC";
        $params = [':keyword' => $keyword];
        
        $result = $this->db->query($query, $params);
        return $result ?: [];
    }
    
    /**
     * Récupère les éléments les plus récents
     * 
     * @param int $limit Nombre maximum d'éléments à récupérer
     * @return array Liste des éléments récents
     */
    public function getRecent(int $limit = 5): array
    {
        $query = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :limit";
        $params = [':limit' => $limit];
        
        $result = $this->db->query($query, $params);
        return $result ?: [];
    }
    
    /**
     * Exemple de validation des données avant création ou mise à jour
     * 
     * @param array $data Données à valider
     * @return bool True si les données sont valides, false sinon
     */
    public function validate(array $data): bool
    {
        // Cette méthode est un exemple simplifié.
        // Dans une implémentation réelle, vous utiliseriez un système de validation plus robuste.
        
        // Vérifier les champs requis
        if (empty($data['title']) || empty($data['description'])) {
            return false;
        }
        
        // Vérifier la longueur du titre
        if (strlen($data['title']) > 255) {
            return false;
        }
        
        return true;
    }
}