<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Gitopedia - Framework HMVC' ?></title>
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- JavaScript -->
    <script defer type="module" src="/assets/js/components/gitCardComponent.js"></script>
    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/dist/output.css">
    <?php if (isset($extraCss)): ?>
        <?= $extraCss ?>
    <?php endif; ?>
</head>

<body class="font-[Montserrat] bg-[#161618]">
    <div class="grid grid-cols-12 gap-6">

        <!-- En-tête -->
        <header class="col-span-12 bg-[#303034] text-white py-6 fixed w-full z-50">
            <div class="container mx-auto px-4 sm:px-6 lg:px-12 flex items-center justify-between">
                <a href="/" class="flex items-center gap-3">
                    <img src="assets/img/logoGit.png" alt="Logo Git" class="h-8" />
                    <span class="text-xl font-bold bg-gradient-to-l from-[#A5A6FF] to-[#6264F0] bg-clip-text text-transparent">Gitopedia</span>
                </a>
                <nav>
                    <ul class="flex gap-6 font-semibold">
                        <li><a href="/" class="hover:text-transparent hover:bg-gradient-to-l hover:from-[#A5A6FF] hover:to-[#6264F0] hover:bg-clip-text hover:underline">Accueil</a></li>
                        <li><a href="/actualite" class="hover:text-transparent hover:bg-gradient-to-l hover:from-[#A5A6FF] hover:to-[#6264F0] hover:bg-clip-text hover:underline">Actualité</a></li>
                        <li><a href="/documentation" class="hover:text-transparent hover:bg-gradient-to-l hover:from-[#A5A6FF] hover:to-[#6264F0] hover:bg-clip-text hover:underline">Documentation</a></li>
                        <li><a href="/a-propos" class="hover:text-transparent hover:bg-gradient-to-l hover:from-[#A5A6FF] hover:to-[#6264F0] hover:bg-clip-text hover:underline">À propos</a></li>
                    </ul>
                </nav>
                <div class="flex gap-3">
                    <button class="bg-[#303034] text-[#fff] font-bold px-4 py-1.5 rounded-md">Connexion</button>
                    <button class="bg-gradient-to-l from-[#A5A6FF] to-[#6264F0] text-[#161618] font-bold px-4 rounded-md">Inscription</button>
                </div>
            </div>
        </header>