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
            <h1 class="text-4xl md:text-6xl font-bold mb-6 bg-gradient-to-l from-[#A5A6FF] to-[#6264F0] bg-clip-text text-transparent">
                Apprenez Git de façon Intuitive
            </h1>
            <p class="text-lg text-gray-300 mb-8">
                Progressez grâce à une documentation complète et des tutoriels adaptés, du niveau débutant à expert.
            </p>
            <div class="flex flex-col md:flex-row gap-4 justify-center md:justify-start">
                <a href="/documentation" class="bg-gradient-to-l from-[#A5A6FF] to-[#6264F0] text-[#161618] font-bold py-3 px-6 rounded-md">
                    Explorer les tutoriels
                </a>
                <a href="/a-propos" class="bg-[#303034] text-white font-bold py-3 px-6 rounded-md">
                    Consulter la documentation
                </a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Fonctionnalité 2</h5>
                    <p class="card-text">Description de la deuxième fonctionnalité...</p>
                    <a href="/feature2" class="btn btn-primary">En savoir plus</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contenu principal sous l'en-tête -->
<main class="col-span-12">
    <!-- Section avec titre et sous-titre -->
    <section>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <!-- Titre de la section -->
            <div class="text-center mb-12">
                <h2 class="text-3xl font-semibold text-white">Articles les plus populaires</h2>
                <p class="text-xl text-gray-400 mt-2">Découvrez les contenus préférés de notre communauté</p>
            </div>

            <!-- Conteneur pour la carte -->
            <div class="flex justify-center">
                <div class="w-96 flex flex-col items-center">
                    <!-- Carte -->
                    <div class="bg-gray-800 rounded-xl overflow-hidden shadow-[8px_8px_0_0_rgba(28,28,28,1)] w-full">
                        <div class="p-6 flex flex-col h-full bg-[#303034]">
                            <!-- Icône + badge niveau -->
                            <div class="flex items-center mb-4">
                                <div class="bg-[#22C55E] p-4 rounded-lg">
                                    <svg class="w-8 h-8 text-[#fff]" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 font-semibold bg-[#D1EEE3] text-[#22C55E] text-sm px-3 py-1 rounded-full">
                                    Débutant
                                </div>
                            </div>

                            <!-- Titre de la carte -->
                            <h2 class="text-xl font-bold text-white mb-3">
                                Premiers pas avec Git : installation et configuration
                            </h2>

                            <!-- Description -->
                            <p class="text-base text-[#A3A3A3] mb-5 flex-grow">
                                Découvrez comment installer Git sur votre système d'exploitation et le configurer pour une utilisation optimale.
                            </p>

                            <!-- Tags -->
                            <div class="flex flex-wrap gap-2 mb-5">
                                <span class="bg-[#D4D1EE] text-[#6264F0] font-semibold px-3 py-1 rounded-full text-sm">Configuration</span>
                                <span class="bg-[#D4D1EE] text-[#6264F0] font-semibold px-3 py-1 rounded-full text-sm">Installation</span>
                            </div>

                            <!-- Pied de carte -->
                            <div class="flex justify-between items-center mt-auto">
                                <a href="#" class="bg-gradient-to-l from-[#A5A6FF] to-[#6264F0] bg-clip-text text-transparent font-semibold flex items-center">
                                    Lire le tutoriel
                                </a>
                                <div class="flex items-center bg-[#1C1C1C] p-2 rounded-full text-red-500">
                                    <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>22</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>