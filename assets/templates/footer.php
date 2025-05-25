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
<footer class="col-span-12 bg-[#303034] text-white pt-10 pb-5">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid gap-12 grid-cols-1 md:grid-cols-12 mb-20">
            <div class="md:col-span-4 flex flex-col items-center md:items-start text-center md:text-left">
                <a href="/">
                    <div class="flex items-center mb-2 transition-all duration-200 transform hover:scale-[1.03] active:scale-[0.95]">
                        <img src="assets/img/logoGit.png" alt="Logo Git" class="mx-3" />
                        <h5 class="text-2xl font-bold bg-gradient-to-l from-[#A5A6FF] to-[#6264F0] bg-clip-text text-transparent">Gitopedia</h5>
                    </div>
                </a>
                <p>Parce que git <code>`commit -m 'help'`</code> ne fonctionne pas.</p>
            </div>

            <div class="md:col-span-8 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
                <div class="flex flex-col gap-2">
                    <h5 class="text-xl font-bold bg-gradient-to-l from-[#A5A6FF] to-[#6264F0] bg-clip-text text-transparent">Ressources</h5>
                    <ul class="space-y-2">
                        <li><a href="/" class="hover:text-transparent hover:bg-gradient-to-l hover:from-[#A5A6FF] hover:to-[#6264F0] hover:bg-clip-text hover:underline">Accueil</a></li>
                        <li><a href="/news" class="hover:text-transparent hover:bg-gradient-to-l hover:from-[#A5A6FF] hover:to-[#6264F0] hover:bg-clip-text hover:underline">Actualité</a></li>
                        <li><a href="/documentation" class="hover:text-transparent hover:bg-gradient-to-l hover:from-[#A5A6FF] hover:to-[#6264F0] hover:bg-clip-text hover:underline">Documentation</a></li>
                        <li><a href="/contact" class="hover:text-transparent hover:bg-gradient-to-l hover:from-[#A5A6FF] hover:to-[#6264F0] hover:bg-clip-text hover:underline">À propos</a></li>
                    </ul>
                </div>

                <div class="flex flex-col gap-2">
                    <h5 class="text-xl font-bold bg-gradient-to-l from-[#A5A6FF] to-[#6264F0] bg-clip-text text-transparent">Niveaux</h5>
                    <ul class="space-y-2">
                        <li><a href="/debutant" class="hover:text-transparent hover:bg-gradient-to-l hover:from-[#A5A6FF] hover:to-[#6264F0] hover:bg-clip-text hover:underline">Débutant</a></li>
                        <li><a href="/intermediaire" class="hover:text-transparent hover:bg-gradient-to-l hover:from-[#A5A6FF] hover:to-[#6264F0] hover:bg-clip-text hover:underline">Intermédiaire</a></li>
                        <li><a href="/avance" class="hover:text-transparent hover:bg-gradient-to-l hover:from-[#A5A6FF] hover:to-[#6264F0] hover:bg-clip-text hover:underline">Avancé</a></li>
                        <li><a href="/expert" class="hover:text-transparent hover:bg-gradient-to-l hover:from-[#A5A6FF] hover:to-[#6264F0] hover:bg-clip-text hover:underline">Expert</a></li>
                    </ul>
                </div>

                <div class="flex flex-col gap-2">
                    <h5 class="text-xl font-bold bg-gradient-to-l from-[#A5A6FF] to-[#6264F0] bg-clip-text text-transparent">Communauté</h5>
                    <ul class="space-y-2">
                        <li><a href="/github" class="hover:text-transparent hover:bg-gradient-to-l hover:from-[#A5A6FF] hover:to-[#6264F0] hover:bg-clip-text hover:underline">GitHub</a></li>
                        <li><a href="/discord" class="hover:text-transparent hover:bg-gradient-to-l hover:from-[#A5A6FF] hover:to-[#6264F0] hover:bg-clip-text hover:underline">Discord</a></li>
                        <li><a href="/contribute" class="hover:text-transparent hover:bg-gradient-to-l hover:from-[#A5A6FF] hover:to-[#6264F0] hover:bg-clip-text hover:underline">Contribuer</a></li>
                        <li><a href="/faq" class="hover:text-transparent hover:bg-gradient-to-l hover:from-[#A5A6FF] hover:to-[#6264F0] hover:bg-clip-text hover:underline">FAQ</a></li>
                    </ul>
                </div>

                <div class="flex flex-col gap-2">
                    <h5 class="text-xl font-bold bg-gradient-to-l from-[#A5A6FF] to-[#6264F0] bg-clip-text text-transparent">Liens</h5>
                    <ul class="space-y-2">
                        <li><a href="/legal" class="hover:text-transparent hover:bg-gradient-to-l hover:from-[#A5A6FF] hover:to-[#6264F0] hover:bg-clip-text hover:underline">Mentions légales</a></li>
                        <li><a href="/terms" class="hover:text-transparent hover:bg-gradient-to-l hover:from-[#A5A6FF] hover:to-[#6264F0] hover:bg-clip-text hover:underline">Conditions d'utilisation</a></li>
                        <li><a href="/privacy" class="hover:text-transparent hover:bg-gradient-to-l hover:from-[#A5A6FF] hover:to-[#6264F0] hover:bg-clip-text hover:underline">Politique de confidentialité</a></li>
                        <li><a href="/cookies" class="hover:text-transparent hover:bg-gradient-to-l hover:from-[#A5A6FF] hover:to-[#6264F0] hover:bg-clip-text hover:underline">Cookies</a></li>
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
</div>

<script src="/js/app.js"></script>

<!-- 
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