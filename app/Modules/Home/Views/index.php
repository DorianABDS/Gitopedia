<!-- 
/**
 * Vue home.php - Page d'accueil du site
 * 
 * Cette vue définit le contenu principal de la page d'accueil.
 * Elle sera intégrée dans le layout principal par le contrôleur.
 * 
 * Dans l'architecture HMVC, cette vue :
 * - Est spécifique au module "Home"
 * - Définit uniquement le contenu central de la page
 * - Est rendue par la méthode index() du HomeController
 * - Est intégrée dans le layout.php qui fournit la structure HTML complète
 * 
 * Toute variable définie dans le contrôleur et passée via le tableau $data
 * est disponible directement dans cette vue (comme $title, si définie).
 */
-->

<section class="col-span-12 pt-40 h-screen">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-12 gap-6 items-center">
        <div class="col-span-12 md:col-span-6 text-center md:text-left">
            <h1 class="text-4xl md:text-6xl font-bold mb-6 bg-gradient-to-l from-[#A5A6FF] to-[#6264F0] bg-clip-text text-transparent">Apprenez Git de façon Intuitive</h1>
            <p class="text-lg text-gray-300 mb-8">Progressez grâce à une documentation complète et des tutoriels adaptés, du niveau débutant à expert.</p>
            <div class="flex flex-col md:flex-row gap-4 justify-center md:justify-start">
                <a href="/documentation" class="bg-gradient-to-l from-[#A5A6FF] to-[#6264F0] text-[#161618] font-bold py-3 px-6 rounded-md">Explorer les tutoriels</a>
                <a href="/a-propos" class="bg-[#303034] text-white font-bold py-3 px-6 rounded-md">Consulter la documentation</a>
            </div>
        </div>
        <div class="col-span-12 md:col-span-6 flex justify-end">
            <img src="assets/img/logoGitopedia.png" alt="Logo Gitopedia" class="w-[500px] h-auto" />
        </div>
    </div>
</section>