<?php
/**
 * Entité User - Pattern Entity du Domain-Driven Design
 * 
 * Cette classe implémente le pattern Entity du Domain-Driven Design (DDD).
 * Dans le DDD, une entité est un objet avec une identité continue dans le temps,
 * même lorsque ses attributs changent.
 * 
 * Une entité est caractérisée par:
 * - Une identité unique qui la distingue des autres objets
 * - Un cycle de vie et un état qui évoluent dans le temps
 * - Des comportements et règles métier intrinsèques
 * 
 * L'entité User répond à la question "QUOI?" :
 * - QUOI est un utilisateur dans notre système?
 * - QUELLES propriétés/attributs possède-t-il?
 * - QUELLES règles métier sont intrinsèques à l'utilisateur?
 * 
 * Cette classe est indépendante de la façon dont l'utilisateur est stocké 
 * (base de données, fichiers, API, etc.) et se concentre uniquement sur la représentation 
 * et le comportement de l'utilisateur en tant qu'objet métier.
 * 
 * Avantages du pattern Entity dans le DDD:
 * - Encapsulation des données et comportements liés aux utilisateurs
 * - Garantie de l'intégrité des données utilisateur via validation
 * - Séparation claire entre les données et leur persistance
 * - Représentation fidèle du modèle métier de l'application
 */

namespace App\Domain\User\Entity;

class User
{
    /**
     * Identifiant unique de l'utilisateur
     * 
     * @var int
     */
    private int $id;
    
    /**
     * Adresse email de l'utilisateur (identifiant de connexion)
     * 
     * @var string
     */
    private string $email;
    
    /**
     * Nom complet de l'utilisateur
     * 
     * @var string
     */
    private string $name;
    
    /**
     * Date de création du compte utilisateur
     * 
     * @var string
     */
    private string $createdAt;

    /**
     * Constructeur pour créer une instance d'utilisateur
     * 
     * Initialise un nouvel utilisateur à partir d'un tableau de données.
     * Cette approche facilite la création d'instances depuis différentes
     * sources (base de données, formulaires, API, etc.)
     * 
     * @param array $data Données d'initialisation de l'utilisateur
     */
    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? 0;
        $this->email = $data['email'] ?? '';
        $this->name = $data['name'] ?? '';
        $this->createdAt = $data['created_at'] ?? date('Y-m-d H:i:s');
    }

    /**
     * Récupère l'identifiant de l'utilisateur
     * 
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * Récupère l'email de l'utilisateur
     * 
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
    
    /**
     * Modifie l'email de l'utilisateur
     * 
     * @param string $email Nouvel email
     * @return self Instance courante pour chaînage
     */
    public function setEmail(string $email): self
    {
        // On pourrait ajouter une validation ici
        $this->email = $email;
        return $this;
    }
    
    /**
     * Récupère le nom de l'utilisateur
     * 
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * Modifie le nom de l'utilisateur
     * 
     * @param string $name Nouveau nom
     * @return self Instance courante pour chaînage
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * Récupère la date de création du compte
     * 
     * @return string Date au format Y-m-d H:i:s
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
    
    /**
     * Convertit l'entité en tableau associatif
     * 
     * Utile pour les opérations de persistance ou pour créer
     * des représentations (JSON, etc.) de l'utilisateur.
     * 
     * @return array Données de l'utilisateur sous forme de tableau
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'created_at' => $this->createdAt
        ];
    }
}