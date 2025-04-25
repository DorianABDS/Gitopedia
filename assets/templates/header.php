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
  <link rel="stylesheet" href="/assets/css/styles">
  <?php if (isset($extraCss)): ?>
    <?= $extraCss ?>
  <?php endif; ?>
</head>

<body class="font-[Montserrat]">
  <header class="bg-[#303034] text-white px-4 sm:px-6 lg:px-12 py-6">
    <div class="container mx-auto grid grid-cols-1 md:grid-cols-12 gap-6 items-center">

      <div class="md:col-span-4 flex items-center gap-3">
        <img src="logo.png" alt="Logo Git" class="h-8" />
        <span class="text-xl font-bold bg-gradient-to-l from-[#A5A6FF] to-[#6264F0] bg-clip-text text-transparent">Gitopedia</span>
      </div>

      <nav class="md:col-span-4">
        <ul class="flex flex-col md:flex-row justify-center items-start md:items-center gap-3 md:gap-6 text-base font-semibold">
          <li><a href="/" class="hover:text-transparent hover:bg-gradient-to-l hover:from-[#A5A6FF] hover:to-[#6264F0] hover:bg-clip-text hover:underline">Accueil</a></li>
          <li><a href="/actualite" class="hover:text-transparent hover:bg-gradient-to-l hover:from-[#A5A6FF] hover:to-[#6264F0] hover:bg-clip-text hover:underline">Actualité</a></li>
          <li><a href="/documentation" class="hover:text-transparent hover:bg-gradient-to-l hover:from-[#A5A6FF] hover:to-[#6264F0] hover:bg-clip-text hover:underline">Documentation</a></li>
          <li><a href="/a-propos" class="hover:text-transparent hover:bg-gradient-to-l hover:from-[#A5A6FF] hover:to-[#6264F0] hover:bg-clip-text hover:underline">À propos</a></li>
        </ul>
      </nav>

      <div class="md:col-span-4 flex justify-start md:justify-end gap-3 text-base">
        <button class="bg-[#303034] text-[#fff] font-bold px-4 py-1.5 rounded">Connexion</button>
        <button class="bg-gradient-to-l from-[#A5A6FF] to-[#6264F0] text-[#303034] font-bold px-4 rounded">Inscription</button>
      </div>

    </div>
  </header>