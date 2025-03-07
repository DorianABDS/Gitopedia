<?php
/**
 * Classe UserRepository - Implémentation du pattern Repository du DDD
 * 
 * Cette classe implémente le pattern Repository du Domain-Driven Design (DDD),
 * fournissant une implémentation concrète pour l'accès et la manipulation
 * des données utilisateur via le modèle de base du framework.
 * 
 * Selon les principes du DDD, un Repository:
 * - Maintient l'illusion d'une collection en mémoire d'entités de domaine
 * - Isole le domaine métier des détails techniques de persistance
 * - Traduit entre le format de stockage et les objets du domaine
 * - Cache les détails complexes des requêtes sous une interface simple
 * 
 * Cette implémentation répond concrètement aux questions "OÙ?" et "COMMENT?":
 * - Les données sont stockées dans une table SQL 'users'
 * - On récupère un utilisateur via des requêtes SELECT
 * - On crée/modifie un utilisateur via des requêtes INSERT/UPDATE
 * 
 * Le repository masque tous les détails d'accès aux données derrière une
 * interface orientée objet, protégeant ainsi la logique métier des
 * changements dans la couche de persistance.
 * 
 * Cette classe utilise également le pattern Adapter pour faire correspondre
 * le modèle du framework à l'interface du repository définie dans le DDD.
 */

namespace App\Domain\User\Repository;

use App\Core\Database;
use App\Core\Model;
use App\Domain\User\Entity\User;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Modèle sous-jacent pour les opérations de base de données
     * 
     * Utilise le Model du framework HMVC existant pour bénéficier
     * des fonctionnalités CRUD déjà implémentées.
     * 
     * @var Model
     */
    private Model $userModel;
    
    /**
     * Constructeur du repository
     * 
     * @param Database $db Instance de connexion à la base de données
     */
    public function __construct(Database $db)
    {
        // Utiliser une classe anonyme étendant Model pour définir
        // le modèle spécifique aux utilisateurs
        $this->userModel = new class($db) extends Model {
            protected string $table = 'users';
            protected string $primaryKey = 'id';
        };
    }
    
    /**
     * Trouve un utilisateur par son identifiant
     * 
     * Récupère les données brutes via le modèle puis les transforme
     * en objet de domaine User.
     * 
     * @param int $id Identifiant de l'utilisateur
     * @return User|null Entité utilisateur ou null si non trouvé
     */
    public function findById(int $id): ?User
    {
        // Récupérer les données utilisateur depuis la base de données
        $userData = $this->userModel->find($id);
        
        // Convertir les données en entité User ou retourner null
        return $userData ? new User($userData) : null;
    }
    
    /**
     * Trouve un utilisateur par son email
     * 
     * Utilise une requête conditionnelle pour trouver l'utilisateur
     * correspondant à l'email spécifié.
     * 
     * @param string $email Email à rechercher
     * @return User|null Entité utilisateur ou null si non trouvé
     */
    public function findByEmail(string $email): ?User
    {
        // Rechercher par email (valeur unique dans la base)
        $userData = $this->userModel->firstWhere(['email' => $email]);
        
        // Convertir en entité User ou retourner null
        return $userData ? new User($userData) : null;
    }
    
    /**
     * Crée un nouvel utilisateur dans la base de données
     * 
     * @param array $userData Données de l'utilisateur à créer
     * @return int|null ID du nouvel utilisateur ou null en cas d'échec
     */
    public function create(array $userData): ?int
    {
        // Utiliser la méthode create() du modèle pour insérer l'utilisateur
        // Cette méthode retourne l'ID généré ou null en cas d'échec
        return $this->userModel->create($userData);
    }
    
    /**
     * Met à jour les données d'un utilisateur existant
     * 
     * @param int $id Identifiant de l'utilisateur à mettre à jour
     * @param array $userData Nouvelles données à appliquer
     * @return bool Succès ou échec de l'opération
     */
    public function update(int $id, array $userData): bool
    {
        // Utiliser la méthode update() du modèle qui retourne un booléen
        // indiquant le succès ou l'échec de l'opération
        return $this->userModel->update($id, $userData);
    }
}