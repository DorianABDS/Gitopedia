# Guide du Dossier Services

## Introduction au Pattern Service Layer

Le dossier `Services` implémente le pattern **Service Layer** (Couche de Service), un concept architectural qui définit une couche d'application dédiée à l'orchestration des opérations métier et à la coordination entre différents composants du système.

## Qu'est-ce que le Pattern Service Layer?

Le pattern Service Layer:
- Définit une **couche d'application** qui agit comme intermédiaire entre les contrôleurs et le domaine métier
- **Encapsule la logique métier** complexe qui implique plusieurs entités ou repositories
- **Coordonne** les opérations entre différents composants (entités, repositories, services externes)
- **Traduit** les données entre la couche présentation et la couche domaine

## Structure du Dossier Services

```
Services/
├── User/
│   └── UserService.php
├── Auth/
│   └── AuthenticationService.php
├── Email/
│   └── EmailService.php
└── [AutresServices]/
    └── [ServiceSpecifique].php
```

Cette organisation par domaine fonctionnel permet de regrouper les services liés à un même concept métier.

## Exemple de Service: UserService

Le `UserService` orchestre les opérations liées aux utilisateurs qui impliquent plusieurs étapes ou composants:

```php
namespace App\Services\User;

use App\Domain\User\Repository\UserRepositoryInterface;
use App\Core\Database;
use App\Domain\User\Entity\User;

class UserService
{
    private UserRepositoryInterface $userRepository;
    private Database $db;
    
    public function __construct(UserRepositoryInterface $userRepository, Database $db)
    {
        $this->userRepository = $userRepository;
        $this->db = $db;
    }
    
    public function createUserProfile(array $userData): ?User
    {
        // Validation des données
        if (!$this->validateUserData($userData)) {
            return null;
        }
        
        // Vérification d'unicité
        if ($this->userRepository->findByEmail($userData['email'])) {
            return null; // Email déjà utilisé
        }
        
        // Transaction pour garantir l'intégrité
        try {
            $this->db->beginTransaction();
            
            // Création de l'utilisateur
            $userId = $this->userRepository->create($userData);
            
            if (!$userId) {
                $this->db->rollBack();
                return null;
            }
            
            $this->db->commit();
            
            // Retourner l'entité créée
            return $this->userRepository->findById($userId);
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log('Erreur création profil: ' . $e->getMessage());
            return null;
        }
    }
    
    // Autres méthodes du service...
}
```

Ce service:
1. Valide les données d'entrée
2. Vérifie des contraintes d'unicité
3. Utilise une transaction pour garantir l'intégrité des données
4. Gère les erreurs et journalise les problèmes
5. Retourne une entité de domaine complète

## Différence avec les Autres Couches

Pour bien comprendre le rôle des services, il est important de distinguer:

| Couche | Rôle | Question |
|--------|------|----------|
| **Contrôleurs** | Gèrent les requêtes HTTP et la présentation | "QUOI faire avec cette requête?" |
| **Services** | Orchestrent les opérations métier complexes | "COMMENT réaliser cette opération?" |
| **Repositories** | Accèdent aux données et reconstituent les entités | "OÙ et COMMENT stocker/récupérer les données?" |
| **Entités** | Représentent les concepts métier et leurs règles | "QUOI, quelles sont les propriétés et règles?" |

## Quand Créer un Service?

Créez un service lorsque:

1. Une opération implique **plusieurs entités ou repositories**
2. Une opération nécessite une **transaction** pour maintenir l'intégrité des données
3. Une opération implique une **logique métier complexe** qui ne devrait pas être dans un contrôleur
4. Une opération doit être **réutilisée** dans plusieurs contrôleurs ou modules
5. Une opération dépend de **services externes** (API, envoi d'emails, paiements, etc.)

## Enregistrement et Configuration des Services

Les services sont généralement enregistrés dans le `ServiceContainer` via le fichier `app/services.php`:

```php
// Dans app/services.php
use App\Core\ServiceContainer;

return function() {
    // Enregistrer un service singleton
    ServiceContainer::singleton(UserService::class, function() {
        $userRepository = ServiceContainer::resolve(UserRepositoryInterface::class);
        $db = Application::getInstance()->getDatabase();
        
        return new UserService($userRepository, $db);
    });
    
    // Enregistrer un alias pour une utilisation plus simple
    ServiceContainer::register('user', function() {
        return ServiceContainer::resolve(UserService::class);
    });
};
```

## Utilisation depuis les Contrôleurs

```php
class UserController extends Controller
{
    public function register()
    {
        // Récupérer les données du formulaire
        $userData = [
            'name' => $this->request->post('name'),
            'email' => $this->request->post('email'),
            'password' => $this->request->post('password')
        ];
        
        // Utiliser le service pour créer l'utilisateur
        $userService = $this->getService(UserService::class);
        $user = $userService->createUserProfile($userData);
        
        if ($user) {
            // Rediriger vers la page de succès
            $this->response->redirect('/registration-success');
        } else {
            // Afficher le formulaire avec des erreurs
            return $this->viewWithLayout('register', 'layout', [
                'errors' => ['Impossible de créer le compte utilisateur'],
                'formData' => $userData
            ]);
        }
    }
}
```

## Types Courants de Services

### Services de Domaine
Implémentent la logique métier complexe impliquant plusieurs entités ou repositories:
- `UserService`: Gestion des utilisateurs
- `OrderService`: Traitement des commandes
- `ContentService`: Gestion de contenu

### Services d'Infrastructure
Gèrent l'interaction avec des systèmes externes ou des préoccupations techniques:
- `EmailService`: Envoi d'emails
- `PaymentService`: Traitement des paiements
- `CacheService`: Gestion du cache
- `StorageService`: Gestion du stockage de fichiers

### Services d'Application
Orchestrent le flux de travail global de l'application:
- `AuthenticationService`: Authentification des utilisateurs
- `NotificationService`: Gestion des notifications
- `ReportingService`: Génération de rapports

## Bonnes Pratiques

1. **Injection de dépendances**: Injectez les dépendances via le constructeur pour faciliter les tests

2. **Services sans état**: Concevez vos services pour être sans état entre les appels

3. **Noms significatifs**: Nommez vos services avec des verbes d'action ou des noms décrivant leur fonction

4. **Services ciblés**: Évitez les "services fourre-tout"; chaque service devrait avoir une responsabilité claire

5. **Transactions**: Utilisez des transactions pour les opérations qui modifient plusieurs entités

6. **Gestion d'erreurs**: Gérez correctement les erreurs et évitez de laisser échapper des exceptions non traitées

7. **Journalisation**: Intégrez la journalisation des événements importants et des erreurs

8. **Validation**: Effectuez toujours une validation des données d'entrée avant de les traiter

## Avantages du Pattern Service Layer

- **Séparation des préoccupations**: Isole la logique métier de la logique de présentation
- **Réutilisation du code**: Facilite le partage de la logique métier entre différentes parties de l'application
- **Testabilité améliorée**: Permet de tester la logique métier indépendamment de l'interface utilisateur
- **Maintenabilité**: Organise le code de manière plus structurée et compréhensible
- **Évolutivité**: Facilite l'ajout de nouvelles fonctionnalités sans modifier les contrôleurs ou les entités

## Anti-patterns à Éviter

1. **Anémie des services**: Services qui ne font que déléguer aux repositories sans ajouter de valeur
2. **Services trop gros**: Services qui deviennent des "classes divines" avec trop de responsabilités
3. **Logique métier dans les contrôleurs**: Déplacer la logique métier complexe dans les services
4. **Dépendances circulaires**: Éviter que les services dépendent les uns des autres de manière circulaire
5. **Duplication de code**: Factoriser le code commun à plusieurs services dans des classes utilitaires

---

Les services constituent une couche essentielle de votre application, agissant comme médiateurs entre l'interface utilisateur et le domaine métier. En suivant les principes et pratiques décrits dans ce guide, vous créerez une architecture claire, maintenable et évolutive pour votre application.