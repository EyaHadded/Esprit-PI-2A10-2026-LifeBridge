<?php
$pageTitle = 'Espace Agent de santé';
$currentPage = 'espace';
// Variable fournie par EspaceController : $user (User)
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
  <h1 class="section-title">Espace <span>Agent de santé</span></h1>
  <p class="lead">Bienvenue, <?= e($user->getNom()) ?>.</p>
  <div class="feature-card text-start mb-4">
    <p class="mb-1"><strong>Rôle :</strong> Agent de santé</p>
    <p class="mb-0"><strong>E-mail :</strong> <?= e($user->getEmail()) ?></p>
  </div>
  <a href="index.php?page=demandes" class="btn btn-lb me-2">Demandes</a>
  <a href="index.php?page=campagnes" class="btn btn-outline-lb">Campagnes</a>
</section>
<!-- ===== Fin du contenu spécifique ===== -->

<?php require_once __DIR__ . '/includes/footer.php'; ?>
<?php require_once __DIR__ . '/includes/scripts.php'; ?>
</body>
</html>
