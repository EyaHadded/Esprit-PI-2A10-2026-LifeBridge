<?php
$pageTitle = "Don d'organes";
$currentPage = 'don-organes';
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
<section class="container py-5"><div class="row align-items-center g-5">
  <div class="col-lg-6">
    <h1 class="section-title">Don d'organes <br><span>Offrez une nouvelle chance</span></h1>
    <p class="my-4">Le don d'organes permet à des personnes en attente de greffe de retrouver l'espoir de vivre.</p>
    <a href="index.php?page=connexion" class="btn btn-outline-lb">En savoir plus →</a>
  </div>
  <div class="col-lg-6 row g-3 text-center">
    <div class="col-4"><div class="feature-card"><i class="bi bi-heart-pulse"></i><p class="mb-0 mt-2">Un geste altruiste</p></div></div>
    <div class="col-4"><div class="feature-card"><i class="bi bi-award"></i><p class="mb-0 mt-2">Une vie sauvée</p></div></div>
    <div class="col-4"><div class="feature-card"><i class="bi bi-people"></i><p class="mb-0 mt-2">Un espoir partagé</p></div></div>
  </div>
</div></section>
<!-- ===== Fin du contenu spécifique ===== -->

<?php require_once __DIR__ . '/includes/footer.php'; ?>
<?php require_once __DIR__ . '/includes/scripts.php'; ?>
</body>
</html>
