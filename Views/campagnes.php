<?php
$pageTitle = 'Campagnes';
$currentPage = 'campagnes';
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
<section class="container py-5">
  <h1 class="section-title mb-4">Nos campagnes et <span>événements</span></h1>
  <div class="row g-4">
  <?php foreach ($campagnes as $c): ?>
    <div class="col-md-4"><div class="feature-card text-start">
      <div class="date-badge mb-3"><strong class="fs-4"><?= $c["jour"] ?></strong><br><?= $c["mois"] ?></div>
      <h5><?= htmlspecialchars($c["titre"]) ?></h5>
      <p><i class="bi bi-geo-alt-fill text-danger"></i> <?= htmlspecialchars($c["ville"]) ?></p>
      <a href="#" class="btn btn-outline-lb btn-sm">Participer →</a>
    </div></div>
  <?php endforeach; ?>
  </div>
</section>
<!-- ===== Fin du contenu spécifique ===== -->

<?php require_once __DIR__ . '/includes/footer.php'; ?>
<?php require_once __DIR__ . '/includes/scripts.php'; ?>
</body>
</html>
