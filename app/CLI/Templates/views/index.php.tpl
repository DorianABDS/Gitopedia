<!-- 
/**
 * Vue index.php - Page d'accueil du module {{MODULE_NAME}}
 * 
 * Cette vue représente la page principale du module {{MODULE_NAME}}.
 * Elle présente une vue d'ensemble des fonctionnalités et données
 * disponibles dans ce module.
 * 
 * Dans l'architecture HMVC, cette vue :
 * - Est spécifique au module "{{MODULE_NAME}}"
 * - Ne contient que le contenu central de la page
 * - Est rendue par la méthode index() du {{CONTROLLER_NAME}}
 * - Est intégrée dans le layout global de l'application
 */
-->

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h1>Module {{MODULE_NAME}}</h1>
            <p class="lead">Bienvenue dans le module {{MODULE_NAME}} !</p>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Liste des éléments</h5>
                    <p class="card-text">Accédez à la liste complète des éléments disponibles.</p>
                    <a href="/{{MODULE_NAME_LOWERCASE}}/list" class="btn btn-primary">Voir la liste</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Créer un nouvel élément</h5>
                    <p class="card-text">Ajoutez un nouvel élément à la base de données.</p>
                    <a href="/{{MODULE_NAME_LOWERCASE}}/create" class="btn btn-success">Créer</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    API du module
                </div>
                <div class="card-body">
                    <h5 class="card-title">Accès API</h5>
                    <p class="card-text">Le module dispose également d'une API pour accéder aux données programmatiquement.</p>
                    <code>GET /{{MODULE_NAME_LOWERCASE}}/api</code>
                </div>
            </div>
        </div>
    </div>
</div>