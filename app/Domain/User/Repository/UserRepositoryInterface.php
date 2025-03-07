<?php
/**
 * Interface UserRepositoryInterface - Pattern Repository du Domain-Driven Design
 * 
 * Cette interface implémente le pattern Repository du Domain-Driven Design (DDD).
 * Le Repository est un médiateur entre le domaine et les couches de données,
 * qui offre une interface orientée objet pour accéder et manipuler les données.
 * 
 * Dans le DDD, un repository:
 * - Agit comme une collection en mémoire d'entités
 * - Encapsule la logique de requêtage et de persistance
 * - Fournit une abstraction sur l'infrastructure de stockage
 * - Permet de reconstituer des objets de domaine complets
 * 
 * Le repository répond à la question "OÙ?" et "COMMENT STOCKER/RÉCUPÉRER?" :
 * - OÙ sont stockées les données utilisateur?
 * - COMMENT récupérer un utilisateur par son ID ou son email?
 * - COMMENT persister un nouvel utilisateur ou mettre à jour ses données?
 * 
 * Cette interface définit un contrat qui doit être respecté par toute 
 * implémentation concrète, indépendamment de la technologie de stockage 
 * sous-jacente (MySQL, MongoDB, API externe, etc.)
 * 
 * Avantages du pattern Repository:
 * - Découplage entre la logique métier et la persistance des données
 * - Facilité pour changer l'implémentation (différentes bases de données)
 * - Simplification des tests unitaires via des mocks/stubs
 * - Centralisation de la logique d'accès aux données
 * - Réduction de la duplication de code
 * 
 * Dans un framework HMVC, les repositories peuvent être utilisés par plusieurs
 * modules, offrant un accès cohérent aux données à travers l'application.
 */

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\User;

interface UserRepositoryInterface
{
    /**
     * Trouve un utilisateur par son identifiant unique
     * 
     * @param int $id Identifiant de l'utilisateur
     * @return User|null L'utilisateur trouvé ou null si non trouvé
     */
    public function findById(int $id): ?User;
    
    /**
     * Trouve un utilisateur par son adresse email
     * 
     * @param string $email Email de l'utilisateur
     * @return User|null L'utilisateur trouvé ou null si non trouvé
     */
    public function findByEmail(string $email): ?User;
    
    /**
     * Sauvegarde un nouvel utilisateur dans le stockage
     * 
     * @param array $userData Données de l'utilisateur à créer
     * @return int|null ID du nouvel utilisateur ou null en cas d'échec
     */
    public function create(array $userData): ?int;
    
    /**
     * Met à jour les données d'un utilisateur existant
     * 
     * @param int $id Identifiant de l'utilisateur
     * @param array $userData Nouvelles données à appliquer
     * @return bool Succès ou échec de l'opération
     */
    public function update(int $id, array $userData): bool;
}