<?php
/**
 * Classe PasswordHasher - Implémentation des patterns Utility Class et Strategy
 * 
 * Cette classe implémente principalement le pattern Utility Class/Helper,
 * qui fournit des fonctionnalités réutilisables via des méthodes statiques
 * sans nécessiter l'instanciation d'objets.
 * 
 * Elle intègre également le pattern Strategy pour le hachage de mots de passe,
 * en encapsulant différents algorithmes de hachage sécurisé derrière une interface
 * cohérente, permettant leur interchangeabilité sans modifier le code client.
 * 
 * La classe utilise aussi le pattern Façade en fournissant une interface simplifiée
 * aux fonctionnalités complexes de hachage de mots de passe de PHP.
 * 
 * Les avantages de cette approche incluent:
 * - Encapsulation de la complexité du hachage de mots de passe
 * - Centralisation des paramètres de sécurité
 * - Standardisation des pratiques de hachage dans l'application
 * - Facilité de mise à jour des algorithmes de hachage
 * - Simplification de l'utilisation des fonctions de sécurité
 * 
 * Le positionnement dans Core/Auth indique qu'il s'agit d'une fonctionnalité
 * fondamentale du framework liée à la sécurité, qui peut être utilisée par
 * différents modules et services.
 */

namespace App\Core\Auth;

class PasswordHasher
{
    /**
     * Coût du hachage de mot de passe
     * 
     * Définit l'effort computationnel pour le hachage.
     * Valeurs plus élevées = plus sécurisé mais plus lent.
     * 
     * @var int
     */
    private static int $hashCost = 12;
    
    /**
     * Hashe un mot de passe de manière sécurisée
     * 
     * Crée un hash sécurisé du mot de passe en utilisant l'algorithme
     * de hachage le plus fort disponible (PASSWORD_DEFAULT).
     * 
     * @param string $password Mot de passe en clair à hasher
     * @return string Hash du mot de passe
     */
    public static function hash(string $password): string
    {
        // Utiliser password_hash avec l'algorithme par défaut
        // qui correspond actuellement à bcrypt (Blowfish)
        return password_hash($password, PASSWORD_DEFAULT, [
            'cost' => self::$hashCost // Niveau de sécurité/coût computationnel
        ]);
    }
    
    /**
     * Vérifie si un mot de passe correspond à un hash
     * 
     * Compare de manière sécurisée un mot de passe en clair avec
     * un hash stocké précédemment.
     * 
     * @param string $password Mot de passe en clair à vérifier
     * @param string $hash Hash stocké pour comparaison
     * @return bool True si le mot de passe correspond, false sinon
     */
    public static function verify(string $password, string $hash): bool
    {
        // Utiliser la fonction native de PHP qui gère automatiquement
        // différents algorithmes de hachage
        return password_verify($password, $hash);
    }
    
    /**
     * Détermine si un hash de mot de passe doit être recalculé
     * 
     * Cette méthode vérifie si un hash existant utilise l'algorithme
     * actuel et les options recommandées. Si ce n'est pas le cas,
     * le hash devrait être mis à jour (rehashé).
     * 
     * Utilisation typique lorsqu'un utilisateur se connecte:
     * if (PasswordHasher::verify($password, $hash)) {
     *     if (PasswordHasher::needsRehash($hash)) {
     *         // Stocker un nouveau hash avec l'algorithme/options actuels
     *         $newHash = PasswordHasher::hash($password);
     *         // Mettre à jour le hash dans la base de données...
     *     }
     *     // Authentification réussie...
     * }
     * 
     * @param string $hash Hash existant à vérifier
     * @return bool True si le hash doit être recalculé
     */
    public static function needsRehash(string $hash): bool
    {
        // Vérifie si le hash utilise l'algorithme actuel avec les options recommandées
        return password_needs_rehash($hash, PASSWORD_DEFAULT, [
            'cost' => self::$hashCost
        ]);
    }