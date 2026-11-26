<?php
$pageTitle = 'Espace Citoyen';
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

<section class="container py-5">
  <h1 class="section-title">Espace <span>Citoyen</span></h1>
  <p class="lead">Bienvenue, <?= e($user->getNom()) ?>.</p>

  <div class="feature-card text-start mb-4">
    <p class="mb-1"><strong>Rôle :</strong> Citoyen</p>
    <p class="mb-0"><strong>E-mail :</strong> <?= e($user->getEmail()) ?></p>
  </div>

  <div class="d-flex flex-wrap gap-2">
    <a href="index.php?page=profil" class="btn btn-lb">
      <i class="bi bi-person-gear me-1"></i> Gérer mon profil
    </a>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
<?php require_once __DIR__ . '/includes/scripts.php'; ?>
</body>
</html>
