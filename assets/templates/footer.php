<!-- 
/**
 * footer.php - Pied de page et fermeture du document HTML
 * 
 * Ce fichier définit la partie inférieure de chaque page HTML et contient :
 * - Le pied de page (footer) avec informations de copyright et liens utiles
 * - L'inclusion des scripts JavaScript (Bootstrap et personnalisés)
 * - Support pour l'ajout de scripts JS spécifiques à certaines pages
 * - Fermeture des balises body et html
 * 
 * Le footer est inclus automatiquement dans toutes les pages via le layout.php
 * principal, garantissant une structure cohérente et une expérience utilisateur unifiée.
 */
-->
        <!-- 
            Pied de page principal de l'application
            Structure en colonnes responsive avec :
            - Description du site
            - Liens de navigation principaux
            - Liens vers les ressources externes
            - Copyright dynamique avec l'année courante
            
            Le fond sombre (bg-dark) et le texte blanc (text-white) créent
            un contraste visuel avec le reste de la page.
        -->
        <footer class="bg-[#303034] text-white mt-5 py-5">
            <div class="container">
                <div class="row mb-4">
                    <!-- Nom et description du site -->
                    <div class="col-md-4">
                        <div class="flex">
                            <img src="assets/img/logoGitopedia.png" alt="Logo Gitopedia" class="mx-3">
                            <h5 class="text-2xl font-bold bg-gradient-to-l from-[#A5A6FF] to-[#6264F0] bg-clip-text text-transparent" style="-webkit-text-fill-color: transparent;">Gitopedia</h5>
                        </div>
                        <p>Une plateforme dédiée à l'apprentissage de Git, du débutant à l'expert, avec des exemples concrets et une approche pédagogique moderne.</p> 
                        <!-- Ou alors cette phrase: "Gitopedia: Parce que git `commit -m 'help'` ne fonctionne pas." -->
                    </div>
                    
                    <!-- liens de navigation ressources -->
                    <div class="col-md-2">
                    <h5 class="text-2xl font-bold bg-gradient-to-l from-[#A5A6FF] to-[#6264F0] bg-clip-text text-transparent" style="-webkit-text-fill-color: transparent;" >Ressources</h5>
                        <ul class="list-unstyled">
                            <li><a href="/" class="text-white !no-underline">Accueil</a></li>
                            <li><a href="/documentation" class="text-white !no-underline">Documentation</a></li>
                            <li><a href="/contact" class="text-white !no-underline">À propos</a></li>
                        </ul>
                    </div>

                    <!-- liens de navigation niveaux -->
                    <div class="col-md-2">
                        <h5 class="text-2xl font-bold bg-gradient-to-l from-[#A5A6FF] to-[#6264F0] bg-clip-text text-transparent" style="-webkit-text-fill-color: transparent;">Niveaux</h5>
                        <ul class="list-unstyled">
                            <li><a href="/debutant" class="text-white !no-underline">Débutant</a></li>
                            <li><a href="/intermediaire" class="text-white !no-underline">Intermédiaire</a></li>
                            <li><a href="/avance" class="text-white !no-underline">Avancé</a></li>
                            <li><a href="/expert" class="text-white !no-underline">Expert</a></li>
                        </ul>
                    </div>

                    <!-- liens de navigation communauté -->
                    <div class="col-md-2">
                        <h5 class="text-2xl font-bold bg-gradient-to-l from-[#A5A6FF] to-[#6264F0] bg-clip-text text-transparent" style="-webkit-text-fill-color: transparent;">Communauté</h5>
                        <ul class="list-unstyled">
                            <li><a href="/github" class="text-white !no-underline">GitHub</a></li>
                            <li><a href="/discord" class="text-white !no-underline">Discord</a></li>
                            <li><a href="/contribuer" class="text-white !no-underline">Contribuer</a></li>
                            <li><a href="/faq" class="text-white !no-underline">FAQ</a></li>
                        </ul>
                    </div>

                    <!-- liens de navigation légal -->
                    <div class="col-md-2">
                        <h5 class="text-2xl font-bold bg-gradient-to-l from-[#A5A6FF] to-[#6264F0] bg-clip-text text-transparent" style="-webkit-text-fill-color: transparent;">Légal</h5>
                        <ul class="list-unstyled">
                            <li><a href="/mentions-legales" class="text-white !no-underline">Mentions légales</a></li>
                            <li><a href="/politique-de-confidentialite" class="text-white !no-underline">Politique de confidentialité</a></li>
                            <li><a href="/conditions-d’utilisation" class="text-white !no-underline">Conditions d’utilisation</a></li>
                            <li><a href="/cookies" class="text-white !no-underline">Cookies</a></li>
                        </ul>
                    </div>
                    
                    <!-- Colonne de droite : ressources et liens externes -->
                    <!-- <div class="col-md-3">
                        <h5>Ressources</h5>
                        <ul class="list-unstyled">
                            <li><a href="/docs" class="text-white">Documentation</a></li>
                            <li><a href="https://github.com/yourusername/gitopedia" class="text-white">GitHub</a></li>
                        </ul>
                    </div> -->

                </div>
                
                <!-- Séparateur horizontal -->
                <hr class="border-0 h-0.5 bg-gradient-to-l from-[#A5A6FF] to-[#6264F0]">
                
                <!-- 
                    Copyright dynamique avec l'année courante
                    L'expression PHP date('Y') insère automatiquement l'année actuelle,
                    évitant ainsi d'avoir à mettre à jour manuellement cette information.
                -->
                <div class="flex place-content-between mt-4">
                    <p class="">&copy; <?= date('Y') ?> Gitopedia. Tous droits réservés.</p>
                    <p class="">Conçu avec ❤️ pour la communauté des développeurs</p>
                </div>
            </div>
        </footer>

        <!-- 
            Tailwind
        -->
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <!-- 
            JavaScript personnalisé de l'application
            Contient les scripts spécifiques au site (validations, interactions, etc.)
        -->
        <script src="/js/app.js"></script>

        <!-- 
            Inclusions JavaScript conditionnelles
            Permet d'ajouter des scripts spécifiques à certaines pages uniquement
            La variable $extraJs peut être définie dans le contrôleur pour inclure
            des scripts additionnels ou du code JavaScript inline
        -->
        <?php if (isset($extraJs)): ?>
            <?= $extraJs ?>
        <?php endif; ?>
    </body>
</html>