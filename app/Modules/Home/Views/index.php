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

    <section class="py-52">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <!-- Titre de la section -->
            <div class="text-center mb-12">
                <h2 class="text-3xl font-semibold text-white">Découvrez un apprentissage interactif et communautaire</h2>
                <p class="text-xl text-gray-400 mt-2">Découvrez les contenus préférés de notre communauté</p>
            </div>

            <!-- Conteneur pour les cartes -->
            <div class="flex flex-wrap justify-center gap-6">
                <!-- Carte 1 -->
                <div class="w-96 mt-12 flex flex-col items-center h-full transition-transform duration-300 hover:scale-[1.02] hover:shadow-xl">
                    <div class="bg-gray-800 rounded-xl overflow-hidden shadow-[8px_8px_0_0_rgba(28,28,28,1)] w-full h-full">
                        <div class="p-6 flex flex-col h-full bg-[#303034]">
                            <!-- Icône + badge niveau -->
                            <div class="flex items-center mb-4">
                                <div class="bg-[#676CF2] p-4 rounded-lg">
                                    <img src="/public/assets/img/level.svg" alt="icon de niveau" class="w-8 h-8">
                                </div>
                            </div>

                            <!-- Titre de la carte -->
                            <h2 class="text-xl font-bold text-white mb-3">
                                Progression par niveau
                            </h2>

                            <!-- Description -->
                            <p class="text-base text-[#A3A3A3] mb-5 flex-grow">
                                Des parcours d'apprentissage adaptés à tous, du débutant à l'expert, avec des exemples concrets à chaque étape.
                            </p>

                            <!-- Pied de carte -->
                            <div class="flex justify-between items-center mt-auto">
                                <a href="#" class="bg-gradient-to-l from-[#A5A6FF] to-[#6264F0] bg-clip-text text-transparent font-semibold flex items-center">
                                    Voir les parcours
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carte 2 -->
                <div class="w-96 flex flex-col items-center h-full transition-transform duration-300 hover:scale-[1.02] hover:shadow-xl">
                    <div class="bg-gray-800 rounded-xl overflow-hidden shadow-[8px_8px_0_0_rgba(28,28,28,1)] w-full h-full">
                        <div class="p-6 flex flex-col h-full bg-[#303034]">
                            <!-- Icône + badge niveau -->
                            <div class="flex items-center mb-4">
                                <div class="bg-[#ED4E9D] p-4 rounded-lg">
                                    <img src="/public/assets/img/material-symbols_code.svg" alt="icon symbol code" class="w-8 h-8">
                                </div>
                            </div>

                            <!-- Titre de la carte -->
                            <h2 class="text-xl font-bold text-white mb-3">
                                Exemples interactifs
                            </h2>

                            <!-- Description -->
                            <p class="text-base text-[#A3A3A3] mb-5 flex-grow">
                                Pratiquez Git directement dans votre navigateur avec nos exemples interactifs et visualisez les résultats en temps réel.
                            </p>

                            <!-- Pied de carte -->
                            <div class="flex justify-between items-center mt-auto">
                                <a href="#" class="bg-gradient-to-l from-[#A5A6FF] to-[#6264F0] bg-clip-text text-transparent font-semibold flex items-center">
                                    Essayer maintenant
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carte 3 -->
                <div class="w-96 mt-12 flex flex-col items-center h-full transition-transform duration-300 hover:scale-[1.02] hover:shadow-xl">
                    <div class="bg-gray-800 rounded-xl overflow-hidden shadow-[8px_8px_0_0_rgba(28,28,28,1)] w-full h-full">
                        <div class="p-6 flex flex-col h-full bg-[#303034]">
                            <!-- Icône + badge niveau -->
                            <div class="flex items-center mb-4">
                                <div class="bg-[#08B8D6] p-4 rounded-lg">
                                    <img src="/public/assets/img/users.svg" alt="icon d'utilisateurs" class="w-8 h-8">
                                </div>
                            </div>

                            <!-- Titre de la carte -->
                            <h2 class="text-xl font-bold text-white mb-3">
                                Communauté active
                            </h2>

                            <!-- Description -->
                            <p class="text-base text-[#A3A3A3] mb-5 flex-grow">
                                Rejoignez des développeurs passionnés, partagez vos connaissances et obtenez de l'aide sur Git.
                            </p>

                            <!-- Pied de carte -->
                            <div class="flex justify-between items-center mt-auto">
                                <a href="#" class="bg-gradient-to-l from-[#A5A6FF] to-[#6264F0] bg-clip-text text-transparent font-semibold flex items-center">
                                    Rejoindre la communauté
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section avec titre et sous-titre -->
    <section>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <!-- Titre de la section -->
            <div class="text-center mb-12">
                <h2 class="text-3xl font-semibold text-white">Commencez dès maintenant</h2>
                <p class="text-xl text-gray-400 mt-2">Choisissez votre niveau et plongez dans l'apprentissage de Git</p>
            </div>

            <!-- Conteneur cartes en ligne -->
            <div class="flex flex-wrap justify-center gap-6">
                <!-- Carte -->
                <div class="w-96 transition-transform duration-300 hover:scale-[1.02] hover:shadow-xl">
                    <div class="bg-gray-800 rounded-xl overflow-hidden shadow-[8px_8px_0_0_rgba(28,28,28,1)] w-full h-full">
                        <div class="p-6 flex flex-col h-full bg-[#303034]">
                            <!-- Badge niveau -->
                            <div class="flex items-center mb-4">
                                <div class="font-semibold bg-[#198754] text-[#D3EDE4] text-sm px-3 py-1 rounded-full">
                                    Débutant
                                </div>
                            </div>

                            <!-- Titre -->
                            <h2 class="text-xl font-bold text-white mb-3">Les bases de Git</h2>

                            <!-- Description -->
                            <p class="text-base text-[#A3A3A3] mb-5 flex-grow">
                                Découvrez les commandes fondamentales et comprenez les concepts clés de Git.
                            </p>

                            <!-- Checklist -->
                            <div class="flex text-white gap-2 mb-1">
                                <img src="public/assets/img/check_green.svg" alt="" class="fill-[#D1EEE3]">
                                <p>Installation et configuration</p>
                            </div>
                            <div class="flex text-white gap-2 mb-1">
                                <img src="public/assets/img/check_green.svg" alt="">
                                <p>Premiers commits</p>
                            </div>
                            <div class="flex text-white gap-2 mb-1">
                                <img src="public/assets/img/check_green.svg" alt="">
                                <p>Branches simples</p>
                            </div>
                            <div class="flex text-white gap-2 mb-1">
                                <img src="public/assets/img/check_green.svg" alt="">
                                <p>Utilisation de GitHub</p>
                            </div>

                            <!-- Bouton -->
                            <div class="p-[2px] mt-5 rounded-lg bg-gradient-to-r from-[#A5A6FF] to-[#6264F0] inline-block">
                                <button class="bg-[#303034] text-white rounded-lg px-4 py-2 w-full h-full">
                                    Commencer le niveau Débutant
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-96 transition-transform duration-300 hover:scale-[1.02] hover:shadow-xl">
                    <div class="bg-gray-800 rounded-xl overflow-hidden shadow-[8px_8px_0_0_rgba(28,28,28,1)] w-full h-full">
                        <div class="p-6 flex flex-col h-full bg-[#303034]">
                            <!-- Badge niveau -->
                            <div class="flex items-center mb-4">
                                <div class="font-semibold bg-[#ED4E9D] text-[#F2DAEC] text-sm px-3 py-1 rounded-full">
                                Avancé
                                </div>
                            </div>

                            <!-- Titre -->
                            <h2 class="text-xl font-bold text-white mb-3">Techniques avancées</h2>

                            <!-- Description -->
                            <p class="text-base text-[#A3A3A3] mb-5 flex-grow">
                                Perfectionnez vos connaissances et apprenez des workflows professionnels.
                            </p>

                            <!-- Checklist -->
                            <div class="flex text-white gap-2 mb-1">
                                <img src="public/assets/img/check_pink.svg" alt="" class="fill-[#D1EEE3]">
                                <p>Git interactive rebase</p>
                            </div>
                            <div class="flex text-white gap-2 mb-1">
                                <img src="public/assets/img/check_pink.svg" alt="">
                                <p>Git cherry-pick et reflog</p>
                            </div>
                            <div class="flex text-white gap-2 mb-1">
                                <img src="public/assets/img/check_pink.svg" alt="">
                                <p>Sous-modules Git</p>
                            </div>
                            <div class="flex text-white gap-2 mb-1">
                                <img src="public/assets/img/check_pink.svg" alt="">
                                <p>Git filter-branch</p>
                            </div>

                            <!-- Bouton -->
                            <div class="p-[2px] mt-5 rounded-lg bg-gradient-to-r from-[#A5A6FF] to-[#6264F0] inline-block">
                                <button class="bg-[#303034] text-white rounded-lg px-4 py-2 w-full h-full">
                                    Commencer le niveau Avancé
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-96 transition-transform duration-300 hover:scale-[1.02] hover:shadow-xl">
                    <div class="bg-gray-800 rounded-xl overflow-hidden shadow-[8px_8px_0_0_rgba(28,28,28,1)] w-full h-full">
                        <div class="p-6 flex flex-col h-full bg-[#303034]">
                            <!-- Badge niveau -->
                            <div class="flex items-center mb-4">
                                <div class="font-semibold bg-[#DC3545] text-[#F4DBE0] text-sm px-3 py-1 rounded-full">
                                    Expert
                                </div>
                            </div>

                            <!-- Titre -->
                            <h2 class="text-xl font-bold text-white mb-3">Maîtrise complète</h2>

                            <!-- Description -->
                            <p class="text-base text-[#A3A3A3] mb-5 flex-grow">
                            Devenez un expert Git et optimisez vos workflows d'équipe.
                            </p>

                            <!-- Checklist -->
                            <div class="flex text-white gap-2 mb-1">
                                <img src="public/assets/img/check_red.svg" alt="" class="fill-[#D1EEE3]">
                                <p>Git hooks et automatisation</p>
                            </div>
                            <div class="flex text-white gap-2 mb-1">
                                <img src="public/assets/img/check_red.svg" alt="">
                                <p>Reflog et récupération avancée</p>
                            </div>
                            <div class="flex text-white gap-2 mb-1">
                                <img src="public/assets/img/check_red.svg" alt="">
                                <p>Sous-modules et arbres</p>
                            </div>
                            <div class="flex text-white gap-2 mb-1">
                                <img src="public/assets/img/check_red.svg" alt="">
                                <p>Git en entreprise et CI/CD</p>
                            </div>

                            <!-- Bouton -->
                            <div class="p-[2px] mt-5 rounded-lg bg-gradient-to-r from-[#A5A6FF] to-[#6264F0] inline-block">
                                <button class="bg-[#303034] text-white rounded-lg px-4 py-2 w-full h-full">
                                    Commencer le niveau Expert
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


</main>