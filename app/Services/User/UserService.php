<?php
/**
 * Classe UserService - Implémentation des patterns Service Layer et Application Service
 * 
 * Cette classe combine plusieurs patterns de conception:
 * 
 * 1. Pattern Service Layer:
 *    Définit une couche de services qui établit une frontière et un ensemble 
 *    d'opérations disponibles autour de l'application. Cette couche coordonne 
 *    les réponses de l'application aux opérations des acteurs externes.
 * 
 * 2. Pattern Application Service (dans le contexte DDD):
 *    Orchestre et coordonne les interactions entre différents objets de domaine
 *    pour réaliser des cas d'utilisation spécifiques de l'application.
 * 
 * 3. Pattern Façade:
 *    Fournit une interface unifiée simplifiée pour un ensemble d'interfaces
 *    plus complexes du système, facilitant l'utilisation du sous-système.
 * 
 * Le service répond à la question "COMMENT FAIRE?" :
 * - COMMENT réaliser un processus métier impliquant plusieurs étapes?
 * - COMMENT coordonner plusieurs entités/repositories pour une opération complexe?
 * - COMMENT implémenter des règles métier qui dépassent une seule entité?
 * 
 * Contrairement aux entités (qui représentent "QUOI") et aux repositories
 * (qui gèrent "OÙ" et "COMMENT STOCKER"), un service définit "COMMENT FAIRE"
 * des opérations métier complètes.
 * 
 * Avantages de l'approche service:
 * - Séparation claire des responsabilités (SRP du SOLID)
 * - Centralisation de la logique métier complexe
 * - Réutilisabilité à travers différents modules HMVC
 * - Facilité de test et de maintenance
 * - Découplage entre la couche de présentation et la logique métier
 */

namespace App\Services\User;

use App\Core\Database;
use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserRepositoryInterface;

class UserService
{
    /**
     * Repository d'accès aux données utilisateur
     * 
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;
    
    /**
     * Instance de base de données pour les transactions
     * 
     * @var Database
     */
    private Database $db;
    
    /**
     * Constructeur du service utilisateur
     * 
     * @param UserRepositoryInterface $userRepository Repository pour l'accès aux données
     * @param Database $db Instance de base de données pour les transactions
     */
    public function __construct(UserRepositoryInterface $userRepository, Database $db)
    {
        $this->userRepository = $userRepository;
        $this->db = $db;
    }
    
    /**
     * Récupère un utilisateur par son ID
     * 
     * Service simple qui délègue au repository.
     * 
     * @param int $id Identifiant de l'utilisateur
     * @return User|null L'utilisateur trouvé ou null
     */
    public function getUserById(int $id): ?User
    {
        return $this->userRepository->findById($id);
    }
    
    /**
     * Crée un profil utilisateur avec validation
     * 
     * Ce service orchestre le processus complet de création d'un utilisateur:
     * 1. Valide les données d'entrée
     * 2. Vérifie l'unicité de l'email
     * 3. Utilise une transaction pour garantir l'intégrité des données
     * 4. Retourne l'entité User créée ou null en cas d'échec
     * 
     * @param array $userData Données de l'utilisateur à créer
     * @return User|null L'utilisateur créé ou null en cas d'échec
     */
    public function createUserProfile(array $userData): ?User
    {
        // Validation basique des données utilisateur
        if (!$this->validateUserData($userData)) {
            return null;
        }
        
        // Vérifier si l'email existe déjà
        if ($this->userRepository->findByEmail($userData['email'])) {
            return null; // Email déjà utilisé
        }
        
        try {
            // Démarrer une transaction pour garantir l'intégrité des données
            $this->db->beginTransaction();
            
            // Créer l'utilisateur via le repository
            $userId = $this->userRepository->create($userData);
            
            if (!$userId) {
                // Échec de création, annuler la transaction
                $this->db->rollBack();
                return null;
            }
            
            // Valider la transaction
            $this->db->commit();
            
            // Récupérer et retourner l'utilisateur complet
            return $this->userRepository->findById($userId);
            
        } catch (\Exception $e) {
            // En cas d'erreur, annuler la transaction et journaliser
            $this->db->rollBack();
            error_log('Erreur lors de la création du profil: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Met à jour le profil d'un utilisateur
     * 
     * Service qui coordonne la mise à jour d'un profil utilisateur
     * avec validation et vérification des données.
     * 
     * @param int $userId ID de l'utilisateur à mettre à jour
     * @param array $userData Nouvelles données utilisateur
     * @return bool Succès ou échec de l'opération
     */
    public function updateUserProfile(int $userId, array $userData): bool
    {
        // Vérifier que l'utilisateur existe
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            return false;
        }
        
        // Vérifier si changement d'email et dans ce cas son unicité
        if (isset($userData['email']) && $userData['email'] !== $user->getEmail()) {
            if ($this->userRepository->findByEmail($userData['email'])) {
                return false; // Email déjà utilisé par un autre utilisateur
            }
        }
        
        // Ajouter la date de mise à jour
        $userData['updated_at'] = date('Y-m-d H:i:s');
        
        // Effectuer la mise à jour via le repository
        return $this->userRepository->update($userId, $userData);
    }
    
    /**
     * Valide les données utilisateur
     * 
     * Méthode privée de validation qui centralise les règles
     * de validation pour les données utilisateur.
     * 
     * @param array $userData Données à valider
     * @return bool Résultat de la validation
     */
    private function validateUserData(array $userData): bool
    {
        // Vérifier la présence des champs obligatoires
        if (empty($userData['email']) || empty($userData['name'])) {
            return false;
        }
        
        // Valider le format de l'email
        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        
        // Vérifier la longueur du nom (minimum 2 caractères)
        if (strlen($userData['name']) < 2) {
            return false;
        }
        
        return true;
    }
}