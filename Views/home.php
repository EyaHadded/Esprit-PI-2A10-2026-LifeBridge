<?php
$pageTitle = 'Accueil';
$currentPage = 'home';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<?php require_once __DIR__ . '/includes/head.php'; ?>
</head>
<body>
<?php require_once __DIR__ . '/includes/spinner.php'; ?>
<?php require_once __DIR__ . '/includes/navbar.php'; ?>

<!-- ===== Contenu spécifique à la page ===== -->
<section class="hero"><div class="container"><div class="row align-items-center">
  <div class="col-lg-6">
    <h1>Ensemble,<br><span>sauvons des vies</span></h1>
    <p class="lead my-4">Donnez votre sang, devenez donneur d'organes, et faites la différence.</p>
    <a href="index.php?page=connexion" class="btn btn-lb me-2"><i class="bi bi-droplet-fill"></i> Je donne</a>
    <a href="index.php?page=don-sang" class="btn btn-outline-lb">En savoir plus →</a>
  </div>
  <div class="col-lg-6 text-center"><i class="bi bi-droplet-fill" style="font-size:12rem;color:var(--lb-red)"></i></div>
</div></div></section>
<section class="container mt-n5 pb-4"><div class="row g-4 text-center">
  <div class="col-md-4"><div class="feature-card"><i class="bi bi-droplet"></i><h5 class="mt-2">Don de sang</h5><p class="mb-0">Un geste simple</p></div></div>
  <div class="col-md-4"><div class="feature-card"><i class="bi bi-heart-pulse"></i><h5 class="mt-2">Don d'organes</h5><p class="mb-0">Une seconde chance</p></div></div>
  <div class="col-md-4"><div class="feature-card"><i class="bi bi-people"></i><h5 class="mt-2">Solidarité</h5><p class="mb-0">Une société plus humaine</p></div></div>
</div></section>
<!-- ===== Fin du contenu spécifique ===== -->

<?php require_once __DIR__ . '/includes/footer.php'; ?>
<?php require_once __DIR__ . '/includes/scripts.php'; ?>
</body>
</html>
