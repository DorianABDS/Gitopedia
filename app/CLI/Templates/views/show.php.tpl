<!-- 
/**
 * Vue show.php - Page de détail d'un élément du module {{MODULE_NAME}}
 * 
 * Cette vue affiche les détails complets d'un élément spécifique
 * identifié par son ID. Elle présente toutes les informations
 * relatives à cet élément de manière structurée.
 * 
 * Dans l'architecture HMVC, cette vue :
 * - Est spécifique au module "{{MODULE_NAME}}"
 * - Ne contient que le contenu central de la page
 * - Est rendue par la méthode show() du {{CONTROLLER_NAME}}
 * - Est intégrée dans le layout global de l'application
 * - Reçoit les données de l'élément via la variable $item
 */
-->

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/{{MODULE_NAME_LOWERCASE}}">{{MODULE_NAME}}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $item['title'] ?></li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3><?= $item['title'] ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5>Description</h5>
                            <p><?= $item['description'] ?></p>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">Informations</div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">ID: <?= $item['id'] ?></li>
                                    <!-- Ajouter d'autres propriétés ici selon vos besoins -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="/{{MODULE_NAME_LOWERCASE}}/edit/<?= $item['id'] ?>" class="btn btn-primary">Modifier</a>
                    <a href="/{{MODULE_NAME_LOWERCASE}}/delete/<?= $item['id'] ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')">Supprimer</a>
                    <a href="/{{MODULE_NAME_LOWERCASE}}" class="btn btn-secondary">Retour à la liste</a>
                </div>
            </div>
        </div>
    </div>
</div>