<?php
/**
 * {{MODEL_NAME}} - Modèle de données complet pour le module {{MODULE_NAME}}
 * 
 * Ce modèle représente la structure de données et la logique métier
 * associée aux entités du module {{MODULE_NAME}}. Il encapsule toutes les
 * opérations CRUD (Create, Read, Update, Delete), les règles de validation,
 * et des méthodes avancées pour la gestion des relations et des requêtes complexes.
 * 
 * Dans l'architecture HMVC, ce modèle :
 * - Interagit directement avec la base de données
 * - Applique les règles de validation des données
 * - Implémente la logique métier spécifique aux entités
 * - Gère les relations avec d'autres modèles
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
        'status',
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
        'description' => 'required',
        'status' => 'in:draft,published,archived'
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
     * Trouve les éléments actifs (publiés)
     * 
     * Cette méthode étend les capacités de base du modèle en fournissant
     * un accès facile aux éléments ayant un statut "published".
     * 
     * @return array Liste des éléments publiés
     */
    public function findPublished(): array
    {
        return $this->where(['status' => 'published']);
    }
    
    /**
     * Recherche des éléments par mot-clé
     * 
     * Cette méthode permet de rechercher des éléments dont le titre ou
     * la description contient le mot-clé spécifié.
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
     * @param string $status Statut des éléments à récupérer (all, published, draft, archived)
     * @return array Liste des éléments récents
     */
    public function getRecent(int $limit = 5, string $status = 'all'): array
    {
        $query = "SELECT * FROM {$this->table}";
        $params = [];
        
        // Ajouter la condition de statut si spécifiée
        if ($status !== 'all') {
            $query .= " WHERE status = :status";
            $params[':status'] = $status;
        }
        
        // Ajouter l'ordre et la limite
        $query .= " ORDER BY created_at DESC LIMIT :limit";
        $params[':limit'] = $limit;
        
        $result = $this->db->query($query, $params);
        return $result ?: [];
    }
    
    /**
     * Met à jour le statut d'un élément
     * 
     * Cette méthode utilitaire facilite le changement de statut d'un élément
     * sans avoir à mettre à jour l'ensemble des données.
     * 
     * @param int $id Identifiant de l'élément
     * @param string $status Nouveau statut (draft, published, archived)
     * @return bool Succès de l'opération
     */
    public function updateStatus(int $id, string $status): bool
    {
        // Vérifier que le statut est valide
        $validStatuses = ['draft', 'published', 'archived'];
        if (!in_array($status, $validStatuses)) {
            return false;
        }
        
        $data = [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->update($id, $data);
    }
    
    /**
     * Récupère des statistiques sur les éléments
     * 
     * Cette méthode avancée retourne un tableau de statistiques
     * concernant les éléments dans la base de données.
     * 
     * @return array Statistiques (total, par statut, etc.)
     */
    public function getStats(): array
    {
        // Compter le nombre total d'éléments
        $total = $this->count();
        
        // Compter par statut
        $published = $this->count(['status' => 'published']);
        $draft = $this->count(['status' => 'draft']);
        $archived = $this->count(['status' => 'archived']);
        
        // Récupérer les éléments les plus récents
        $recent = $this->getRecent(5);
        
        return [
            'total' => $total,
            'published' => $published,
            'draft' => $draft,
            'archived' => $archived,
            'recent_count' => count($recent)
        ];
    }
    
    /**
     * Valide les données selon les règles définies
     * 
     * Cette méthode vérifie que toutes les données fournies respectent
     * les règles de validation définies pour ce modèle.
     * 
     * @param array $data Données à valider
     * @return bool True si les données sont valides, false sinon
     */
    public function validate(array $data): bool
    {
        // Vérifier les champs requis
        if (isset($this->validationRules['title']) && 
            strpos($this->validationRules['title'], 'required') !== false && 
            empty($data['title'])) {
            return false;
        }
        
        if (isset($this->validationRules['description']) && 
            strpos($this->validationRules['description'], 'required') !== false && 
            empty($data['description'])) {
            return false;
        }
        
        // Vérifier la longueur du titre
        if (isset($data['title']) && 
            isset($this->validationRules['title']) && 
            preg_match('/max:(\d+)/', $this->validationRules['title'], $matches)) {
            $maxLength = (int)$matches[1];
            if (strlen($data['title']) > $maxLength) {
                return false;
            }
        }
        
        // Vérifier les valeurs d'énumération (status)
        if (isset($data['status']) && 
            isset($this->validationRules['status']) && 
            preg_match('/in:([^|]+)/', $this->validationRules['status'], $matches)) {
            $allowedValues = explode(',', $matches[1]);
            if (!in_array($data['status'], $allowedValues)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Ajoute ou supprime une étiquette (tag) pour cet élément
     * 
     * Cette méthode illustre comment gérer des relations plusieurs-à-plusieurs
     * entre les entités du modèle et d'autres entités (ici les tags).
     * 
     * @param int $itemId Identifiant de l'élément
     * @param int $tagId Identifiant du tag
     * @param bool $add True pour ajouter, false pour supprimer
     * @return bool Succès de l'opération
     */
    public function manageTag(int $itemId, int $tagId, bool $add = true): bool
    {
        // Nom de la table de jonction (table pivot)
        $pivotTable = '{{TABLE_NAME}}_tags';
        
        // Vérifier si l'élément existe
        $item = $this->find($itemId);
        if (!$item) {
            return false;
        }
        
        if ($add) {
            // Vérifier si la relation existe déjà
            $query = "SELECT COUNT(*) as count FROM $pivotTable WHERE item_id = :item_id AND tag_id = :tag_id";
            $params = [':item_id' => $itemId, ':tag_id' => $tagId];
            $result = $this->db->query($query, $params);
            
            if ($result && $result[0]['count'] > 0) {
                // La relation existe déjà
                return true;
            }
            
            // Ajouter la relation
            $query = "INSERT INTO $pivotTable (item_id, tag_id, created_at) VALUES (:item_id, :tag_id, :created_at)";
            $params = [
                ':item_id' => $itemId,
                ':tag_id' => $tagId,
                ':created_at' => date('Y-m-d H:i:s')
            ];
            
            return $this->db->execute($query, $params);
        } else {
            // Supprimer la relation
            $query = "DELETE FROM $pivotTable WHERE item_id = :item_id AND tag_id = :tag_id";
            $params = [':item_id' => $itemId, ':tag_id' => $tagId];
            
            return $this->db->execute($query, $params);
        }
    }
    
    /**
     * Récupère les tags associés à un élément
     * 
     * Cette méthode illustre comment récupérer des données liées
     * dans une relation plusieurs-à-plusieurs.
     * 
     * @param int $itemId Identifiant de l'élément
     * @return array Liste des tags associés
     */
    public function getTags(int $itemId): array
    {
        // Nom de la table de jonction et de la table des tags
        $pivotTable = '{{TABLE_NAME}}_tags';
        $tagsTable = 'tags';
        
        $query = "SELECT t.* FROM $tagsTable t
                  JOIN $pivotTable pt ON t.id = pt.tag_id
                  WHERE pt.item_id = :item_id
                  ORDER BY t.name";
                  
        $params = [':item_id' => $itemId];
        
        $result = $this->db->query($query, $params);
        return $result ?: [];
    }
}