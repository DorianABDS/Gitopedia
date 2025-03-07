# Gitopedia CLI - Guide d'utilisation

<img src="https://img.shields.io/badge/Gitopedia-CLI-blue?style=for-the-badge&logo=terminal&logoColor=white" alt="Gitopedia CLI" />

Le framework Gitopedia inclut un puissant outil en ligne de commande qui permet d'automatiser diverses tâches de développement, telles que la génération de code, la gestion des modules, et d'autres opérations de maintenance.

> **💡 Astuce :** Utilisez le CLI pour gagner du temps lors du développement. L'automatisation de la génération de code vous permet d'éviter les erreurs et de maintenir une structure cohérente dans votre application.

## Installation

Le script CLI est déjà inclus à la racine de votre projet sous le nom `gitopedia`. Pour l'utiliser, rendez-le exécutable :

```bash
chmod +x gitopedia
```

Vous pouvez ensuite l'exécuter directement :

```bash
./gitopedia <commande> [options] [arguments]
```

Ou via PHP :

```bash
php gitopedia <commande> [options] [arguments]
```

> **ℹ️ Note :** Assurez-vous que les droits d'exécution sont correctement définis pour le fichier `gitopedia` avant de l'utiliser.

## Commandes Disponibles

### Afficher l'aide générale

Pour voir la liste complète des commandes disponibles :

```bash
./gitopedia help
```

### Afficher l'aide d'une commande spécifique

Pour voir des informations détaillées sur une commande particulière :

```bash
./gitopedia help <nom-commande>
```

### Créer un nouveau module

La commande `make:module` permet de générer rapidement un nouveau module HMVC complet avec sa structure de base.

Syntaxe :
```bash
./gitopedia make:module <NomModule> [options]
```

Options disponibles :
- `--force` : Écrase le module s'il existe déjà
- `--model` : Inclut un modèle de base
- `--controller=<Nom>` : Spécifie un nom personnalisé pour le contrôleur (par défaut: \<NomModule\>Controller)
- `--resource` : Génère un module avec des actions CRUD complètes (Create, Read, Update, Delete)
- `--minimal` : Génère un module minimaliste avec seulement l'essentiel (une seule route et une vue)

> **⚠️ Attention :** Les options `--resource` et `--minimal` sont mutuellement exclusives et ne peuvent pas être utilisées ensemble.

#### Exemples d'utilisation

```bash
# Créer un module Blog simple
./gitopedia make:module Blog

# Créer un module User avec un modèle
./gitopedia make:module User --model

# Créer un module Product avec CRUD complet
./gitopedia make:module Product --resource

# Créer un module Admin avec un contrôleur personnalisé
./gitopedia make:module Admin --controller=DashboardController

# Remplacer un module existant
./gitopedia make:module Blog --force --resource

# Créer un module minimaliste
./gitopedia make:module Widget --minimal
```

## Structure générée

Lorsque vous créez un nouveau module, la commande génère une structure adaptée selon les options choisies.

### Structure standard
```
app/Modules/NomModule/
├── Controllers/
│   └── NomModuleController.php  (ou nom personnalisé)
├── Models/
│   └── NomModuleModel.php       (si --model est spécifié)
├── Views/
│   ├── index.php
│   └── show.php
└── router.php
```

### Structure complète (avec l'option --resource)
```
app/Modules/NomModule/
├── Controllers/
│   └── NomModuleController.php  (version CRUD complète)
├── Models/
│   └── NomModuleModel.php       (version avancée avec relations)
├── Views/
│   ├── index.php
│   ├── show.php
│   ├── create.php
│   └── edit.php
└── router.php                   (routes CRUD complètes)
```

### Structure minimale (avec l'option --minimal)
```
app/Modules/NomModule/
├── Controllers/
│   └── NomModuleController.php  (version minimale)
├── Views/
│   └── index.php                (version minimale)
└── router.php                   (route unique)
```

> **🎯 Bon à savoir :** Choisissez la structure qui correspond le mieux à vos besoins. Utilisez `--minimal` pour des modules simples, la version standard pour des modules moyennement complexes, et `--resource` pour des modules nécessitant une gestion complète de données.

## Personnalisation des templates

Les templates utilisés pour générer les fichiers se trouvent dans le répertoire `app/CLI/Templates/`. Vous pouvez les modifier pour adapter le code généré à vos besoins spécifiques.

### Liste des templates disponibles

Templates disponibles :
- `controller.php.tpl` : Template du contrôleur de base
- `controller_resource.php.tpl` : Template du contrôleur avec CRUD complet
- `controller_minimal.php.tpl` : Template du contrôleur minimaliste
- `router.php.tpl` : Template des routes de base
- `router_resource.php.tpl` : Template des routes pour CRUD complet
- `router_minimal.php.tpl` : Template des routes minimales
- `model.php.tpl` : Template du modèle de données de base
- `model_resource.php.tpl` : Template du modèle de données avancé avec relations et méthodes supplémentaires
- `views/index.php.tpl` : Template de la vue liste
- `views/index_minimal.php.tpl` : Template de la vue minimale
- `views/show.php.tpl` : Template de la vue détail
- `views/create.php.tpl` : Template de la vue création
- `views/edit.php.tpl` : Template de la vue édition

> **💡 Personnalisation :** Vous pouvez personnaliser ces templates en y ajoutant votre propre code, styles ou commentaires. Les balises comme `{{MODULE_NAME}}` seront automatiquement remplacées par les valeurs appropriées lors de la génération.

## Variables de remplacement dans les templates

Lors de la génération de fichiers, les balises suivantes sont remplacées par leurs valeurs correspondantes :

| Balise | Description |
|--------|-------------|
| `{{MODULE_NAME}}` | Nom du module (ex: Blog, User) |
| `{{MODULE_NAME_LOWERCASE}}` | Nom du module en minuscules (ex: blog, user) |
| `{{CONTROLLER_NAME}}` | Nom du contrôleur (ex: BlogController) |
| `{{MODEL_NAME}}` | Nom du modèle (ex: BlogModel) |
| `{{TABLE_NAME}}` | Nom de la table en base de données (ex: blogs) |
| `{{YEAR}}` | Année courante |
| `{{DATE}}` | Date courante (format Y-m-d) |

## Création de nouvelles commandes CLI

Pour ajouter vos propres commandes, créez une nouvelle classe dans le répertoire `app/CLI/` qui étend la classe `App\CLI\Command`. Suivez le modèle de `MakeModule.php` pour définir votre commande.

> **⚙️ Extension :** Le système de commandes est conçu pour être facilement extensible. Chaque commande est une classe PHP distincte qui peut être ajoutée au système sans modifier le code existant.

### Exemple de création d'une commande personnalisée

```php
<?php
namespace App\CLI;

class MaCommande extends Command
{
    protected string $name = 'ma:commande';
    protected string $description = 'Description de ma commande';
    
    protected array $arguments = [
        'argument1' => 'Description de l\'argument 1'
    ];
    
    protected array $options = [
        '--option1' => 'Description de l\'option 1'
    ];
    
    public function execute(array $args): int
    {
        // Logique de votre commande ici
        $this->output('Ma commande exécutée avec succès !', 'success');
        return 0; // 0 indique un succès
    }
}
```

La commande sera automatiquement disponible via le CLI sans aucune configuration supplémentaire.

## Bonnes pratiques

- **Nommage cohérent** : Utilisez la convention PascalCase pour les noms de modules (ex: `Blog`, `UserAccount`).
- **Tests après génération** : Vérifiez toujours le code généré pour vous assurer qu'il répond à vos besoins.
- **Personnalisation progressive** : Commencez par générer un module standard, puis personnalisez-le selon vos besoins spécifiques.
- **Versionnez vos templates** : Si vous modifiez les templates, assurez-vous de les inclure dans votre gestionnaire de versions.

> **❌ À éviter :** Ne modifiez pas les fichiers générés dans le répertoire `app/CLI/`, car vos modifications seraient écrasées lors des mises à jour du framework.

> **✅ Recommandé :** Créez vos propres templates personnalisés en copiant et modifiant les templates existants.

## Dépannage

### Problèmes courants et solutions

| Problème | Solution |
|----------|----------|
| `Permission denied` lors de l'exécution | Exécutez `chmod +x gitopedia` pour rendre le fichier exécutable |
| Template introuvable | Vérifiez que le template existe dans `app/CLI/Templates/` |
| Erreur lors de la création d'un module | Assurez-vous que le répertoire `app/Modules/` est accessible en écriture |

> **🔧 Support :** Si vous rencontrez des problèmes persistants, consultez la documentation complète ou ouvrez une issue sur le dépôt GitHub du projet.