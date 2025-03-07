<!-- 
/**
 * Vue create.php - Formulaire de création d'un élément du module {{MODULE_NAME}}
 * 
 * Cette vue présente un formulaire permettant de créer un nouvel élément
 * pour le module {{MODULE_NAME}}. Elle inclut tous les champs nécessaires
 * avec la validation appropriée.
 * 
 * Dans l'architecture HMVC, cette vue :
 * - Est spécifique au module "{{MODULE_NAME}}"
 * - Ne contient que le contenu central de la page
 * - Est rendue par la méthode create() du {{CONTROLLER_NAME}}
 * - Est intégrée dans le layout global de l'application
 */
-->

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/{{MODULE_NAME_LOWERCASE}}">{{MODULE_NAME}}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Créer un nouvel élément</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Créer un nouvel élément</h3>
                </div>
                <div class="card-body">
                    <form action="/{{MODULE_NAME_LOWERCASE}}/store" method="post">
                        <div class="mb-3">
                            <label for="title" class="form-label">Titre</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                            <div class="form-text">Le titre doit être concis et descriptif.</div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                        </div>
                        <!-- Ajouter d'autres champs selon vos besoins -->
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                        <a href="/{{MODULE_NAME_LOWERCASE}}" class="btn btn-secondary">Annuler</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>