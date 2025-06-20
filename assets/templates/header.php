<!DOCTYPE html>
<html lang="fr">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Gitopedia - Framework HMVC' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;700&display=swap" rel="stylesheet">
    <?php if (isset($extraCss)): ?>
        <?= $extraCss ?>
    <?php endif; ?>
    <meta charset="UTF-8">

    <!-- Configuration d'affichage responsive - assure le rendu correct sur tous les appareils -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- 
        Titre de la page - dynamique avec fallback
        La variable $title est fournie par le contrôleur via la classe Layout
        L'opérateur de coalescence null (??) permet d'utiliser un titre par défaut
        si la variable $title n'est pas définie
    -->
    <title><?= $title ?? 'Gitopedia - Framework HMVC' ?></title>

    <!-- Lien vers le CDN de Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Ajouter les liens pour les polices externes -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;700&display=swap" rel="stylesheet">
    
    <!-- 
        Inclusions CSS conditionnelles
        Permet d'ajouter des styles spécifiques à certaines pages uniquement
        La variable $extraCss peut être définie dans le contrôleur pour inclure
        des feuilles de styles additionnelles ou des styles inline
        -->
    <link rel="stylesheet" href="/assets/css/styles">

    <?php if (isset($extraCss)): ?>
        <?= $extraCss ?>
    <?php endif; ?>
</head>

<body class="font-[Montserrat] bg-[#161618]">
    <div class="grid grid-cols-12 gap-6">

        <header class="col-span-12 bg-[#161618] text-white py-4 fixed w-full z-50 border-b border-[#303034]">
            <div class="container mx-auto px-4 sm:px-6 lg:px-12 flex items-center justify-between">
                <a href="/" class="flex items-center gap-3 transition-all duration-200 transform hover:scale-[1.03] active:scale-[0.95]">
                    <img src="assets/img/logoGit.png" alt="Logo Git" class="h-8" />
                    <span class="text-xl font-bold bg-gradient-to-l from-[#A5A6FF] to-[#6264F0] bg-clip-text text-transparent">Gitopedia</span>
                </a>
                <nav>
                    <ul class="flex gap-6 font-medium">
                        <li><a href="/" class="hover:text-transparent hover:bg-gradient-to-l hover:from-[#A5A6FF] hover:to-[#6264F0] hover:bg-clip-text hover:underline">Accueil</a></li>
                        <li><a href="/actualite" class="hover:text-transparent hover:bg-gradient-to-l hover:from-[#A5A6FF] hover:to-[#6264F0] hover:bg-clip-text hover:underline">Actualité</a></li>
                        <li><a href="/documentation" class="hover:text-transparent hover:bg-gradient-to-l hover:from-[#A5A6FF] hover:to-[#6264F0] hover:bg-clip-text hover:underline">Documentation</a></li>
                        <li><a href="/a-propos" class="hover:text-transparent hover:bg-gradient-to-l hover:from-[#A5A6FF] hover:to-[#6264F0] hover:bg-clip-text hover:underline">À propos</a></li>
                    </ul>
                </nav>
                <div class="flex gap-3">
                    <a href="#" class="bg-[#303034] text-white font-semibold px-4 py-1.5 rounded-md transition-all duration-200 transform hover:scale-[1.03] active:scale-[0.95]">
                        Connexion
                    </a>
                    <a href="#" class="bg-gradient-to-l from-[#A5A6FF] to-[#6264F0] text-[#161618] font-semibold px-4 py-1.5 rounded-md transition-all duration-200 transform hover:scale-[1.03] active:scale-[0.95]">
                        Inscription
                    </a>
                </div>


            </div>
        </header>