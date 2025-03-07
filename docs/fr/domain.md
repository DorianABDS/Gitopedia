# Guide du Dossier Domain

## Introduction au Domain-Driven Design (DDD)

Le dossier `Domain` est l'implémentation du concept de Domain-Driven Design (DDD), une approche de développement logiciel qui se concentre sur la modélisation du domaine métier et sa logique.

## Qu'est-ce que le DDD?

Le Domain-Driven Design est une méthodologie de développement qui:
- Place le domaine métier au centre de la conception du logiciel
- Utilise un "langage omniprésent" partagé entre développeurs et experts métier
- Isole la logique métier des préoccupations techniques
- Organise un système complexe en sous-domaines cohérents

## Structure du Dossier Domain

```
Domain/
├── User/
│   ├── Entity/
│   │   └── User.php
│   └── Repository/
│       ├── UserRepository.php
│       └── UserRepositoryInterface.php
└── [AutresDomaines]/
    ├── Entity/
    └── Repository/
```

## Concepts Clés du DDD

### Les Entités (Entities)

Une **entité** est un objet qui possède une identité unique qui persiste à travers le temps, même si ses attributs changent.

Exemple (`User.php`):
```php
namespace App\Domain\User\Entity;

class User
{
    private int $id;
    private string $email;
    private string $name;
    
    // Getters, setters et comportements métier
}
```

Les entités:
- Sont définies par leur identité (leur ID)
- Encapsulent des données ET des comportements
- Contiennent les règles métier liées à ces données
- Sont indépendantes de la façon dont elles sont stockées

### Les Repositories

Un **repository** agit comme une collection en mémoire d'entités. Il masque les détails de l'accès aux données derrière une interface orientée objet.

Exemple (`UserRepositoryInterface.php`):
```php
namespace App\Domain\User\Repository;

use App\Domain\User\Entity\User;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;
    public function findByEmail(string $email): ?User;
    public function save(User $user): void;
}
```

Les repositories:
- Fournissent une abstraction sur le stockage des données
- Isolent le domaine des détails d'implémentation de la persistance
- Permettent de manipuler les entités sans connaître comment elles sont stockées
- Facilitent les tests unitaires grâce à la possibilité de les "mocker"

## Avantages et Cas d'Utilisation

### Quand utiliser le DDD:
- Pour des applications avec une logique métier complexe
- Lorsque le domaine est central dans l'application
- Pour des projets à long terme qui vont évoluer
- Quand la collaboration avec des experts métier est cruciale

### Avantages:
- Modélisation plus fidèle de la réalité métier
- Meilleure communication entre développeurs et experts métier
- Évolution plus facile face aux changements de règles métier
- Séparation claire entre logique métier et infrastructure technique

## Bonnes Pratiques

1. **Identifiez les limites de contexte**: Divisez votre domaine en sous-domaines cohérents
2. **Utilisez le langage métier**: Nommez vos classes, méthodes et propriétés selon les termes métier
3. **Gardez les entités pures**: Évitez les dépendances sur l'infrastructure (DB, frameworks)
4. **Créez des repositories centrés sur l'agrégat**: Un repository par racine d'agrégat
5. **Validez les entités**: Assurez l'intégrité des données dans les entités elles-mêmes

## Comment Interagir avec le Dossier Domain

Voici comment interagir avec la couche de domaine depuis un contrôleur:

```php
class UserController extends Controller
{
    public function profile($id)
    {
        // Récupérer le service utilisateur depuis le container
        $userService = $this->getService(UserService::class);
        
        // Récupérer l'utilisateur via le service
        $user = $userService->getUserById($id);
        
        // Rendre la vue avec les données de l'entité
        return $this->view('profile', ['user' => $user]);
    }
}
```

## Pour Aller Plus Loin

- **Agrégats**: Groupes d'entités que vous traitez comme une unité cohésive
- **Value Objects**: Objets définis par leurs attributs plutôt que par leur identité
- **Services de domaine**: Encapsulent la logique qui ne convient pas naturellement aux entités
- **Événements de domaine**: Communiquent des changements importants dans le domaine

---

En respectant ces principes, vous créerez un code plus expressif, plus maintenable et mieux aligné avec les besoins métier de votre application.