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
<footer class="bg-[#303034] flex justify-center text-white mt-5 pt-10 pb-5 px-4 sm:px-6 lg:px-8">
    <div class="container">
        <div class="grid gap-12 grid-cols-1 md:grid-cols-12 mb-20">
            <div class="md:col-span-4 flex flex-col items-center md:items-start text-center md:text-left">
                <div class="flex items-center mb-2">
                    <img src="assets/img/logoGitpedia.png" alt="Logo Git" class="mx-3" />
                    <h5 class="text-2xl font-bold bg-gradient-to-l from-[#A5A6FF] to-[#6264F0] bg-clip-text text-transparent">Gitopedia</h5>
                </div>
                <p>Parce que git <code>`commit -m 'help'`</code> ne fonctionne pas.</p>
            </div>

            <div class="md:col-span-8 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
                <div class="flex flex-col gap-2">
                    <h5 class="text-xl font-bold bg-gradient-to-l from-[#A5A6FF] to-[#6264F0] bg-clip-text text-transparent">Ressources</h5>
                    <ul class="space-y-2">
                        <li><a href="/" class="text-white no-underline hover:underline">Accueil</a></li>
                        <li><a href="/actualite" class="text-white no-underline hover:underline">Actualité</a></li>
                        <li><a href="/documentation" class="text-white no-underline hover:underline">Documentation</a></li>
                        <li><a href="/contact" class="text-white no-underline hover:underline">À propos</a></li>
                    </ul>
                </div>

                <div class="flex flex-col gap-2">
                    <h5 class="text-xl font-bold bg-gradient-to-l from-[#A5A6FF] to-[#6264F0] bg-clip-text text-transparent">Niveaux</h5>
                    <ul class="space-y-2">
                        <li><a href="/debutant" class="text-white no-underline hover:underline">Débutant</a></li>
                        <li><a href="/intermediaire" class="text-white no-underline hover:underline">Intermédiaire</a></li>
                        <li><a href="/avance" class="text-white no-underline hover:underline">Avancé</a></li>
                        <li><a href="/expert" class="text-white no-underline hover:underline">Expert</a></li>
                    </ul>
                </div>

                <div class="flex flex-col gap-2">
                    <h5 class="text-xl font-bold bg-gradient-to-l from-[#A5A6FF] to-[#6264F0] bg-clip-text text-transparent">Communauté</h5>
                    <ul class="space-y-2">
                        <li><a href="/github" class="text-white no-underline hover:underline">GitHub</a></li>
                        <li><a href="/discord" class="text-white no-underline hover:underline">Discord</a></li>
                        <li><a href="/contribuer" class="text-white no-underline hover:underline">Contribuer</a></li>
                        <li><a href="/faq" class="text-white no-underline hover:underline">FAQ</a></li>
                    </ul>
                </div>

                <div class="flex flex-col gap-2">
                    <h5 class="text-xl font-bold bg-gradient-to-l from-[#A5A6FF] to-[#6264F0] bg-clip-text text-transparent">Légal</h5>
                    <ul class="space-y-2">
                        <li><a href="/mentions-legales" class="text-white no-underline hover:underline">Mentions légales</a></li>
                        <li><a href="/politique-de-confidentialite" class="text-white no-underline hover:underline">Politique de confidentialité</a></li>
                        <li><a href="/conditions-d’utilisation" class="text-white no-underline hover:underline">Conditions d’utilisation</a></li>
                        <li><a href="/cookies" class="text-white no-underline hover:underline">Cookies</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <hr class="border-0 h-0.5 bg-gradient-to-l from-[#A5A6FF] to-[#6264F0]" />

        <div class="flex flex-col md:flex-row justify-between items-center mt-5 text-sm text-center md:text-left">
            <p>&copy; <?= date('Y') ?> Gitopedia. Tous droits réservés.</p>
            <p>Conçu avec ❤️ pour la communauté des développeurs</p>
        </div>
    </div>
</footer>

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