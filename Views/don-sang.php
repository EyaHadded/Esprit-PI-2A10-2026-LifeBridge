<?php
$pageTitle = 'Don de sang';
$currentPage = 'don-sang';
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
    <h1 class="section-title">Don de sang <br><span>Un don qui sauve des vies</span></h1>
    <p class="my-4">Le don de sang est un acte généreux et solidaire qui permet d'aider des milliers de patients.</p>
    <ul class="list-unstyled"><li><i class="bi bi-check-circle-fill text-danger"></i> Aider les malades</li><li><i class="bi bi-check-circle-fill text-danger"></i> Renforcer les réserves</li><li><i class="bi bi-check-circle-fill text-danger"></i> Partager la vie</li></ul>
    <a href="index.php?page=connexion" class="btn btn-lb">S'inscrire comme donneur →</a>
  </div>
  <div class="col-lg-6"><div class="p-4 rounded-4" style="background:var(--lb-pink)"><h5 class="text-center text-primary-emphasis">Les groupes sanguins</h5>
    <div class="text-center"><?php foreach ($groupes as $g): ?><span class="blood-drop"><?= htmlspecialchars($g) ?></span><?php endforeach; ?></div></div></div>
</div></section>
<!-- ===== Fin du contenu spécifique ===== -->

<?php require_once __DIR__ . '/includes/footer.php'; ?>
<?php require_once __DIR__ . '/includes/scripts.php'; ?>
</body>
</html>
