<!-- 
/**
 * header.php - Partie supérieure du template HTML
 * 
 * Ce fichier définit la structure du début de chaque page HTML dans l'application.
 * Il contient :
 * - La déclaration DOCTYPE et l'ouverture des balises html et body
 * - Les métadonnées essentielles pour le référencement et l'affichage responsive
 * - Le titre dynamique de la page
 * - Les inclusions de CSS (Bootstrap et personnalisé)
 * - Support pour l'ajout de CSS spécifique à certaines pages
 * 
 * Le header est inclus automatiquement dans toutes les pages via le layout.php
 * principal, assurant ainsi une structure HTML cohérente à travers l'application.
 */
-->
<!DOCTYPE html>
<html lang="fr">

<head>
    <!-- Encodage des caractères - UTF-8 pour le support complet des caractères internationaux -->
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

<header class="bg-[#303034] h-16 flex items-center justify-between md:px-12">
  <div class="flex items-center gap-2">
    <img src="logo.png" alt="Logo Git" class="h-8" />
    <span class="text-xl font-bold text-white">Gitopedia</span>
  </div>

  <nav>
    <ul class="flex gap-6 text-white">
      <li><a href="/" class="hover:underline">Accueil</a></li>
      <li><a href="/actualite" class="hover:underline">Actualité</a></li>
      <li><a href="/a-propos" class="hover:underline">À propos</a></li>
      <li><a href="/documentation" class="hover:underline">Documentation</a></li>
    </ul>
  </nav>

  <div class="flex items-center gap-3">
    <button class="bg-white text-[#303034] px-3 py-1 rounded">Connexion</button>
    <button class="bg-white text-[#303034] px-3 py-1 rounded">Inscription</button>
  </div>
</header>


<body>
    <!-- Le contenu de la page sera inséré ici, suivi par footer.php -->